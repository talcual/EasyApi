<?php

namespace EasyApi\Core;

function jwtcheck($token)
{
    // aquí validarías firma, expiración, etc.
    return $token === 'jwt.fake.token';
}