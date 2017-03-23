<?php

    namespace Core;

    class Route
    {
        public static $default;
        public static $actions = []; //later make private
        private static $options = [];
        private static $names = [];
        /**
         * find and return action for request if any
         * @param  \Core\Request $request [description]
         * @return \Core\Action           [description]
         */
        public static function getAction (Request $request)
        {
            foreach (self::$actions as $action)
            {
                if ($action->isActive($request))
                {
                    return $action;
                }
            }
            return false;
        }
        public static function getDefault()
        {
            return self::$default;
        }
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
            if ($action->getPath()=='/')
            {
                self::$default = $action;
            }
            return $action;
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
        public static function name ($name, $action)
        {
            self::$names[$name] = $action;
        }
        public static function url ($name, $args, $locale=null)
        {
            if (isset(self::$names[$name]))
            {
                if ($locale==null)
                {
                    $locale = App::getLocale();
                }
                $path = self::$names[$name]->fillPath($args);
                return dirname($_SERVER['SCRIPT_NAME']).'/'.$locale.'/'.$path;
            }
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
