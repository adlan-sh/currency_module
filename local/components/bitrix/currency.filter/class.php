<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use CurrencyCourse\CurrencyHelper;

class CurrencyFilterComponent extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams = parent::onPrepareComponentParams($arParams);

        $arParams['FILTER_NAME'] = $arParams['FILTER_NAME'] ?? 'arrFilter';
        $arParams['CURRENCY_CODES'] =  CurrencyHelper::getUniqueCurrencyCodes();

        return $arParams;
    }

    public function executeComponent()
    {
        try {
            $this->checkModules();
            $this->processForm();
            $this->includeComponentTemplate('template');
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

    private function checkModules()
    {
        if (!Loader::includeModule('currency.course')) {
            throw new \RuntimeException(Loc::getMessage('CURRENCY_FILTER_MODULE_NOT_INSTALLED'));
        }
    }

    private function processForm()
    {
        $this->arResult['FORM_ACTION'] = $this->request->getRequestUri();
        $this->arResult['FILTER_NAME'] = $this->arParams['FILTER_NAME'];
        $this->arResult['CURRENCY_CODES'] = $this->arParams['CURRENCY_CODES'];

        $this->arResult['CURRENT_FILTER'] = [];
        if (isset($GLOBALS[$this->arParams['FILTER_NAME']])) {
            $this->arResult['CURRENT_FILTER'] = $GLOBALS[$this->arParams['FILTER_NAME']];
        }
    }
}
