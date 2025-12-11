<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use CurrencyCourse\CurrencyHelper;
use Bitrix\Main\Type\DateTime;

class CurrencyListComponent extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $arParams = parent::onPrepareComponentParams($arParams);

        $arParams['PAGE_SIZE'] = (int)($arParams['PAGE_SIZE'] ?? 20);
        $arParams['DISPLAY_FIELDS'] = is_array($arParams['DISPLAY_FIELDS']) ?
            $arParams['DISPLAY_FIELDS'] : ['ID', 'CODE', 'DATE', 'COURSE'];
        $arParams['FILTER_NAME'] = $arParams['FILTER_NAME'] ?? 'arrFilter';
        $arParams['SHOW_FILTER'] = $arParams['SHOW_FILTER'] ?? 'N';

        return $arParams;
    }

    public function executeComponent()
    {
        try {
            $this->checkModules();
            $this->processRequest();
            $this->getData();
            $this->includeComponentTemplate('template');
        } catch (Exception $e) {
            ShowError($e->getMessage());
        }
    }

    private function checkModules()
    {
        if (!Loader::includeModule('currency.course')) {
            throw new RuntimeException(Loc::getMessage('CURRENCY_LIST_MODULE_NOT_INSTALLED'));
        }
    }

    private function processRequest()
    {
        $filter = [];

        if ($this->arParams['FILTER_NAME'] && isset($GLOBALS[$this->arParams['FILTER_NAME']])) {
            $arrFilter = $GLOBALS[$this->arParams['FILTER_NAME']];

            if (!empty($arrFilter['CODE'])) {
                $filter['=CODE'] = $arrFilter['CODE'];
            }

            if (!empty($arrFilter['DATE_FROM'])) {
                $filter['>=DATE'] = new DateTime($arrFilter['DATE_FROM'] . ' 00:00:00', 'Y-m-d H:i:s');
            }

            if (!empty($arrFilter['DATE_TO'])) {
                $filter['<=DATE'] = new DateTime($arrFilter['DATE_TO'] . ' 23:59:59', 'Y-m-d H:i:s');
            }

            if (!empty($arrFilter['COURSE_FROM'])) {
                $filter['>=COURSE'] = (float)$arrFilter['COURSE_FROM'];
            }

            if (!empty($arrFilter['COURSE_TO'])) {
                $filter['<=COURSE'] = (float)$arrFilter['COURSE_TO'];
            }
        }

        $this->arResult['FILTER'] = $filter;
    }

    private function getData()
    {
        $select = $this->arParams['DISPLAY_FIELDS'];

        if (!in_array('ID', $select, true)) {
            $select[] = 'ID';
        }

        $navParams = [
            'page_size' => $this->arParams['PAGE_SIZE'],
            'current_page' => $this->request->get('PAGEN_1') ?: 1
        ];

        $result = CurrencyHelper::getListWithNav(
            $this->arResult['FILTER'],
            $select,
            ['DATE' => 'DESC', 'ID' => 'DESC'],
            $navParams
        );

        $this->arResult['ITEMS'] = $result['ITEMS'];
        $this->arResult['NAV_OBJECT'] = $result['NAV_OBJECT'];
        $this->arResult['TOTAL_COUNT'] = $result['TOTAL_COUNT'];

        $this->arResult['CURRENCY_CODES'] = CurrencyHelper::getUniqueCurrencyCodes();

        foreach ($this->arResult['ITEMS'] as &$item) {
            if (isset($item['DATE'])) {
                $item['DATE_FORMATTED'] = FormatDate('d.m.Y H:i:s', MakeTimeStamp($item['DATE']));
            }
            if (isset($item['COURSE'])) {
                $item['COURSE_FORMATTED'] = number_format($item['COURSE'], 4, '.', ' ');
            }
        }

        unset($item);
    }
}
