<?php
declare(strict_types=1);

header('Content-Type: application/json');

require_once '../includes/init.php';
require_admin();

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

function safe_json(?string $s): ?string
{
    if ($s === null || trim($s) === '') return null;
    $j = json_decode($s, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new RuntimeException('config_json نامعتبر است');
    }
    return json_encode($j, JSON_UNESCAPED_UNICODE);
}

try {
    $pdo = db();

    switch ($action) {
        case 'list_providers':
            $st = $pdo->query("SELECT id, name FROM external_api_providers WHERE status=1 ORDER BY priority, id");
            echo json_encode(['ok' => true, 'items' => ($st->fetchAll() ?: [])]);
            break;

        case 'list_services':
            $st = $pdo->query("
                SELECT s.*, p.name AS provider_name
                FROM identity_verification_services s
                LEFT JOIN external_api_providers p ON p.id=s.provider_id
                ORDER BY s.sort_order ASC, s.id DESC
            ");
            echo json_encode(['data' => ($st->fetchAll() ?: [])]);
            break;

        case 'get_service':
            $id = (int)($_GET['id'] ?? 0);
            $st = $pdo->prepare("SELECT * FROM identity_verification_services WHERE id=? LIMIT 1");
            $st->execute([$id]);
            $row = $st->fetch();
            echo json_encode(['ok' => true, 'item' => $row ?: null]);
            break;

        case 'save_service':
            $id = (int)($_POST['id'] ?? 0);
            $code = trim((string)($_POST['code'] ?? ''));
            $title = trim((string)($_POST['title'] ?? ''));
            $description = trim((string)($_POST['description'] ?? ''));
            $subject_kind = (int)($_POST['subject_kind'] ?? 0);
            $provider_id = (int)($_POST['provider_id'] ?? 0);
            $http_method = strtoupper(trim((string)($_POST['http_method'] ?? 'POST')));
            $endpoint_path = trim((string)($_POST['endpoint_path'] ?? ''));
            $is_active = (int)($_POST['is_active'] ?? 1);
            $sort_order = (int)($_POST['sort_order'] ?? 100);
            $config_json_raw = $_POST['config_json'] ?? null;
            $config_json = safe_json(is_string($config_json_raw) ? $config_json_raw : null);

            if ($code === '' || $title === '' || $provider_id <= 0 || $endpoint_path === '') {
                throw new RuntimeException('فیلدهای اجباری ناقص است');
            }
            if (!in_array($subject_kind, [0,1,2], true)) $subject_kind = 0;
            if (!in_array($http_method, ['GET','POST','PUT','PATCH','DELETE'], true)) $http_method = 'POST';
            $is_active = $is_active ? 1 : 0;

            if ($id > 0) {
                $st = $pdo->prepare("
                    UPDATE identity_verification_services
                    SET code=:code, title=:title, description=:description,
                        subject_kind=:subject_kind, provider_id=:provider_id,
                        http_method=:http_method, endpoint_path=:endpoint_path,
                        config_json=:config_json, is_active=:is_active, sort_order=:sort_order
                    WHERE id=:id
                    LIMIT 1
                ");
                $st->execute([
                    ':code' => $code,
                    ':title' => $title,
                    ':description' => ($description === '' ? null : $description),
                    ':subject_kind' => $subject_kind,
                    ':provider_id' => $provider_id,
                    ':http_method' => $http_method,
                    ':endpoint_path' => $endpoint_path,
                    ':config_json' => $config_json,
                    ':is_active' => $is_active,
                    ':sort_order' => $sort_order,
                    ':id' => $id,
                ]);
            } else {
                $st = $pdo->prepare("
                    INSERT INTO identity_verification_services
                    (code, title, description, subject_kind, provider_id, http_method, endpoint_path, config_json, is_active, sort_order, created_at, updated_at)
                    VALUES
                    (:code,:title,:description,:subject_kind,:provider_id,:http_method,:endpoint_path,:config_json,:is_active,:sort_order,NOW(3),NOW(3))
                ");
                $st->execute([
                    ':code' => $code,
                    ':title' => $title,
                    ':description' => ($description === '' ? null : $description),
                    ':subject_kind' => $subject_kind,
                    ':provider_id' => $provider_id,
                    ':http_method' => $http_method,
                    ':endpoint_path' => $endpoint_path,
                    ':config_json' => $config_json,
                    ':is_active' => $is_active,
                    ':sort_order' => $sort_order,
                ]);
                $id = (int)$pdo->lastInsertId();
            }

            echo json_encode(['ok' => true, 'id' => $id]);
            break;

        case 'delete_service':
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) throw new RuntimeException('شناسه نامعتبر است');
            $st = $pdo->prepare("DELETE FROM identity_verification_services WHERE id=? LIMIT 1");
            $st->execute([$id]);
            echo json_encode(['ok' => true]);
            break;

        default:
            echo json_encode(['ok' => false, 'message' => 'action نامعتبر است']);
    }
} catch (Throwable $e) {
    $debug = (string)env('APP_DEBUG', '0') === '1';
    echo json_encode(['ok' => false, 'message' => $debug ? $e->getMessage() : 'خطای سرور']);
}
