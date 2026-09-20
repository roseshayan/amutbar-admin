<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__ . '/../includes/ExternalApiHelper.php';
require_once __DIR__ . '/../includes/video_challenge.php';
require_once __DIR__ . '/../includes/IdentityVerificationRunner.php';

final class FixtureApiIrClient extends ExternalApiHelper
{
    public array $requests = [];
    public array $response = ['status' => 200, 'errno' => 0, 'body' => '{"success":true,"code":0,"data":true}'];
    public function getActiveCredential($providerId, $env = 3) {
        return ['id' => null, 'api_key' => 'fixture-secret', 'bearer_token' => null];
    }
    protected function sendRequest(string $url, string $method, array $data, array $headers, int $timeout): array {
        $this->requests[] = compact('url', 'method', 'data', 'headers', 'timeout');
        return $this->response;
    }
}

function expectServiceError(callable $operation, string $code, ?int $status = null): VerificationServiceException
{
    try { $operation(); } catch (VerificationServiceException $e) {
        check($e->errorCode === $code, "Expected $code, got {$e->errorCode}");
        if ($status !== null) check($e->httpStatus === $status, 'Mapped HTTP status');
        return $e;
    }
    throw new RuntimeException("Expected failure: $code");
}

$afterSharedAccountChecks = function (): void {
    $start = $GLOBALS['checks'];
    $pdo = db();
    $pdo->exec("INSERT INTO external_api_providers (name, slug, base_url, auth_type, timeout_ms) VALUES ('Fixture', 'api_ir', 'https://old.example.invalid', 1, 8000)");
    $client = new FixtureApiIrClient($pdo);
    $input = ['nationalCode' => '0010007700', 'mobile' => '09120000000'];
    $result = $client->callExternalApi('api_ir', '/api/sw1/ShahkarLite', 'POST', $input);
    check($result['success'] && $result['data'] === true, 'Official boolean envelope');
    $request = $client->requests[0];
    check($request['url'] === 'https://s.api.ir/api/sw1/ShahkarLite', 'Current fixed provider origin');
    check(in_array('Authorization: Bearer fixture-secret', $request['headers'], true), 'API key is sent as Bearer');
    check($request['timeout'] >= 30000, 'Light-service timeout floor');
    $client->response['body'] = '{"success":true,"code":0,"data":false}';
    check($client->callExternalApi('api_ir', '/api/sw1/ShahkarLite', 'POST', $input)['data'] === false, 'Mismatch is a valid business result');
    $client->response['body'] = '{"success":true,"data":{"nationalCode":"0010007700","alive":true}}';
    $client->callExternalApi('api_ir', '/api/sw1/PersonImage', 'POST', ['nationalCode' => '0010007700', 'birthDate' => '1370/01/02', 'serialNumber' => 'ignored']);
    $request = end($client->requests);
    check($request['url'] === 'https://s.api.ir/api/sw1/PersonData', 'Saved legacy service upgraded');
    check($request['data'] === ['nationalCode' => '0010007700', 'birthDate' => '1370/1/2'], 'PersonData schema has no serial');
    check($request['timeout'] >= 60000, 'Image-service timeout floor');
    $client->response['body'] = '{"success":false,"code":401,"message":"secret provider detail"}';
    $failure = expectServiceError(fn() => $client->callExternalApi('api_ir', '/api/sw1/ShahkarLite', 'POST', $input), 'provider_credentials_error', 503);
    check(strlen($failure->requestId ?? '') === 32 && !str_contains($failure->getMessage(), 'secret'), 'Safe message and support reference');
    check(count($client->requests) === 4, 'Failure was not automatically retried');
    $log = $pdo->query('SELECT * FROM external_api_request_logs ORDER BY id DESC LIMIT 1')->fetch();
    check($log['request_id'] === $failure->requestId && $log['error_code'] === 'provider_credentials_error', 'Correlated structured log');
    check(!str_contains($log['request_redacted_json'], '0010007700') && !str_contains($log['response_redacted_json'], 'secret'), 'No identity or provider secrets in logs');
    foreach ([
        [429, '{}', 0, 'provider_rate_limited', 503],
        [500, '<html>failure</html>', 0, 'provider_unavailable', 503],
        [200, '<html>failure</html>', 0, 'provider_invalid_response', 502],
        [200, 'null', 0, 'provider_invalid_response', 502],
        [200, '{"success":"true"}', 0, 'provider_invalid_response', 502],
        [200, '{"success":false,"code":429}', 0, 'provider_rejected', 502],
        [0, '', CURLE_OPERATION_TIMEDOUT, 'provider_timeout', 504],
        [0, '', CURLE_SSL_CACERT, 'provider_tls_error', 503],
        [0, '', CURLE_COULDNT_RESOLVE_HOST, 'provider_unavailable', 503],
    ] as [$status, $body, $errno, $code, $mappedStatus]) {
        expectServiceError(fn() => ApiIrContract::decode($status, $body, $errno), $code, $mappedStatus);
    }
    expectServiceError(fn() => ApiIrContract::validateResponse('/api/sw1/ShahkarLite', ['data' => 'true']), 'provider_invalid_response');
    expectServiceError(fn() => ApiIrContract::validateResponse('/api/sw1/PersonData', ['data' => ['alive' => false]]), 'identity_not_verified');
    $video = ['nationalCode' => '0010007700', 'birthDate' => '1370/01/02', 'serialNumber' => 'ABCDE123', 'speechText' => 'متن آزمایشی برای ضبط ویدئو', 'videoBase64' => base64_encode('fixture video')];
    check(ApiIrContract::prepare('/api/sw1/VideoVerify', $video)['timeout_ms'] === 120000, 'Video processing allows 120 seconds');
    expectServiceError(fn() => ApiIrContract::prepare('/api/sw1/VideoVerify', array_replace($video, ['serialNumber' => ''])), 'verification_input_missing');
    expectServiceError(fn() => ApiIrContract::prepare('/api/sw1/VideoVerify', array_replace($video, ['videoBase64' => base64_encode(str_repeat('a', ApiIrContract::MAX_VIDEO_BYTES + 1))])), 'video_too_large');
    $falseResult = ['success' => true, 'data' => ['isPassed' => false, 'isMatch' => true, 'isLiveness' => true, 'isSpeechMatched' => true]];
    check(!verification_video_passed($falseResult), 'Final provider rejection cannot pass even when subscores pass');

    $migration = file_get_contents(__DIR__ . '/../database/migrations/2026_09_20_000007_video_verification_challenges.sql');
    $pdo->exec($migration); $pdo->exec($migration);
    $user = $pdo->query("SELECT * FROM users WHERE phone='09120000001'")->fetch();
    $user['user_type'] = 1;
    $client->response['body'] = json_encode(['success' => true, 'data' => 'متن تصادفی آزمایشی برای خواندن']);
    $challenge = verification_create_challenge($user, 'ABCDE123', $client);
    check(end($client->requests)['data'] === [], 'Speech request has no invented parameters');
    $otherApp = $user; $otherApp['user_type'] = 2;
    expectServiceError(fn() => verification_consume_challenge($otherApp, $challenge['challenge_token']), 'video_challenge_expired');
    check(verification_consume_challenge($user, $challenge['challenge_token']) === $challenge['speech_text'], 'Bound speech is retrieved from server');
    expectServiceError(fn() => verification_consume_challenge($user, $challenge['challenge_token']), 'video_challenge_expired');
    expectServiceError(fn() => verification_create_challenge($user, '', $client), 'verification_rate_limited');
    $legacy = ['provider_slug' => 'api_ir', 'code' => 'PersonImage', 'title' => 'old', 'endpoint_path' => 'api/sw1/PersonImage', 'config_json' => '{"required":["serialNumber"]}'];
    $current = IdentityVerificationRunner::currentService($legacy);
    check($current['code'] === 'PersonData' && $current['endpoint_path'] === '/api/sw1/PersonData', 'Panel displays current service');
    check(json_decode($current['config_json'], true)['required'] === ['nationalCode', 'birthDate'], 'Panel drops obsolete serial requirement');
    foreach (['Shahkar2', 'VideoMatch'] as $obsolete) {
        check(!IdentityVerificationRunner::currentService(array_replace($legacy, ['endpoint_path' => '/api/sw1/' . $obsolete]))['available'], 'Obsolete panel service disabled');
    }
    check(ApiIrContract::prepare('api/sw1/ShahkarLite', $input)['endpoint'] === '/api/sw1/ShahkarLite', 'Legacy missing leading slash normalized');
    $providerId = (int)$pdo->query("SELECT id FROM external_api_providers WHERE slug='api_ir'")->fetchColumn();
    $pdo->prepare("INSERT INTO identity_verification_services (code,title,provider_id,endpoint_path,subject_kind) VALUES ('ShahkarLite','Test',?,'api/sw1/ShahkarLite',1)")->execute([$providerId]);
    $sid = (int)$pdo->lastInsertId();
    $pdo->prepare('UPDATE users SET code_meli=?,birth_date=? WHERE id=?')->execute(['0010007700','1370/1/2',$user['id']]);
    $client->response['body'] = '{"success":true,"data":false}';
    $runner = new IdentityVerificationRunner($pdo, $client);
    $run = $runner->runServiceForUser($sid, (int)$user['id'], null, [], 1);
    check($run['ok'] === true && $run['verified'] === false, 'Panel distinguishes identity mismatch from successful HTTP request');
    check((int)$pdo->query('SELECT status FROM identity_verification_jobs WHERE id=' . (int)$run['job_id'])->fetchColumn() === 4, 'Rejected identity job is not marked verified');
    $before = count($client->requests);
    $run = $runner->runServiceForUser($sid, (int)$user['id'], null, [], 2);
    check(!$run['ok'] && count($client->requests) === $before, 'Wrong-role service never calls provider');
    echo 'PASS: ' . ($GLOBALS['checks'] - $start) . " api.ir contract and error-handling checks (no external calls)\n";
};
require __DIR__ . '/shared_app_accounts.php';
