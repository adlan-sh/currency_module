<?php

namespace CurrencyCourse;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\ObjectException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\UI\PageNavigation;

class CurrencyHelper
{
    /**
     * Получает курс валюты на определенную дату
     *
     * @param string $code Код валюты
     * @param string $date Дата
     * @return float|null Курс валюты
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getCourseByDate($code, $date)
    {
        $result = CurrencyCourseTable::getList([
            'filter' => [
                '=CODE' => $code,
                '>=DATE' => $date . ' 00:00:00',
                '<=DATE' => $date . ' 23:59:59'
            ],
            'limit' => 1,
            'order' => ['DATE' => 'DESC']
        ])->fetch();

        return $result ? (float)$result['COURSE'] : null;
    }

    /**
     * Добавляет новый курс валюты
     *
     * @param string $code Код валюты
     * @param string $date Дата
     * @param float $course Курс валюты
     * @throws ObjectException
     */
    public static function addCourse($code, $date, $course): AddResult
    {
        return CurrencyCourseTable::add([
            'CODE' => $code,
            'DATE' => new DateTime($date, 'Y-m-d H:i:s'),
            'COURSE' => $course
        ]);
    }

    /**
     * Получает список валют с постраничной навигацией
     *
     * @param array $filter Фильтр
     * @param array $select Выбираемые поля
     * @param array $order Сортировка
     * @param array $navParams Параметры навигации
     * @return array Результат с данными и навигацией
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getListWithNav($filter = [], $select = ['*'], $order = ['ID' => 'DESC'], $navParams = [])
    {
        $nav = new PageNavigation('currency-nav');

        if (isset($navParams['page_size'])) {
            $nav->setPageSize($navParams['page_size']);
        }

        if (isset($navParams['current_page'])) {
            $nav->setCurrentPage($navParams['current_page']);
        }

        $result = CurrencyCourseTable::getList([
            'select' => $select,
            'filter' => $filter,
            'order' => $order,
            'count_total' => true,
            'offset' => $nav->getOffset(),
            'limit' => $nav->getLimit()
        ]);

        $items = [];
        while ($item = $result->fetch()) {
            $items[] = $item;
        }

        $nav->setRecordCount($result->getCount());

        return [
            'ITEMS' => $items,
            'NAV_OBJECT' => $nav,
            'TOTAL_COUNT' => $result->getCount()
        ];
    }

    /**
     * Получает уникальные коды валют
     *
     * @return array Массив кодов валют
     * @throws SqlQueryException
     */
    public static function getUniqueCurrencyCodes()
    {
        $connection = Application::getConnection();
        $sql = "SELECT DISTINCT CODE FROM b_currency_course ORDER BY CODE";

        $result = $connection->query($sql);

        $codes = [];
        while ($row = $result->fetch()) {
            $codes[] = $row['CODE'];
        }

        return $codes;
    }
}
