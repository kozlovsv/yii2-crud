<?php

namespace kozlovsv\crud\helpers;

use yii\helpers\VarDumper;

class DebugHelper
{
    /**
     * Debug function
     * d($var);
     * @param $var
     * @param int $callerLevel
     */
    public static function d($var, $callerLevel = 1)
    {
        $cli = php_sapi_name() == 'cli';
        $caller = static::getCaller($callerLevel);
        if ($caller && !empty($caller['file']) && !empty($caller['line'])) {
            $s = $caller['file']  . ' / Line: ' . $caller['line'];
            if ($cli)
                $s .= "\r\n";
            else
                $s = '<code>File: ' . $s . '</code>';
            echo $s;
        }
        if (!$cli) echo '<pre>';
        VarDumper::dump($var, 10, !$cli);
        if (!$cli) echo '</pre>';
    }

    /**
     * Debug function with die() after
     * dd($var);
     * @param $var
     * @param int $callerLevel
     */
    public static function dd($var, $callerLevel = 1)
    {
        static::d($var, $callerLevel);
        die();
    }

    private static function getCaller($level = 1)
    {
        $arr = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $level);
        return count($arr) > $level - 1 ? $arr[$level - 1] : null;
    }
}