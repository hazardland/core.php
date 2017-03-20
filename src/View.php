<?php

    namespace Core;

    class View
    {
        private static $globals = [];
        /**
         * Render view
         * @param  [type] $__name   [description]
         * @param  array  $__values [description]
         * @return [type]           [description]
         */
        public static function render ($__name, $__values=[])
        {
            if (is_array($__values) && count($__values))
            {
                foreach ($__values as $__key => &$__value)
                {
                    ${$__key} = $__value;
                }
            }
            include APP.'/views/'.str_replace('.','',$__name).'.php';
        }
        /**
         * Set global variable
         * @param string $key key
         * @param string $value value
         */
        public static function set ($key, $value)
        {
            self::$globals[$key] = $value;
        }
        /**
         * Get global variable
         * @param  string $key key
         * @return mixed value
         */
        public static function get ($key)
        {
            return self::$globals[$key];
        }
    }
