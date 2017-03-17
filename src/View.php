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
        public static function render ($__name, $__values=[])
        {
            if (self::$path===null)
            {
                trigger_error("[\\Core\\View] view path is not defined", E_USER_ERROR);
            }
            if (is_array($__values) && count($__values))
            {
                foreach ($__values as $__key => &$__value)
                {
                    ${$__key} = $__value;
                }
            }
            include self::$path.'/'.str_replace(['/','\\'],'',$__name).self::$extension;
        }
    }
