<?php

use App\Support\Env;

/**
 * @param string $key
 * @param null $default
 * @return mixed
 */
function env(string $key, $default = null)
{
    return Env::get($key, $default);
}
