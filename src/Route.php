<?php

    namespace Core;

    use \Core\Entry;
    use \Core\Method;

    class Route
    {
        public static $routes;
        private static $option = [];
        public static function add ($path, $callback, $method=null, $option=[])
        {
            self::$routes[$path] = new Entry ($path, $callback, $method, $option+self::$option);
            return self::$routes[$path];
        }
        public static function get ($path, $callback)
        {
            return self::add ($path, $callback, Method::GET);
        }
        public static function post ($path, $callback)
        {
            return self::add ($path, $callback, Method::POST);
        }
        public static function put ($path, $callback)
        {
            return self::add ($path, $callback, Method::POST);
        }
        public static function delete ($path, $callback)
        {
            return self::add ($path, $callback, Method::POST);
        }
        public static function group ($option, \Closure $callback)
        {
            self::$option = $option;
            $callback();
            self::$option = [];
        }
    }
