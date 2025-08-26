<?php

namespace App\Requests;

class DocumentRequest
{
    private array $data;

    public function __construct(array $postData)
    {
        $this->data = $this->sanitizeData($postData);
    }

    private function sanitizeData(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
        }
        return $sanitized;
    }

    public function get(string $key, string $default = 'Не указано'): string
    {
        return $this->data[$key] ?? $default;
    }

    public function getAll(): array
    {
        return $this->data;
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
}
