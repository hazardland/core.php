<?php

    namespace Core;

    class Route
    {
        public static $actions = []; //later make private
        private static $options = [];
        public static function add ($path, $callback, $method=null, $options=[])
        {
            if (!is_array($options))
            {
                $options = [];
            }
            if (is_array(self::$options) && count(self::$options)>0)
            {
                $options = $options+self::$options;
            }
            $action = new Action ($path, $callback, $method, $options);
            self::$actions[] = $action;
            return $action;
        }
        public static function match ($route)
        {
            foreach (self::$actions as $action)
            {
                if ($action->match($route))
                {
                    return $action;
                }
            }
            return false;
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
