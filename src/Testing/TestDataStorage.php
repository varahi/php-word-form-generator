<?php

namespace App\Testing;

class TestDataStorage
{
    private static $testData = [];
    private static $testMode = false;

    public static function enableTestMode(): void
    {
        self::$testMode = true;
    }

    public static function disableTestMode(): void
    {
        self::$testMode = false;
    }

    public static function isTestMode(): bool
    {
        return self::$testMode;
    }

    // Теперь добавляем данные только по имени тестового кейса
    public static function addTestData(string $testCaseName, array $data): void
    {
        self::$testData[$testCaseName] = $data;
    }

    public static function getTestData(string $testCaseName): array
    {
        return self::$testData[$testCaseName] ?? [];
    }

    public static function getAvailableTestCases(): array
    {
        return array_keys(self::$testData);
    }

    public static function getTestDataByIndex(int $index): array
    {
        $testCases = self::getAvailableTestCases();
        if (isset($testCases[$index])) {
            return self::getTestData($testCases[$index]);
        }
        return [];
    }
}