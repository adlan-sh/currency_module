<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);
?>

<div class="currency-list">
    <?php if (!empty($arResult['ITEMS'])): ?>
        <div class="currency-list-table">
            <table class="currency-table">
                <thead>
                <tr>
                    <?php if (in_array('ID', $arParams['DISPLAY_FIELDS'], true)): ?>
                        <th>ID</th>
                    <?php endif; ?>
                    <?php if (in_array('CODE', $arParams['DISPLAY_FIELDS'], true)): ?>
                        <th>Код валюты</th>
                    <?php endif; ?>
                    <?php if (in_array('DATE', $arParams['DISPLAY_FIELDS'], true)): ?>
                        <th>Дата</th>
                    <?php endif; ?>
                    <?php if (in_array('COURSE', $arParams['DISPLAY_FIELDS'], true)): ?>
                        <th>Курс</th>
                    <?php endif; ?>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($arResult['ITEMS'] as $item): ?>
                    <tr>
                        <?php if (in_array('ID', $arParams['DISPLAY_FIELDS'], true)): ?>
                            <td><?= $item['ID'] ?></td>
                        <?php endif; ?>
                        <?php if (in_array('CODE', $arParams['DISPLAY_FIELDS'], true)): ?>
                            <td><?= htmlspecialcharsbx($item['CODE']) ?></td>
                        <?php endif; ?>
                        <?php if (in_array('DATE', $arParams['DISPLAY_FIELDS'], true)): ?>
                            <td><?= $item['DATE_FORMATTED'] ?></td>
                        <?php endif; ?>
                        <?php if (in_array('COURSE', $arParams['DISPLAY_FIELDS'], true)): ?>
                            <td><?= $item['COURSE_FORMATTED'] ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($arResult['NAV_OBJECT']->getPageCount() > 1): ?>
            <div class="currency-pagination">
                <?php
                $APPLICATION->IncludeComponent(
                    'bitrix:system.pagenavigation',
                    '',
                    [
                        'NAV_OBJECT' => $arResult['NAV_OBJECT'],
                        'SEF_MODE' => 'N',
                    ],
                    $component
                );
                ?>
            </div>
        <?php endif; ?>

        <div class="currency-total">
            Всего записей: <?= $arResult['TOTAL_COUNT'] ?>
        </div>
    <?php else: ?>
        <div class="currency-empty">
            Нет данных для отображения
        </div>
    <?php endif; ?>
</div>

<style>
    .currency-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    .currency-table th,
    .currency-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .currency-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .currency-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .currency-table tr:hover {
        background-color: #f5f5f5;
    }
    .currency-pagination {
        margin: 20px 0;
        text-align: center;
    }
    .currency-total {
        margin-top: 10px;
        font-style: italic;
        color: #666;
    }
    .currency-empty {
        padding: 20px;
        text-align: center;
        color: #999;
        font-style: italic;
    }
</style>