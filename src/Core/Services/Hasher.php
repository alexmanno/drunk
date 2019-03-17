<?php

declare(strict_types=1);

namespace AlexManno\Drunk\Core\Services;

class Hasher
{
    /**
     * @param string $password
     *
     * @return string
     */
    public static function encrypt(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
