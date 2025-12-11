<?php

namespace CurrencyCourse;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\FloatField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\ORM\Fields\Validators;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Objectify\Collection;
use Bitrix\Main\ORM\Objectify\EntityObject;

Loc::loadMessages(__FILE__);

class CurrencyCourseTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_currency_course';
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
                'title' => Loc::getMessage('CURRENCY_COURSE_ID')
            ]),

            new StringField('CODE', [
                'required' => true,
                'validation' => function() {
                    return [
                        new Validators\LengthValidator(1, 10)
                    ];
                },
                'title' => Loc::getMessage('CURRENCY_COURSE_CODE')
            ]),

            new DatetimeField('DATE', [
                'required' => true,
                'title' => Loc::getMessage('CURRENCY_COURSE_DATE')
            ]),

            new FloatField('COURSE', [
                'required' => true,
                'title' => Loc::getMessage('CURRENCY_COURSE_COURSE')
            ]),
        ];
    }
}

class CurrencyCourse extends EntityObject
{
}

class CurrencyCourseCollection extends Collection
{
}
