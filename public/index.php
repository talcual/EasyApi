<?php

require __DIR__ . '/../vendor/autoload.php';

require_once '../src/Core/jwt.php';
require_once '../src/Bootstrap/db.php';
require_once '../src/Actions/actions.php';

use function EasyApi\Core\runner;

$config = [
    'cors'        => true,
    'jwtenabled'  => true,
];


runner();