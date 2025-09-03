<?php

namespace App\Services;

use App\Dotenv\EnvConfig;

class PhpMailService
{
    /**
     * Отправка через PHP mail() function
     */
    public function sendViaPhpMail(
        string $to,
        string $subject,
        string $body,
        string $fileContent = null,
        string $fileName = null
    ): bool {
        $from = EnvConfig::get('SMTP_USERNAME', 'noreply@' . $_SERVER['SERVER_NAME']);

        $headers = "From: Document Generator <{$from}>\r\n";
        $headers .= "Reply-To: {$from}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";

        if ($fileContent) {
            // Multipart message with attachment
            $boundary = md5(time());
            $headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

            $message = "--{$boundary}\r\n";
            $message .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $message .= chunk_split(base64_encode($body)) . "\r\n";

            $message .= "--{$boundary}\r\n";
            $message .= "Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document; name=\"{$fileName}\"\r\n";
            $message .= "Content-Transfer-Encoding: base64\r\n";
            $message .= "Content-Disposition: attachment; filename=\"{$fileName}\"\r\n\r\n";
            $message .= chunk_split(base64_encode($fileContent)) . "\r\n";

            $message .= "--{$boundary}--";
        } else {
            // Simple text message
            $headers .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
            $headers .= "Content-Transfer-Encoding: base64\r\n";
            $message = chunk_split(base64_encode($body));
        }

        return mail($to, $subject, $message, $headers);
    }
}