<?php

    namespace Core;

    use \Core\Entry;
    use \Core\Method;

    class Route
    {

        private static $pathes;
        private static $entries = [];
        private static $options = [];
        public static function add ($path, $callback, $method=null, $options=[])
        {
            self::$pathes = null;
            if (!is_array($options))
            {
                $options = [];
            }
            if (is_array(self::$options) && count(self::$options)>0)
            {
                $options = $options+self::$options;
            }
            $entry = new Entry ($path, $callback, $method, $options);
            self::$entries[] = $entry;
            return $entry;
        }
        private static function build ()
        {
            if (!self::$entries || self::$pathes!==null) return;
            self::$pathes = [];
            foreach (self::$entries as $key => $entry)
            {
                self::$pathes[$key] = $entry->getRegex();
            }
        }
        public static function match ($route)
        {
            self::build();
            if (!is_array(self::$pathes) || !self::$pathes) return;
            $matches = [];
            foreach (self::$pathes as $key => $pattern)
            {
                $match = preg_match($pattern, $route);
                debug ($match,$pattern);
                if ($match===1)
                {
                    $matches[$key] = true;
                }
            }
            if (!$matches) return false;
            $result = [];
            foreach ($matches as $key => $value)
            {
                $result[$key] = self::$entries[$key];
            }
            return $result;
        }
        public static function get ($path, $callback, $options=[])
        {
            return self::add ($path, $callback, Method::GET, $options);
        }
        public static function post ($path, $callback, $options=[])
        {
            return self::add ($path, $callback, Method::POST, $options);
        }
        public static function put ($path, $callback, $options=[])
        {
            return self::add ($path, $callback, Method::POST, $options);
        }
        public static function delete ($path, $callback, $options=[])
        {
            return self::add ($path, $callback, Method::POST, $options);
        }
        /**
         * Available options
         * prefix
         * namespace
         * domain
         * access
         * filter
         * @param array $options
         * @param  \Closure $callback [description]
         * @return [type]             [description]
         */
        public static function group ($options, \Closure $callback)
        {

            self::$options = $options;
            $callback();
            self::$options = [];
        }
    }
