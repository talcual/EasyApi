<?php

$actions = [
    'login'    => 'api_login',
    'register' => 'api_register',
    'ping'     => 'api_ping',
    'user'     => 'api_user',
];

/**
 * Ejecuta una acción registrada
 */
function invoke($params, $action)
{
    global $actions;

    if (!isset($actions[$action])) {
        http_response_code(404);
        exit('Action not found');
    }

    $fn = $actions[$action];

    if (!is_callable($fn)) {
        http_response_code(500);
        exit('Action not callable');
    }

    $fn($params);
}

/**
 * Acciones
 */
function api_login($params)
{
    echo json_encode(['token' => 'jwt.fake.token']);
}

function api_register($params)
{
    echo 'register';
}

function api_ping()
{
    echo 'pong';
}

function api_user($params)
{
    echo 'user id: ' . $params;
}