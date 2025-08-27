<?php

namespace App\Factory;

use App\Services\DocumentGenerator;
use App\Requests\DocumentRequest;
use App\Services\DateFormatter;

class DocumentGeneratorFactory
{
    public static function createContractGenerator(array $postData): DocumentGenerator
    {
        $request = new DocumentRequest($postData);

        if(empty($postData['template'])) {
            header('Location: form.html');
            exit;
        }

        $generator = new DocumentGenerator('data/' . $postData['template'] . '.docx');

        [$day, $month, $year] = explode('.', $request->get('contract_date'));
        [$customer_birthdate_day, $customer_birthdate_month, $customer_birthdate_year] = explode('.', $request->get('customer_birthdate'));
        [$contract_duration_day, $contract_duration_month, $contract_duration_year] = explode('.', $request->get('contract_duration_date'));

        //self::debug($customer_birthdate_day);

        $generator->setValues([
            // Contract data
            'contract_number' => $request->get('contract_number'),
            'day' => $day,
            'month' => DateFormatter::russianMonth((int)$month),
            'year' => $year,

            'cdday' => $contract_duration_day,
            'cdmonth' => DateFormatter::russianMonth((int)$contract_duration_month),
            'cdyear' => $contract_duration_year,

            // Customer data
            'customer_fullname' => $request->get('customer_fullname'),
            'cday' => $customer_birthdate_day,
            'cmonth' => DateFormatter::russianMonth((int)$customer_birthdate_month),
            'cyear' => $customer_birthdate_year,
            'customer_passwport_number' => $request->get('customer_passwport_number'),
            'customer_passport_issued_by' => $request->get('customer_passport_issued_by'),
            'customer_registration_address' => $request->get('customer_registration_address'),
            'customer_phone' => $request->get('customer_phone'),
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
