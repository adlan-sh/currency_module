<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentParameters = [
    'PARAMETERS' => [
        'PAGE_SIZE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Количество элементов на странице',
            'TYPE' => 'STRING',
            'DEFAULT' => '20'
        ],
        'DISPLAY_FIELDS' => [
            'PARENT' => 'BASE',
            'NAME' => 'Отображаемые поля',
            'TYPE' => 'LIST',
            'MULTIPLE' => 'Y',
            'VALUES' => [
                'ID' => 'ID',
                'CODE' => 'Код валюты',
                'DATE' => 'Дата',
                'COURSE' => 'Курс'
            ],
            'DEFAULT' => ['ID', 'CODE', 'DATE', 'COURSE']
        ],
        'FILTER_NAME' => [
            'PARENT' => 'BASE',
            'NAME' => 'Имя массива с фильтром',
            'TYPE' => 'STRING',
            'DEFAULT' => 'arrFilter'
        ],
        'SHOW_FILTER' => [
            'PARENT' => 'BASE',
            'NAME' => 'Показывать форму фильтра',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N'
        ],
        'CACHE_TIME' => [
            'DEFAULT' => 3600
        ]
    ]
];
