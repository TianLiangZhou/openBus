<?php

use App\Support\Env;

function env(string $key, $default = null)
{
    return Env::get($key, $default);
}
