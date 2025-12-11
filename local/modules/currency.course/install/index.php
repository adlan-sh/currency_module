<?php

use CurrencyCourse\CurrencyHelper;
use CurrencyCourse\CurrencyCourseTable;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;

define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt");

Loc::loadMessages(__FILE__);

class currency_course extends CModule
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
        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('CURRENCY_COURSE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('CURRENCY_COURSE_MODULE_DESCRIPTION');
        $this->PARTNER_NAME = Loc::getMessage('CURRENCY_COURSE_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('CURRENCY_COURSE_PARTNER_URI');
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallFiles();
        $this->InstallEvents();
    }

    public function DoUninstall()
    {
        $this->UnInstallEvents();
        $this->UnInstallFiles();
        $this->UnInstallDB();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallDB()
    {
        $connection = Application::getConnection();

        $tableName = 'b_currency_course';

        if (!$connection->isTableExists($tableName)) {
            $sql = "
                CREATE TABLE {$tableName} (
                    ID INT NOT NULL AUTO_INCREMENT,
                    CODE VARCHAR(10) NOT NULL,
                    DATE DATETIME NOT NULL,
                    COURSE DECIMAL(10,4) NOT NULL,
                    PRIMARY KEY (ID),
                    INDEX ix_{$tableName}_code (CODE),
                    INDEX ix_{$tableName}_date (DATE)
                )
            ";

            $connection->query($sql);
        }

        $this->registerAutoloadClasses();

        $this->InstallComponents();

        return true;
    }

    public function UnInstallDB()
    {
        $connection = Application::getConnection();
        $tableName = 'b_currency_course';

        if ($connection->isTableExists($tableName)) {
            $connection->dropTable($tableName);
        }

        $this->UnInstallComponents();

        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . '/../components',
            Application::getDocumentRoot() . '/local/components',
            true, true
        );

        return true;
    }

    public function UnInstallFiles()
    {
        DeleteDirFiles(
            __DIR__ . '/../components',
            Application::getDocumentRoot() . '/local/components'
        );

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

    private function registerAutoloadClasses()
    {
        Loader::registerAutoLoadClasses($this->MODULE_ID, [
            'CurrencyCourse\CurrencyCourseTable' => '/local/modules/currency.course/lib/currencycoursetable.php',
            'CurrencyCourse\CurrencyHelper' => '/local/modules/currency.course/lib/currencyhelper.php',
        ]);
    }

    private function InstallComponents()
    {
        $componentsSource = __DIR__ . '/../components';
        $componentsTarget = Application::getDocumentRoot() . '/local/components';

        if (!is_dir($componentsTarget . '/bitrix')) {
            if (!mkdir($concurrentDirectory = $componentsTarget . '/bitrix', 0755, true)
                && !is_dir($concurrentDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
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
        $componentsTarget = Application::getDocumentRoot() . '/local/components';

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
