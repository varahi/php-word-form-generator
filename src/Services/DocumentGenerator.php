<?php

namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;
use Exception;

class DocumentGenerator
{
    private $templatePath;
    private $values;
    private $outputFilename;

    public function __construct(string $templatePath)
    {
        if (!file_exists($templatePath)) {
            throw new Exception("Template file not found: " . $templatePath);
        }

        $this->templatePath = $templatePath;
        $this->values = [];
        $this->outputFilename = 'document_' . date('Y-m-d') . '.docx';
    }

    public function setValue(string $key, string $value): self
    {
        $this->values[$key] = $value;
        return $this;
    }

    public function setValues(array $values): self
    {
        $this->values = array_merge($this->values, $values);
        return $this;
    }

    public function setOutputFilename(string $filename): self
    {
        $this->outputFilename = $filename;
        return $this;
    }

    public function generate(): string
    {
        $templateProcessor = new TemplateProcessor($this->templatePath);
        $templateProcessor->setValues($this->values);

        $tempFile = tempnam(sys_get_temp_dir(), 'word_template');
        $templateProcessor->saveAs($tempFile);

        return $tempFile;
    }

    public function download(): void
    {
        $tempFile = $this->generate();

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $this->outputFilename . '"');
        header('Content-Length: ' . filesize($tempFile));
        header('Cache-Control: max-age=0');
        header('Pragma: public');

        readfile($tempFile);
        unlink($tempFile);
        exit;
    }

    public function getFileContent(): string
    {
        $tempFile = $this->generate();
        $content = file_get_contents($tempFile);
        unlink($tempFile);

        return $content;
    }

    public function getFileName(): string
    {
        return $this->outputFilename;
    }

}
