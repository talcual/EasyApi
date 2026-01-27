<?php

namespace EasyApi\Core;

function runner()
{
    global $config;

    // ===== CORS =====
    if ($config['cors']) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authorization, Content-Type');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit(0);
        }
    }

    $headers = getallheaders();
    $method  = $_SERVER['REQUEST_METHOD'];

    // ===== Parse request =====
    if ($method === 'GET' && isset($_GET['qs'])) {
        $parts  = explode('/', trim($_GET['qs'], '/'));
        $action = $parts[0] ?? null;
        $params = $parts[1] ?? null;
    } else {
        $payload = json_decode(file_get_contents('php://input'), true);
        $action  = $payload['action'] ?? null;
        $params  = $payload['params'] ?? null;
    }

    if (!$action) {
        http_response_code(400);
        exit('Missing action');
    }

    // ===== JWT middleware =====
    if (
        $config['jwtenabled'] &&
        !in_array($action, ['login', 'register'])
    ) {
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            exit('Missing Authorization header');
        }

        if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $m)) {
            http_response_code(401);
            exit('Invalid Authorization format');
        }

        if (!jwtcheck($m[1])) {
            http_response_code(401);
            exit('Invalid token');
        }
    }

    invoke($params, $action);
}