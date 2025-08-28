<?php

namespace App\Factory;

use App\Dotenv\EnvConfig;
use App\Services\DocumentGenerator;
use App\Requests\DocumentRequest;
use App\Services\DateFormatter;
use App\Testing\TestDataStorage;
use Symfony\Component\VarDumper\VarDumper;

class DocumentGeneratorFactory
{
    public static function createContractGenerator(array $postData): DocumentGenerator
    {
        EnvConfig::init(); // Инициализируем конфиг

        // Если включен тестовый режим и данные не переданы, используем тестовые
        if (EnvConfig::isTestMode()) {
            $testCaseName = EnvConfig::getDefaultTestCase();
            $testData = TestDataStorage::getTestData($testCaseName);

            if (!empty($testData)) {
                $postData = array_merge($postData, $testData);
            }
        }

        $request = new DocumentRequest($postData);

        if(empty($postData['template'])) {
            header('Location: form.html');
            exit;
        }

        $generator = new DocumentGenerator('data/' . $postData['template'] . '.docx');

        [$day, $month, $year] = explode('.', $request->get('contract_date'));
        //[$customer_birthdate_day, $customer_birthdate_month, $customer_birthdate_year] = explode('.', $request->get('customer_birthdate'));
        [$contract_start_day, $contract_start_month, $contract_start_year] = explode('.', $request->get('contract_start_date'));
        [$contract_end_day, $contract_end_month, $contract_end_year] = explode('.', $request->get('contract_end_date'));
        [$customer_child_birthdate_day, $customer_child_birthdate_month, $customer_child_birthdate_year] =
            explode('.', $request->get('customer_child_birthdate'));

        //self::debug($customer_birthdate_day);

        $generator->setValues([
            // Contract data
            'contract_number' => $request->get('contract_number'),
            'day' => $day,
            'month' => DateFormatter::russianMonth((int)$month),
            'year' => $year,

            // Contract start date
            'csday' => $contract_start_day,
            'csmonth' => DateFormatter::russianMonth((int)$contract_start_month),
            'csyear' => $contract_start_year,

            // Contract end date
            'ceday' => $contract_end_day,
            'cemonth' => DateFormatter::russianMonth((int)$contract_end_month),
            'ceyear' => $contract_end_year,

            // Customer data
            'customer_fullname' => $request->get('customer_fullname'),
            //'cbday' => $customer_birthdate_day,
            //'cbmonth' => DateFormatter::russianMonth((int)$customer_birthdate_month),
            //'cbyear' => $customer_birthdate_year,
            //'customer_phone' => $request->get('customer_phone'),

            'customer_passwport_number' => $request->get('customer_passwport_number'),
            'customer_passport_issued_by' => $request->get('customer_passport_issued_by'),
            'customer_registration_address' => $request->get('customer_registration_address'),
            'customer_phone' => !empty($request->get('customer_phone')) ? trim($request->get('customer_phone')) : '',

            // Customer child data
            'customer_child_fullname' => $request->get('customer_child_fullname'),
            'cchday' => $customer_child_birthdate_day,
            'cchmonth' => DateFormatter::russianMonth((int)$customer_child_birthdate_month),
            'cchyear' => $customer_child_birthdate_year,
        ]);

        $generator->setOutputFilename('contract_' . $request->get('template') .'_'. $request->get('contract_number') . '.docx');

        return $generator;
    }

    private static function debug(string $get)
    {
        echo '<br>';
        print_r($get);
        echo '<br>';
        exit();
    }

    private static function arrDebug(array $get)
    {
        echo '<pre>';
        VarDumper::dump($get);
        echo '</pre>';
        exit();
        //file_put_contents('debug.log', print_r($get, true), FILE_APPEND);
    }
}
