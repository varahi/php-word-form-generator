<?php

namespace App\Factory;

use App\Services\DocumentGenerator;
use App\Requests\DocumentRequest;

class DocumentGeneratorFactory
{
    public static function createContractGenerator(array $postData): DocumentGenerator
    {
        $request = new DocumentRequest($postData);

        $generator = new DocumentGenerator('data/template.docx');

        [$day, $month, $year] = explode('.', $request->get('contract_date'));
        //self::debug($day);

        $generator->setValues([
            'contract_number' => $request->get('contract_number'),
            'day' => $request->get($day),
            'month' => $request->get($month),
            'year' => $request->get($year),
        ]);

        $generator->setOutputFilename('contract_' . $request->get('contract_number') . '.docx');

        return $generator;
    }

    private static function debug(string $get)
    {
        echo '<br>';
        print_r($get);
        echo '<br>';
        exit();
    }
}
