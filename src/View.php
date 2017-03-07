<?php

    namespace Core;

    class View
    {
        private static $path;
        private static $extension;
        public static function setPath ($path, $extension='.php')
        {
            self::$path = $path;
            self::$extension = $extension;
        }
        public static function render ($name, $values=[])
        {
            if (self::$path===null)
            {
                trigger_error("[\\Core\\View] view path is not defined", E_USER_ERROR);
            }
            include self::$path.'/'.str_replace(['/','\\'],'',$name).self::$extension;
        }
    }
