<?php

namespace includes;
use includes\template;
use includes\i18n;


class system {
    public $oTpl = null;
    public $oDB = null;
    public $oLang = null;

    public static $oInstance = null;

    private function __construct()
    {
        if (!file_exists('./lang/cache')) {
            if (!is_writable('./lang')) {
                throw new \Exception('./lang not writable!');
            }
            mkdir('./lang/cache');
        }

        $this->oTpl = template::getInstance();
        $this->oLang = new i18n('lang/lang_{LANGUAGE}.ini', 'lang/cache', 'en');
        $this->oLang->setForcedLang(LANGUAGE);
        $this->oLang->init();
        $this->oDB = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_DATA, DB_USER, DB_PASS);
    }

    public static function getInstance()
    {
        if (self::$oInstance instanceof system) {
            return self::$oInstance;
        }
        return new system();
    }
}
