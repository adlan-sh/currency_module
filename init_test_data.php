<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Добавление тестовых данных курсов валют</h2>";

if (!CModule::IncludeModule('currency.course')) {
    echo "<p style='color: red;'>Модуль currency.course не установлен!</p>";

    $modulePath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/currency.course/';
    require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');

    exit;
}

$libPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/currency.course/lib/';

$currencyTableFile = $libPath . 'currencycoursetable.php';
$currencyHelperFile = $libPath . 'currencyhelper.php';

require_once($currencyTableFile);
require_once($currencyHelperFile);

try {
        $testData = [
            ['USD', '2024-01-20 10:00:00', 75.1234],
            ['EUR', '2024-01-20 10:00:00', 89.5678],
            ['USD', '2024-01-19 10:00:00', 74.9876],
            ['EUR', '2024-01-19 10:00:00', 89.1234],
            ['GBP', '2024-01-20 10:00:00', 95.4321],
            ['USD', '2024-01-18 10:00:00', 74.8567],
            ['EUR', '2024-01-18 10:00:00', 88.9567],
        ];

        $addedCount = 0;
        $errorCount = 0;

        foreach ($testData as $data) {
            try {
                $result = CurrencyCourse\CurrencyHelper::addCourse($data[0], $data[1], $data[2]);

                if ($result->isSuccess()) {
                    echo "Добавлен курс: <b>{$data[0]}</b> на {$data[1]} = {$data[2]}<br>";
                    $addedCount++;
                } else {
                    $errors = $result->getErrorMessages();
                    echo "Ошибка при добавлении {$data[0]}: " . implode(', ', $errors) . "<br>";
                    $errorCount++;
                }
            } catch (Exception $e) {
                echo "Исключение для {$data[0]}: " . $e->getMessage() . "<br>";
                $errorCount++;
            }
        }

        echo "<hr><h3>Итог:</h3>";
        echo "Успешно добавлено: {$addedCount} записей<br>";
        echo "Ошибок: {$errorCount}<br>";

} catch (Exception $e) {
    echo "<p style='color: red;'>Ошибка: " . $e->getMessage() . "</p>";
    echo "<pre>Trace: " . $e->getTraceAsString() . "</pre>";
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
