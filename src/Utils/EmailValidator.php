<?php

namespace App\Utils;

use App\Dotenv\EnvConfig;

class EmailValidator
{
    public static function validate(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validateAdminEmail(): bool
    {
        $email = EnvConfig::getAdminEmail();
        return !empty($email) && self::validate($email);
    }
}