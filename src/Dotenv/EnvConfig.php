<?php

namespace App\Dotenv;

use Dotenv\Dotenv;

class EnvConfig
{
    private static $initialized = false;
    private static $env = [];

    public static function init(): void
    {
        if (self::$initialized) {
            return;
        }

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        self::$env = $_ENV;
        self::$initialized = true;
    }

    public static function get(string $key, string $default = ''): string
    {
        self::init();
        return self::$env[$key] ?? $default;
    }

    public static function isTestMode(): bool
    {
        return self::get('TEST_MODE') === 'true';
    }

    public static function getDefaultTestCase(): string
    {
        return self::get('TEST_CASE', 'test_case_1');
    }

    public static function getMailerMethod(): string
    {
        return self::get('MAILER_METHOD', 'smtp');
    }

    public static function getAdminEmail(): string
    {
        return self::get('ADMIN_EMAIL');
    }
}