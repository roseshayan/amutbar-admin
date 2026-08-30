<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_admin();

header('Content-Type: application/json; charset=utf-8');

$pdo = db();
$action = (string)($_GET['action'] ?? $_POST['action'] ?? '');
$adminId = admin_id();

function faq_json_error(string $message, int $status = 422): never
{
    http_response_code($status);
    echo json_encode(['ok' => false, 'message' => $message], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function faq_validate_app_id(int $appId): int
{
    if (!in_array($appId, [1, 2], true)) faq_json_error('اپلیکیشن هدف نامعتبر است');
    return $appId;
}

function faq_validate_http_url(?string $url, string $label): ?string
{
    $url = trim((string)$url);
    if ($url === '') return null;
    if (!filter_var($url, FILTER_VALIDATE_URL)) faq_json_error($label . ' نامعتبر است');
    $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
    if (!in_array($scheme, ['http', 'https'], true)) faq_json_error($label . ' باید با http یا https شروع شود');
    return $url;
}

function faq_upload_file(array $file, string $kind): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        faq_json_error('آپلود فایل ناموفق بود');
    }

    $tmp = (string)($file['tmp_name'] ?? '');
    $size = (int)($file['size'] ?? 0);
    if ($tmp === '' || !is_uploaded_file($tmp) || $size <= 0) faq_json_error('فایل آپلودشده معتبر نیست');

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string)$finfo->file($tmp);

    if ($kind === 'image') {
        $max = 5 * 1024 * 1024;
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];
        if ($size > $max) faq_json_error('حجم تصویر باید حداکثر ۵ مگابایت باشد');
        if (!isset($allowed[$mime]) || @getimagesize($tmp) === false) faq_json_error('فرمت تصویر مجاز نیست');
    } else {
        $max = 40 * 1024 * 1024;
        $allowed = [
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
            'video/quicktime' => 'mov',
        ];
        if ($size > $max) faq_json_error('حجم ویدئو باید حداکثر ۴۰ مگابایت باشد');
        if (!isset($allowed[$mime])) faq_json_error('فرمت ویدئو مجاز نیست (MP4/WebM/MOV)');
    }

    $dir = BASE_PATH . '/storage/uploads/faqs';
    ensure_dir($dir);
    $filename = $kind . '_' . bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $dest = $dir . '/' . $filename;
    if (!move_uploaded_file($tmp, $dest)) faq_json_error('ذخیره فایل ناموفق بود', 500);
    @chmod($dest, 0640);

    return 'storage/uploads/faqs/' . $filename;
}

function faq_delete_media(?string $key): void
{
    $key = trim((string)$key);
    if ($key === '' || !str_starts_with($key, 'storage/uploads/faqs/')) return;
    $path = BASE_PATH . '/' . ltrim($key, '/');
    if (is_file($path)) @unlink($path);
}

