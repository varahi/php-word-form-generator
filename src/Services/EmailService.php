<?php

namespace App\Services;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_SendmailTransport;
use App\Dotenv\EnvConfig;

class EmailService
{
    /** @var \Swift_Mailer */
    private $mailer;

    public function __construct()
    {
        $this->mailer = $this->createMailer();
    }

    private function createMailer()
    {
        $method = EnvConfig::getMailerMethod();

        switch ($method) {
            case 'smtp':
                return $this->createSmtpMailer();

            case 'sendmail':
                return $this->createSendmailMailer();

            default:
                throw new \Exception("Неизвестный метод отправки почты: {$method}");
        }
    }

    private function createSmtpMailer()
    {

        if (EnvConfig::get('APP_ENV') === 'dev') {
            $transport = (new Swift_SmtpTransport(
                EnvConfig::get('SMTP_HOST', '127.0.0.1'),
                EnvConfig::get('SMTP_PORT', 1025),
            ));
        } else {
            $transport = (new Swift_SmtpTransport(
                EnvConfig::get('SMTP_HOST', ''),
                EnvConfig::get('SMTP_PORT', ''),
                EnvConfig::get('SMTP_ENCRYPTION', 'tls')
            ))
                ->setUsername(EnvConfig::get('SMTP_USERNAME'))
                ->setPassword(EnvConfig::get('SMTP_PASSWORD'));
        }

        return new Swift_Mailer($transport);
    }

    private function createSendmailMailer()
    {
        $sendmailPath = EnvConfig::get('SENDMAIL_PATH', '/usr/sbin/sendmail');
        $transport = new Swift_SendmailTransport($sendmailPath . ' -bs');

        return new Swift_Mailer($transport);
    }

    public function sendDocumentWithContent(
        string $fileContent,
        string $fileName,
        string $subject = 'Новый документ',
        string $body = 'Во вложении сгенерированный документ'
    ): bool
    {
        try {
            $adminEmail = EnvConfig::getAdminEmail();

            if (empty($adminEmail)) {
                throw new \Exception('Email администратора не настроен');
            }

            // Создаем сообщение
            $message = (new Swift_Message($subject))
                ->setFrom([EnvConfig::get('SMTP_USERNAME', 'noreply@example.com') => 'Document Generator'])
                ->setTo([$adminEmail])
                ->setBody($body);


            // Добавляем вложение
            $attachment = new \Swift_Attachment(
                $fileContent,
                $fileName,
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            );

            $message->attach($attachment);

            // Отправляем
            $result = $this->mailer->send($message);

            return $result > 0;

        } catch (\Exception $e) {
            error_log('Email sending error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendDocumentWithAttachment(
        string $filePath,
        string $fileName,
        string $subject = 'Новый документ',
        string $body = 'Во вложении сгенерированный документ'
    ): bool
    {
        try {
            $adminEmail = EnvConfig::getAdminEmail();

            if (empty($adminEmail)) {
                throw new \Exception('Email администратора не настроен');
            }

            $message = (new Swift_Message($subject))
                ->setFrom([EnvConfig::get('SMTP_USERNAME', 'noreply@example.com') => 'Document Generator'])
                ->setTo([$adminEmail])
                ->setBody($body)
                ->attach(\Swift_Attachment::fromPath($filePath)->setFilename($fileName));

            $result = $this->mailer->send($message);

            return $result > 0;

        } catch (\Exception $e) {
            error_log('Email sending error: ' . $e->getMessage());
            return false;
        }
    }
}