<?php

$config = [
    'redis' => [
        'addr'     => getenv('REDIS_ADDR') ?: 'redis',
        'port'     => getenv('REDIS_PORT') ?: 6379,
        'password' => getenv('REDIS_PASSWORD') ?: null,
    ],
    'mysql' => [
        'url'      => getenv('MYSQL_URL') ?: 'mysql',
        'port'     => getenv('MYSQL_PORT') ?: 3306,
        'database' => getenv('MYSQL_DATABASE') ?: 3306,
        'user'     => getenv('MYSQL_USER') ?: null,
        'password' => getenv('MYSQL_PASSWORD') ?: null,
    ],
];

function getRedisConnection(array $config): Redis
{
    $redis = new Redis();

    $redisAddr = $config['addr'] ?? 'redis';
    $redisPort = $config['port'] ?? 6379;
    $redisPass = $config['password'] ?? '';

    $redis->connect($redisAddr, $redisPort, 2.5);

    if (! empty($redisPass)) {
        $redis->auth($redisPass);
    }

    return $redis;
}

function sendJSONResponse(int $statusCode, $data): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/v1/greeting':
        $clientIp  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $uniqueId  = md5($clientIp . $userAgent);

        // Temporary handler for catching warnings as exceptions
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            $redis = getRedisConnection($config['redis']);
        } catch (Exception $e) {
            restore_error_handler();
            error_log("Redis connection error on /v1/greeting: " . $e->getMessage());
            sendJSONResponse(503, ['error' => 'Internal Server Error']);
            exit();
        }

        restore_error_handler();

        try {
            $redis->sAdd('unique_visits', $uniqueId);
            $uniqueVisitsCount = $redis->sCard('unique_visits');
        } catch (Exception $e) {
            error_log("Redis command error on /v1/greeting: " . $e->getMessage());
            sendJSONResponse(503, ['error' => 'Internal Server Error']);
            exit();
        }

        sendJSONResponse(200, [
            'message'       => 'Hello World',
            'unique_visits' => $uniqueVisitsCount,
        ]);
        break;

    case '/healthz/live':
        sendJSONResponse(200, ['status' => 'Alive']);
        break;

    case '/healthz/ready':
        try {
            $redis = getRedisConnection($config['redis']);
            $redis->ping();
            sendJSONResponse(200, ['status' => 'Ready']);
        } catch (Exception $e) {
            error_log("Redis health check error: " . $e->getMessage());
            sendJSONResponse(503, ['error' => 'Internal Server Error']);
        }

        // TODO: Add readiness check for mysql conn
        break;

    default:
        http_response_code(404);
        break;
}
