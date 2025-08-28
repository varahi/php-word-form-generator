<?php

use App\Testing\TestDataStorage;

TestDataStorage::addTestData('test_case_1', [
    'contract_number' => '2808/2025',
    'contract_date' => '28.08.2025',
    'contract_start_date' => '01.09.2025',
    'contract_end_date' => '31.09.2025',
    'customer_fullname' => 'Иванов Иван Иванович',
    'customer_birthdate' => '11.07.1974',
    'customer_passwport_number' => '4510 123456',
    'customer_passport_issued_by' => 'ОУФМС России по г. Москве',
    'customer_registration_address' => 'г. Москва, ул. Ленина, д. 1, кв. 5',
    'customer_phone' => '+7 (999) 123-45-67',
    'customer_child_fullname' => 'Иванова Мария Ивановна',
    'customer_child_birthdate' => '13.06.1997'
]);

TestDataStorage::addTestData('test_case_2', [
    'contract_number' => 'ДГ-2023-002',
    'contract_date' => '20.07.2023',
    'contract_start_date' => '01.08.2023',
    'contract_end_date' => '31.12.2023',
    'customer_fullname' => 'Петрова Ольга Сергеевна',
    'customer_birthdate' => '22.11.1985',
    'customer_passwport_number' => '4511 654321',
    'customer_passport_issued_by' => 'ОУФМС России по г. Санкт-Петербургу',
    'customer_registration_address' => 'г. Санкт-Петербург, Невский пр-т, д. 100, кв. 10',
    'customer_phone' => '+7 (911) 987-65-43',
    'customer_child_fullname' => 'Петров Алексей Петрович',
    'customer_child_birthdate' => '05.06.2018'
]);

TestDataStorage::addTestData('test_case_3', [
    'contract_number' => 'СТ-2023-001',
    'contract_date' => '01.09.2023',
    'contract_start_date' => '01.09.2023',
    'contract_end_date' => '31.08.2024',
    'customer_fullname' => 'ООО "Ромашка"',
    'customer_birthdate' => '01.01.2000',
    'customer_passwport_number' => '',
    'customer_passport_issued_by' => '',
    'customer_registration_address' => 'г. Москва, ул. Цветочная, д. 1',
    'customer_phone' => '+7 (495) 123-45-67',
    'customer_child_fullname' => '',
    'customer_child_birthdate' => '01.01.2020'
]);