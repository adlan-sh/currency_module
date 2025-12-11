<?php

defined('B_PROLOG_INCLUDED') || die();

class CurrencyCourse extends \CModule
{
    public $MODULE_ID = 'currency.course';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS = 'N';
    public $PARTNER_NAME;
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = [];
        include __DIR__ . '/install/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = 'Курсы валют';
        $this->MODULE_DESCRIPTION = 'Модуль для курса валют';
        $this->PARTNER_NAME = '';
        $this->PARTNER_URI = '';
    }

    public function DoInstall()
    {
        global $APPLICATION;

        \RegisterModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallEvents();

        $APPLICATION->IncludeAdminFile(
            'Установка модуля ' . $this->MODULE_ID,
            __DIR__ . '/step.php'
        );
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $APPLICATION->IncludeAdminFile(
            'Удаление модуля ' . $this->MODULE_ID,
            __DIR__ . '/unstep.php'
        );

        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();
        \UnRegisterModule($this->MODULE_ID);
    }

    public function InstallDB()
    {
        global $DB, $APPLICATION;

        $errors = null;

        if (!$DB->Query("SELECT 1 FROM b_currency_course LIMIT 1", true)) {
            $sql = $this->getInstallSQL();
            $DB->Query($sql);
        }

        $this->InstallComponents();

        if ($errors) {
            $APPLICATION->ThrowException(implode('<br>', $errors));
            return false;
        }

        return true;
    }

    public function UnInstallDB()
    {
        global $DB, $APPLICATION;

        $errors = null;

        $DB->Query("DROP TABLE IF EXISTS b_currency_course");

        $this->UnInstallComponents();

        if ($errors) {
            $APPLICATION->ThrowException(implode('<br>', $errors));
            return false;
        }

        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . '/components',
            $_SERVER['DOCUMENT_ROOT'] . '/local/components',
            true, true
        );

        CopyDirFiles(
            __DIR__ . '/admin',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin',
            true, true
        );

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(
            __DIR__ . '/admin',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin'
        );

        DeleteDirFilesEx('/local/components/bitrix/currency.list/');
        DeleteDirFilesEx('/local/components/bitrix/currency.filter/');

        return true;
    }

    public function InstallEvents()
    {
        return true;
    }

    public function UnInstallEvents()
    {
        return true;
    }

    private function getInstallSQL()
    {
        return "
            CREATE TABLE IF NOT EXISTS b_currency_course (
                ID INT NOT NULL AUTO_INCREMENT,
                CODE VARCHAR(10) NOT NULL,
                DATE DATETIME NOT NULL,
                COURSE DECIMAL(10,4) NOT NULL,
                PRIMARY KEY (ID),
                INDEX ix_currency_course_code (CODE),
                INDEX ix_currency_course_date (DATE)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
        ";
    }

    private function InstallComponents()
    {
        $componentsSource = __DIR__ . '/components';
        $componentsTarget = $_SERVER['DOCUMENT_ROOT'] . '/local/components';

        if (!is_dir($componentsTarget . '/bitrix')) {
            if (!mkdir($concurrentDirectory = $componentsTarget . '/bitrix', 0755, true)
                && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Каталог "%s" не создан', $concurrentDirectory));
            }
        }

        CopyDirFiles(
            $componentsSource . '/bitrix/currency.list',
            $componentsTarget . '/bitrix/currency.list',
            true, true
        );

        CopyDirFiles(
            $componentsSource . '/bitrix/currency.filter',
            $componentsTarget . '/bitrix/currency.filter',
            true, true
        );
    }

    private function UnInstallComponents()
    {
        $componentsTarget = $_SERVER['DOCUMENT_ROOT'] . '/local/components';

        DeleteDirFilesEx($componentsTarget . '/bitrix/currency.list');
        DeleteDirFilesEx($componentsTarget . '/bitrix/currency.filter');

        if (is_dir($componentsTarget . '/bitrix') && count(scandir($componentsTarget . '/bitrix')) === 2) {
            rmdir($componentsTarget . '/bitrix');
        }
        if (is_dir($componentsTarget) && count(scandir($componentsTarget)) === 2) {
            rmdir($componentsTarget);
        }
    }
}
