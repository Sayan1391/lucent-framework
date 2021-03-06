<?php

namespace core\modules\guide\models;

use core\classes\SysLocale;
use core\classes\SysModel;

class Guide extends SysModel
{
    protected static $table = 'guide';

    protected static function labels()
    {
        return [
            'machine_name' => SysLocale::t("Machine name"),
            'switch' => SysLocale::t("Switch"),
        ];
    }

    public static function rules()
    {
        return [
            ['switch' => ['required', 'numeric']],
            ['machine_name' => ['required', 'unique', 'machine_name']],
        ];
    }
}