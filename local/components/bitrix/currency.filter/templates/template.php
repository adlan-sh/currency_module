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

<div class="currency-filter-form">
    <form method="get" action="<?= $arResult['FORM_ACTION'] ?>" name="<?= $arResult['FILTER_NAME'] ?>_form">
        <input type="hidden" name="set_filter" value="Y">

        <div class="filter-row">
            <div class="filter-field">
                <label for="currency_code">Код валюты:</label>
                <select name="<?= $arResult['FILTER_NAME'] ?>[CODE]" id="currency_code">
                    <option value="">Все</option>
                    <?php foreach ($arResult['CURRENCY_CODES'] as $code): ?>
                        <option value="<?= htmlspecialcharsbx($code) ?>"
                            <?= ($arResult['CURRENT_FILTER']['CODE'] ?? '') == $code ? 'selected' : '' ?>>
                            <?= htmlspecialcharsbx($code) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="filter-row">
            <div class="filter-field">
                <label for="date_from">Дата с:</label>
                <input type="date"
                       name="<?= $arResult['FILTER_NAME'] ?>[DATE_FROM]"
                       id="date_from"
                       value="<?= htmlspecialcharsbx($arResult['CURRENT_FILTER']['DATE_FROM'] ?? '') ?>">
            </div>

            <div class="filter-field">
                <label for="date_to">Дата по:</label>
                <input type="date"
                       name="<?= $arResult['FILTER_NAME'] ?>[DATE_TO]"
                       id="date_to"
                       value="<?= htmlspecialcharsbx($arResult['CURRENT_FILTER']['DATE_TO'] ?? '') ?>">
            </div>
        </div>

        <div class="filter-row">
            <div class="filter-field">
                <label for="course_from">Курс от:</label>
                <input type="number"
                       step="0.0001"
                       name="<?= $arResult['FILTER_NAME'] ?>[COURSE_FROM]"
                       id="course_from"
                       value="<?= htmlspecialcharsbx($arResult['CURRENT_FILTER']['COURSE_FROM'] ?? '') ?>">
            </div>

            <div class="filter-field">
                <label for="course_to">Курс до:</label>
                <input type="number"
                       step="0.0001"
                       name="<?= $arResult['FILTER_NAME'] ?>[COURSE_TO]"
                       id="course_to"
                       value="<?= htmlspecialcharsbx($arResult['CURRENT_FILTER']['COURSE_TO'] ?? '') ?>">
            </div>
        </div>

        <div class="filter-buttons">
            <button type="submit" class="filter-submit">Фильтровать</button>
            <button type="button" class="filter-reset" onclick="resetFilter()">Сбросить</button>
        </div>
    </form>
</div>

<script>
    function resetFilter() {
        const form = document.forms['<?= $arResult['FILTER_NAME'] ?>_form'];
        const inputs = form.querySelectorAll('input, select');

        inputs.forEach(input => {
            if (input.type !== 'hidden' && input.name !== 'set_filter') {
                if (input.type === 'select-one') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            }
        });

        form.submit();
    }
</script>

<style>
    .currency-filter-form {
        background: #f5f5f5;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 15px;
    }
    .filter-field {
        flex: 1;
        min-width: 200px;
    }
    .filter-field label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }
    .filter-field input[type="text"],
    .filter-field input[type="date"],
    .filter-field input[type="number"],
    .filter-field select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 3px;
        box-sizing: border-box;
    }
    .filter-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
    .filter-submit,
    .filter-reset {
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 14px;
    }
    .filter-submit {
        background: #0066cc;
        color: white;
    }
    .filter-submit:hover {
        background: #0052a3;
    }
    .filter-reset {
        background: #666;
        color: white;
    }
    .filter-reset:hover {
        background: #555;
    }
</style>