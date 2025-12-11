<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

$libPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/currency.course/lib/';

$currencyTableFile = $libPath . 'currencycoursetable.php';
$currencyHelperFile = $libPath . 'currencyhelper.php';

require_once($currencyTableFile);
require_once($currencyHelperFile);

?>

    <div style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <h1>Курсы валют</h1>

        <div style="display: grid; grid-template-columns: 300px 1fr; gap: 30px; margin-top: 30px;">
            <div>
                <h2>Фильтр курсов</h2>
                <div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php
                    $APPLICATION->IncludeComponent(
                        'bitrix:currency.filter',
                        'template',
                        [
                            'FILTER_NAME' => 'arrCurrencyFilter',
                            'CURRENCY_CODES' => [],
                        ],
                        null,
                        ['HIDE_ICONS' => 'Y']
                    );
                    ?>
                </div>
            </div>

            <div>
                <h2>Список курсов валют</h2>

                <div style="background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <?php
                    $APPLICATION->IncludeComponent(
                        'bitrix:currency.list',
                        'template',
                        [
                            'PAGE_SIZE' => 10,
                            'DISPLAY_FIELDS' => ['ID', 'CODE', 'DATE', 'COURSE'],
                            'FILTER_NAME' => 'arrCurrencyFilter',
                            'SHOW_FILTER' => 'N',
                            'CACHE_TIME' => 3600,
                        ],
                        null,
                        ['HIDE_ICONS' => 'Y']
                    );
                    ?>
                </div>
            </div>
        </div>

    </div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
?>