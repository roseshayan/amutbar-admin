<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/init.php';
require_admin();

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? '';
$pdo = db();

function mf_type_label(int $t): string
{
    return match ($t) {
        1 => 'عکس پروفایل',
        2 => 'عکس کارت ملی',
        3 => 'عکس گواهینامه',
        4 => 'عکس کارت ماشین',
        5 => 'عکس برگه سبز',
        6 => 'ویدئو احراز هویت',
        7 => 'عکس بیمه نامه',
        default => 'نامشخص',
    };
}

function mf_human_size(?int $bytes): string
{
    $b = (int)($bytes ?? 0);
    if ($b <= 0) return '-';
    $u = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    $v = (float)$b;
    while ($v >= 1024 && $i < count($u) - 1) {
        $v /= 1024;
        $i++;
    }
    return rtrim(rtrim(number_format($v, 2, '.', ''), '0'), '.') . ' ' . $u[$i];
}

function mf_guess_mime(string $absPath): string
{
    $mime = '';
    if (function_exists('mime_content_type')) {
        $m = @mime_content_type($absPath);
        if (is_string($m)) $mime = $m;
    }
    if ($mime === '') {
        $ext = strtolower(pathinfo($absPath, PATHINFO_EXTENSION));
        $map = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'mp4' => 'video/mp4',
            'mov' => 'video/quicktime',
            'mkv' => 'video/x-matroska',
            'webm' => 'video/webm',
            '3gp' => 'video/3gpp',
        ];
        if (isset($map[$ext])) $mime = $map[$ext];
    }
    return $mime !== '' ? $mime : 'application/octet-stream';
}

function mf_safe_key(string $k): string
{
    $k = trim($k);
    $k = str_replace('\\', '/', $k);
    $k = ltrim($k, '/');
    // جلوگیری از traversal
    if ($k === '' || str_contains($k, '..')) return '';
    return $k;
}

