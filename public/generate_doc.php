<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/test_data.php'; // Загружаем тестовые данные

use PhpOffice\PhpWord\PhpWord;
use App\Factory\DocumentGeneratorFactory;
use App\Services\EmailService;
use App\Dotenv\EnvConfig;
use App\Utils\EmailValidator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phpWord = new PhpWord();

    try {
        if (!EmailValidator::validateAdminEmail()) {
            throw new Exception('Email администратора не настроен или невалиден. Проверьте .env файл');
        }

        $generator = DocumentGeneratorFactory::createContractGenerator($_POST);

        // Получаем содержимое файла и имя
        $fileContent = $generator->getFileContent();
        $fileName = $generator->getFileName();

        // Отправляем на email
        $emailService = new EmailService();

        $subject = 'Сгенерирован документ: ' . ($_POST['contract_number'] ?? 'Без номера');
        $body = "Документ был сгенерирован через форму.\n\n";
        $body .= "Данные формы:\n";

        foreach ($_POST as $key => $value) {
            if (!empty($value) && $key !== 'password') { // Исключаем чувствительные данные
                $body .= "- " . htmlspecialchars($key) . ": " . htmlspecialchars($value) . "\n";
            }
        }

        $success = $emailService->sendDocumentWithContent($fileContent, $fileName, $subject, $body);

        if ($success) {
            // Показываем страницу успеха
            echo "<h1>Документ успешно отправлен!</h1>";
            echo "<p>Файл <strong>{$fileName}</strong> был отправлен на email администратора.</p>";
            echo "<p><a href='index.html'>Сгенерировать еще один документ</a></p>";

            // Или редирект
            // header('Location: success.html');
            // exit;
        } else {
            throw new Exception('Не удалось отправить документ по email');
        }

        // Uncomment to download file
        //$generator->download();

    } catch (Exception $e) {
        http_response_code(500);
        die('Ошибка при создании документа: ' . $e->getMessage());
    }

} else {
    header('Location: index.html');
    exit;
}