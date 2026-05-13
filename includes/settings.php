<?php
declare(strict_types=1);

/**
 * System settings (key/value) روی جدول system_settings.
 */

function settings_get(string $key, $default = null)
{
    $pdo = db();
    $st = $pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ? LIMIT 1");
    $st->execute([$key]);
    $row = $st->fetch();
    if (!$row) return $default;
    return $row['setting_value'];
}

function settings_get_many(array $keys): array
{
    $keys = array_values(array_filter($keys, fn($k) => is_string($k) && $k !== ''));
    if (count($keys) === 0) return [];

    $pdo = db();
    $in = implode(',', array_fill(0, count($keys), '?'));
    $st = $pdo->prepare("SELECT setting_key, setting_value FROM system_settings WHERE setting_key IN ($in)");
    $st->execute($keys);

    $out = [];
    foreach (($st->fetchAll() ?: []) as $r) {
        $out[(string)$r['setting_key']] = $r['setting_value'];
    }
    return $out;
}

function settings_set(string $key, ?string $value, ?int $updatedByUserId = null): void
{
    $pdo = db();
    $st = $pdo->prepare("
        INSERT INTO system_settings (setting_key, setting_value, updated_by_user_id, updated_at)
        VALUES (:k, :v, :u, NOW(3))
        ON DUPLICATE KEY UPDATE
            setting_value = VALUES(setting_value),
            updated_by_user_id = VALUES(updated_by_user_id),
            updated_at = NOW(3)
    ");
    $st->execute([
        ':k' => $key,
        ':v' => $value,
        ':u' => $updatedByUserId,
    ]);
}

/**
 * @throws Throwable
 */
function settings_set_many(array $items, ?int $updatedByUserId = null): void
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        foreach ($items as $k => $v) {
            if (!is_string($k) || $k === '') continue;
            $val = is_null($v) ? null : (string)$v;
            settings_set($k, $val, $updatedByUserId);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}