if ($action === 'categories_list') {
    $rows = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM faq_items i WHERE i.category_id=c.id) AS items_count FROM faq_categories c ORDER BY c.target_app_id, c.sort_order, c.id")->fetchAll();
    $data = [];
    foreach ($rows as $r) {
        $appBadge = (int)$r['target_app_id'] === 1
            ? '<span class="badge bg-primary-transparent">رانندگان</span>'
            : '<span class="badge bg-success-transparent">اعلام بار</span>';
        $status = (int)$r['is_active'] === 1
            ? '<span class="badge bg-success">فعال</span>'
            : '<span class="badge bg-danger">غیرفعال</span>';
        $data[] = [
            'id' => (int)$r['id'],
            'app' => $appBadge,
            'title' => '<strong>' . htmlspecialchars((string)$r['title'], ENT_QUOTES, 'UTF-8') . '</strong><br><small class="text-muted">' . htmlspecialchars((string)($r['description'] ?? ''), ENT_QUOTES, 'UTF-8') . '</small>',
            'icon_key' => htmlspecialchars((string)$r['icon_key'], ENT_QUOTES, 'UTF-8'),
            'sort_order' => (int)$r['sort_order'],
            'items_count' => (int)$r['items_count'],
            'status' => $status,
            'actions' => '<button class="btn btn-sm btn-outline-primary me-1" onclick="editCategory(' . (int)$r['id'] . ')">ویرایش</button>'
                . '<button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(' . (int)$r['id'] . ')">حذف</button>',
        ];
    }
    echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($action === 'category_get') {
    $id = (int)($_GET['id'] ?? 0);
    $st = $pdo->prepare('SELECT * FROM faq_categories WHERE id=? LIMIT 1');
    $st->execute([$id]);
    $row = $st->fetch();
    if (!$row) faq_json_error('دسته‌بندی یافت نشد', 404);
    echo json_encode(['ok' => true, 'item' => $row], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($action === 'category_options') {
    $appId = isset($_GET['target_app_id']) ? faq_validate_app_id((int)$_GET['target_app_id']) : 0;
    $sql = 'SELECT id, target_app_id, title FROM faq_categories';
    $params = [];
    if ($appId > 0) {
        $sql .= ' WHERE target_app_id=?';
        $params[] = $appId;
    }
    $sql .= ' ORDER BY target_app_id, sort_order, title';
    $st = $pdo->prepare($sql);
    $st->execute($params);
    echo json_encode(['ok' => true, 'items' => $st->fetchAll()], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($action === 'category_save') {
    $id = (int)($_POST['id'] ?? 0);
    $appId = faq_validate_app_id((int)($_POST['target_app_id'] ?? 0));
    $title = trim((string)($_POST['title'] ?? ''));
    $description = trim((string)($_POST['description'] ?? ''));
    $iconKey = trim((string)($_POST['icon_key'] ?? 'general'));
    $sortOrder = max(0, (int)($_POST['sort_order'] ?? 100));
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($title === '' || mb_strlen($title) > 160) faq_json_error('عنوان دسته‌بندی الزامی است و حداکثر ۱۶۰ کاراکتر دارد');
    if (mb_strlen($description) > 255) faq_json_error('توضیح دسته‌بندی حداکثر ۲۵۵ کاراکتر دارد');
    $allowedIcons = ['install', 'auth', 'search_load', 'request', 'history', 'transport', 'insurance', 'identity', 'support', 'general'];
    if (!in_array($iconKey, $allowedIcons, true)) $iconKey = 'general';

    if ($id > 0) {
        $st = $pdo->prepare('UPDATE faq_categories SET target_app_id=?, title=?, description=?, icon_key=?, sort_order=?, is_active=?, updated_at=NOW(3) WHERE id=? LIMIT 1');
        $st->execute([$appId, $title, $description !== '' ? $description : null, $iconKey, $sortOrder, $isActive, $id]);
    } else {
        $st = $pdo->prepare('INSERT INTO faq_categories (target_app_id,title,description,icon_key,sort_order,is_active,created_by_user_id,created_at,updated_at) VALUES (?,?,?,?,?,?,?,NOW(3),NOW(3))');
        $st->execute([$appId, $title, $description !== '' ? $description : null, $iconKey, $sortOrder, $isActive, $adminId]);
        $id = (int)$pdo->lastInsertId();
    }
    echo json_encode(['ok' => true, 'id' => $id, 'message' => 'دسته‌بندی ذخیره شد'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'category_delete') {
    $id = (int)($_POST['id'] ?? 0);
    $st = $pdo->prepare('SELECT image_key, video_key FROM faq_items WHERE category_id=?');
    $st->execute([$id]);
    $media = $st->fetchAll();
    $pdo->beginTransaction();
    try {
        $del = $pdo->prepare('DELETE FROM faq_categories WHERE id=?');
        $del->execute([$id]);
        if ($del->rowCount() < 1) {
            $pdo->rollBack();
            faq_json_error('دسته‌بندی یافت نشد', 404);
        }
        $pdo->commit();
        foreach ($media as $m) {
            faq_delete_media($m['image_key'] ?? null);
            faq_delete_media($m['video_key'] ?? null);
        }
        echo json_encode(['ok' => true]);
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('faq.category_delete failed: ' . $e->getMessage());
        faq_json_error('حذف دسته‌بندی انجام نشد', 500);
    }
    exit;
}

if ($action === 'items_list') {
    $rows = $pdo->query("SELECT i.*, c.title AS category_title, c.target_app_id FROM faq_items i JOIN faq_categories c ON c.id=i.category_id ORDER BY c.target_app_id, c.sort_order, i.sort_order, i.id")->fetchAll();
    $data = [];
    foreach ($rows as $r) {
        $appBadge = (int)$r['target_app_id'] === 1
            ? '<span class="badge bg-primary-transparent">رانندگان</span>'
            : '<span class="badge bg-success-transparent">اعلام بار</span>';
        $media = [];
        if (!empty($r['link_url'])) $media[] = '<span class="badge bg-info-transparent">لینک</span>';
        if (!empty($r['image_key'])) $media[] = '<span class="badge bg-warning-transparent">تصویر</span>';
        if (!empty($r['video_key']) || !empty($r['video_url'])) $media[] = '<span class="badge bg-danger-transparent">ویدئو</span>';
        $status = (int)$r['is_active'] === 1
            ? '<span class="badge bg-success">فعال</span>'
            : '<span class="badge bg-danger">غیرفعال</span>';
        $data[] = [
            'id' => (int)$r['id'],
            'app' => $appBadge,
            'category' => htmlspecialchars((string)$r['category_title'], ENT_QUOTES, 'UTF-8'),
            'question' => '<strong>' . htmlspecialchars((string)$r['question'], ENT_QUOTES, 'UTF-8') . '</strong><br><small class="text-muted">' . htmlspecialchars(mb_strimwidth((string)$r['answer'], 0, 120, '…', 'UTF-8'), ENT_QUOTES, 'UTF-8') . '</small>',
            'media' => $media ? implode(' ', $media) : '-',
            'sort_order' => (int)$r['sort_order'],
            'status' => $status,
            'actions' => '<button class="btn btn-sm btn-outline-primary me-1" onclick="editFaq(' . (int)$r['id'] . ')">ویرایش</button>'
                . '<button class="btn btn-sm btn-outline-danger" onclick="deleteFaq(' . (int)$r['id'] . ')">حذف</button>',
        ];
    }
    echo json_encode(['data' => $data], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($action === 'item_get') {
    $id = (int)($_GET['id'] ?? 0);
    $st = $pdo->prepare('SELECT i.*, c.target_app_id FROM faq_items i JOIN faq_categories c ON c.id=i.category_id WHERE i.id=? LIMIT 1');
    $st->execute([$id]);
    $row = $st->fetch();
    if (!$row) faq_json_error('سوال یافت نشد', 404);
    echo json_encode(['ok' => true, 'item' => $row], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($action === 'item_save') {
    $id = (int)($_POST['id'] ?? 0);
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $question = trim((string)($_POST['question'] ?? ''));
    $answer = trim((string)($_POST['answer'] ?? ''));
    $linkLabel = trim((string)($_POST['link_label'] ?? ''));
    $linkUrl = faq_validate_http_url($_POST['link_url'] ?? null, 'لینک');
    $videoUrl = faq_validate_http_url($_POST['video_url'] ?? null, 'آدرس ویدئو');
    $sortOrder = max(0, (int)($_POST['sort_order'] ?? 100));
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    $removeImage = isset($_POST['remove_image']);
    $removeVideo = isset($_POST['remove_video']);

    if ($categoryId <= 0) faq_json_error('دسته‌بندی را انتخاب کنید');
    $st = $pdo->prepare('SELECT id FROM faq_categories WHERE id=? LIMIT 1');
    $st->execute([$categoryId]);
    if (!$st->fetchColumn()) faq_json_error('دسته‌بندی معتبر نیست');
    if ($question === '' || mb_strlen($question) > 255) faq_json_error('سوال الزامی است و حداکثر ۲۵۵ کاراکتر دارد');
    if ($answer === '') faq_json_error('پاسخ الزامی است');
    if ($linkLabel !== '' && $linkUrl === null) faq_json_error('برای عنوان لینک، آدرس لینک هم وارد کنید');
    if ($linkUrl !== null && $linkLabel === '') $linkLabel = 'مشاهده لینک';

    $old = null;
    if ($id > 0) {
        $st = $pdo->prepare('SELECT image_key, video_key FROM faq_items WHERE id=? LIMIT 1');
        $st->execute([$id]);
        $old = $st->fetch();
        if (!$old) faq_json_error('سوال یافت نشد', 404);
    }

    $imageKey = $old['image_key'] ?? null;
    $videoKey = $old['video_key'] ?? null;
    $deleteAfterCommit = [];
    $newUploads = [];

    if ($removeImage && $imageKey) {
        $deleteAfterCommit[] = $imageKey;
        $imageKey = null;
    }
    if ($removeVideo && $videoKey) {
        $deleteAfterCommit[] = $videoKey;
        $videoKey = null;
    }

    if (!empty($_FILES['image']) && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $newKey = faq_upload_file($_FILES['image'], 'image');
        $newUploads[] = $newKey;
        if ($imageKey) $deleteAfterCommit[] = $imageKey;
        $imageKey = $newKey;
    }
    if (!empty($_FILES['video']) && ($_FILES['video']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        $newKey = faq_upload_file($_FILES['video'], 'video');
        $newUploads[] = $newKey;
        if ($videoKey) $deleteAfterCommit[] = $videoKey;
        $videoKey = $newKey;
        // فایل آپلودی بر URL خارجی اولویت دارد.
        $videoUrl = null;
    }

    try {
        if ($id > 0) {
            $st = $pdo->prepare('UPDATE faq_items SET category_id=?, question=?, answer=?, link_label=?, link_url=?, image_key=?, video_key=?, video_url=?, sort_order=?, is_active=?, updated_at=NOW(3) WHERE id=? LIMIT 1');
            $st->execute([$categoryId, $question, $answer, $linkLabel !== '' ? $linkLabel : null, $linkUrl, $imageKey, $videoKey, $videoUrl, $sortOrder, $isActive, $id]);
        } else {
            $st = $pdo->prepare('INSERT INTO faq_items (category_id,question,answer,link_label,link_url,image_key,video_key,video_url,sort_order,is_active,created_by_user_id,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(3),NOW(3))');
            $st->execute([$categoryId, $question, $answer, $linkLabel !== '' ? $linkLabel : null, $linkUrl, $imageKey, $videoKey, $videoUrl, $sortOrder, $isActive, $adminId]);
            $id = (int)$pdo->lastInsertId();
        }
        foreach (array_unique($deleteAfterCommit) as $key) faq_delete_media((string)$key);
        echo json_encode(['ok' => true, 'id' => $id, 'message' => 'سوال ذخیره شد'], JSON_UNESCAPED_UNICODE);
    } catch (Throwable $e) {
        // فایل‌های جدیدی که به‌دلیل خطای دیتابیس به رکورد نرسیده‌اند،
        // چه در create و چه در update، نباید روی دیسک orphan بمانند.
        foreach (array_unique($newUploads) as $key) {
            faq_delete_media((string)$key);
        }
        error_log('faq.item_save failed: ' . $e->getMessage());
        faq_json_error('ذخیره سوال انجام نشد', 500);
    }
    exit;
}

if ($action === 'item_delete') {
    $id = (int)($_POST['id'] ?? 0);
    $st = $pdo->prepare('SELECT image_key, video_key FROM faq_items WHERE id=? LIMIT 1');
    $st->execute([$id]);
    $row = $st->fetch();
    if (!$row) faq_json_error('سوال یافت نشد', 404);
    $pdo->prepare('DELETE FROM faq_items WHERE id=?')->execute([$id]);
    faq_delete_media($row['image_key'] ?? null);
    faq_delete_media($row['video_key'] ?? null);
    echo json_encode(['ok' => true]);
    exit;
}

faq_json_error('عملیات نامعتبر', 400);
