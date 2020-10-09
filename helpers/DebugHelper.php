<?php

namespace kozlovsv\crud\helpers;

use yii\helpers\VarDumper;

class DebugHelper
{
    /**
     * Debug function
     * d($var);
     * @param $var
     * @param null $caller
     */
    public static function d($var, $caller = null)
    {
        $cli = php_sapi_name() == 'cli';
        if (!isset($caller)) {
            $arr = debug_backtrace(1);
            $caller = array_shift($arr);
        }
        if (!$cli) {
            echo '<code>File: ' . $caller['file'] . ' / Line: ' . $caller['line'] . '</code>';
            echo '<pre>';
        }
        VarDumper::dump($var, 10, !$cli);
        if (!$cli) echo '</pre>';
    }

    /**
     * Debug function with die() after
     * dd($var);
     * @param $var
     */
    public static function dd($var)
    {
        $arr = debug_backtrace(1);
        $caller = array_shift($arr);
        static::d($var, $caller);
        die();
    }
}