if ($action === 'list') {
    $fileType = isset($_GET['file_type']) ? (int)$_GET['file_type'] : 0;
    $userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
    $includeFs = !isset($_GET['include_fs']) || (int)$_GET['include_fs'] === 1;

    $where = [];
    $params = [];
    if ($fileType > 0) {
        $where[] = 'uf.file_type=?';
        $params[] = $fileType;
    }
    if ($userId > 0) {
        $where[] = 'uf.user_id=?';
        $params[] = $userId;
    }
    $sqlWhere = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $st = $pdo->prepare("
        SELECT uf.id, uf.user_id, uf.file_type, uf.file_key, uf.mime_type, uf.file_size, uf.created_at,
               u.full_name
        FROM user_files uf
        LEFT JOIN users u ON u.id=uf.user_id
        $sqlWhere
        ORDER BY uf.id DESC
        LIMIT 200
    ");
    $st->execute($params);
    $rows = $st->fetchAll() ?: [];

    $items = [];
    $seenAbs = [];
    foreach ($rows as $r) {
        $id = (int)($r['id'] ?? 0);
        $ft = (int)($r['file_type'] ?? 0);
        $key = (string)($r['file_key'] ?? '');
        $mime = (string)($r['mime_type'] ?? '');

        // در دیتابیس فقط file_key داریم (مثل images/..). مسیر کامل: storage/uploads/{file_key}
        $urlRel = 'storage/uploads/' . ltrim($key, '/');
        $url = base_url() . '/../' . $urlRel;

        $abs = BASE_PATH . '/' . $urlRel;
        $seenAbs[$abs] = true;

        $isImage = str_starts_with($mime, 'image/') || preg_match('/\.(png|jpe?g|gif|webp)$/i', $key);
        $isVideo = str_starts_with($mime, 'video/') || preg_match('/\.(mp4|mov|mkv|webm|3gp)$/i', $key);

        $preview = '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank">'
            . '<span class="badge bg-secondary">باز کردن</span></a>';
        if ($isImage) {
            $preview = '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank">'
                . '<img src="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" style="width:64px;height:64px;object-fit:cover;border-radius:8px" alt="media">'
                . '</a>';
        } elseif ($isVideo) {
            $preview = '<a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '" target="_blank">'
                . '<span class="badge bg-info">ویدئو</span></a>';
        }

        $uName = trim((string)($r['full_name'] ?? ''));
        $uLabel = $uName !== '' ? ($uName . ' (#' . (int)$r['user_id'] . ')') : ('#' . (int)$r['user_id']);

        $items[] = [
            'id' => $id,
            'preview_html' => $preview,
            'file_type_label' => mf_type_label($ft),
            'user_label' => $uLabel,
            'file_size_human' => mf_human_size((int)($r['file_size'] ?? 0)),
            'mime_type' => $mime !== '' ? $mime : '-',
            'file_key' => $urlRel,
            'created_at' => (string)($r['created_at'] ?? ''),
            'actions' => '<button type="button" class="btn btn-sm btn-outline-danger mf-del" data-id="' . $id . '">حذف</button>',
        ];
    }

    // اسکن پوشه uploads برای فایل‌هایی که در دیتابیس ثبت نشده‌اند
    if ($includeFs) {
        $root = BASE_PATH . '/storage/uploads';
        if (is_dir($root)) {
            $rii = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
            );

            foreach ($rii as $file) {
                /** @var SplFileInfo $file */
                if (!$file->isFile()) continue;

                $absPath = $file->getPathname();
                if (isset($seenAbs[$absPath])) continue;

                $rel = 'storage/uploads/' . ltrim(str_replace('\\', '/', substr($absPath, strlen($root))), '/');
                if ($rel === 'storage/uploads/') continue;

                // فیلتر user_id در حالت فایل سیستم: فعلاً فقط بر اساس نام فایل/پوشه حدس می‌زنیم
                if ($userId > 0) {
                    $bn = basename($absPath);
                    if (!str_contains($bn, 'user_' . $userId . '_') && !str_contains($bn, 'avatar_' . $userId . '_') && !str_contains($bn, 'verify_' . $userId . '_')) {
                        continue;
                    }
                }

                $mime2 = mf_guess_mime($absPath);
                $isImage = str_starts_with($mime2, 'image/') || preg_match('/\.(png|jpe?g|gif|webp)$/i', $rel);
                $isVideo = str_starts_with($mime2, 'video/') || preg_match('/\.(mp4|mov|mkv|webm|3gp)$/i', $rel);

                // فیلتر file_type در حالت فایل سیستم: mapping دقیق نداریم؛ فقط allow all اگر فیلتر نخورده
                if ($fileType > 0) {
                    // اگر ادمین فیلتر نوع زده، فایل‌های بدون رکورد را نمایش ندهیم چون نوع مشخص نیست.
                    continue;
                }

                $url2 = base_url() . '/../' . $rel;
                $preview2 = '<a href="' . htmlspecialchars($url2, ENT_QUOTES, 'UTF-8') . '" target="_blank">'
                    . '<span class="badge bg-secondary">باز کردن</span></a>';
                if ($isImage) {
                    $preview2 = '<a href="' . htmlspecialchars($url2, ENT_QUOTES, 'UTF-8') . '" target="_blank">'
                        . '<img src="' . htmlspecialchars($url2, ENT_QUOTES, 'UTF-8') . '" style="width:64px;height:64px;object-fit:cover;border-radius:8px" alt="media">'
                        . '</a>';
                } elseif ($isVideo) {
                    $preview2 = '<a href="' . htmlspecialchars($url2, ENT_QUOTES, 'UTF-8') . '" target="_blank">'
                        . '<span class="badge bg-info">ویدئو</span></a>';
                }

                $items[] = [
                    'id' => '-',
                    'preview_html' => $preview2,
                    'file_type_label' => '<span class="badge bg-warning">ثبت نشده</span>',
                    'user_label' => '- (filesystem)',
                    'file_size_human' => mf_human_size((int)$file->getSize()),
                    'mime_type' => $mime2,
                    'file_key' => $rel,
                    'created_at' => date('Y-m-d H:i:s', (int)$file->getMTime()),
                    'actions' => '<button type="button" class="btn btn-sm btn-outline-danger mf-del-fs" data-key="' . htmlspecialchars($rel, ENT_QUOTES, 'UTF-8') . '">حذف فایل</button>',
                ];
            }
        }
    }

    echo json_encode(['data' => $items], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['ok' => false, 'message' => 'شناسه نامعتبر'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $st = $pdo->prepare('SELECT file_key FROM user_files WHERE id=? LIMIT 1');
    $st->execute([$id]);
    $key = (string)($st->fetchColumn() ?: '');
    if ($key === '') {
        echo json_encode(['ok' => false, 'message' => 'یافت نشد'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $pdo->prepare('DELETE FROM user_files WHERE id=? LIMIT 1')->execute([$id]);

    $path = BASE_PATH . '/storage/uploads/' . $key;
    if (is_file($path)) {
        @unlink($path);
    }

    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($action === 'delete_fs') {
    $key = (string)($_POST['key'] ?? '');
    $key = mf_safe_key($key);
    if ($key === '' || !str_starts_with($key, 'storage/uploads/')) {
        echo json_encode(['ok' => false, 'message' => 'کلید نامعتبر'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $abs = BASE_PATH . '/' . $key;
    if (!is_file($abs)) {
        echo json_encode(['ok' => false, 'message' => 'فایل یافت نشد'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    @unlink($abs);
    echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(['ok' => false, 'message' => 'اکشن نامعتبر'], JSON_UNESCAPED_UNICODE);
