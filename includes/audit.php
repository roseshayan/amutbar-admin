<?php

declare(strict_types=1);

function audit_log(string $action, ?string $entityType = null, ?int $entityId = null, array $payload = []): void
{
    $pdo = db();
    $st = $pdo->prepare("
    INSERT INTO admin_audit_logs (actor_user_id, action, entity_type, entity_id, ip_address, user_agent, payload_json, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(3))
  ");

    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

    $st->execute([
        admin_id() ?? 0,
        $action,
        $entityType,
        $entityId,
        $ip,
        $ua,
        json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    ]);
}
