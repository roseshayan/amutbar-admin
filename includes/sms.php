<?php

declare(strict_types=1);

function payamak_send_otp(string $to, string $code): array
{
    $username = (string)env('PAYAMAK_USERNAME', '');
    $apiKey   = (string)env('PAYAMAK_APIKEY', '');
    $bodyId   = (int)env('PAYAMAK_OTP_BODY_ID', 0);

    if ($username === '' || $apiKey === '' || $bodyId <= 0) {
        return ['ok' => false, 'message' => 'SMS config missing'];
    }

    // text: اگر الگوی شما فقط یک متغیر دارد، همین کد را بفرستید
    $payload = [
        'username' => $username,
        'password' => $apiKey,   // طبق مستند: ApiKey به جای رمز عبور
        'text'     => $code,
        'to'       => $to,
        'bodyId'   => $bodyId,
    ];

    $ch = curl_init('https://rest.payamak-panel.com/api/SendSMS/BaseServiceNumber');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_UNICODE),
        CURLOPT_TIMEOUT        => 15,
    ]);

    $resp = curl_exec($ch);
    $err  = curl_error($ch);
    $http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($resp === false) {
        return ['ok' => false, 'message' => 'cURL error: ' . $err];
    }

    $data = json_decode($resp, true);
    if (!is_array($data)) {
        return ['ok' => false, 'message' => 'Invalid SMS response', 'http' => $http, 'raw' => $resp];
    }

    // طبق مستند: در موفقیت RetStatus=1 و Value=recId، در خطا RetStatus=35 و Value=کد خطا
    if ((int)($data['RetStatus'] ?? 0) === 1) {
        return ['ok' => true, 'rec_id' => $data['Value'] ?? null, 'raw' => $data];
    }

    return ['ok' => false, 'code' => $data['Value'] ?? null, 'raw' => $data, 'message' => 'SMS failed'];
}
