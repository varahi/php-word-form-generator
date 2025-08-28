<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/test_data.php'; // Загружаем тестовые данные

use PhpOffice\PhpWord\PhpWord;
use App\Factory\DocumentGeneratorFactory;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phpWord = new PhpWord();

    try {
        $generator = DocumentGeneratorFactory::createContractGenerator($_POST);
        $generator->download();

    } catch (Exception $e) {
        http_response_code(500);
        die('Ошибка при создании документа: ' . $e->getMessage());
    }

} else {
    header('Location: form.html');
    exit;
}