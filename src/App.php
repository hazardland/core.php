<?php

    namespace Core;

    class App
    {
        private static $locales = [];
        private static $locale;
        private static $request;
        private static $action;
        /**
         * define new locale
         * @param string  $locale  locale code ex:en,ge,ru
         * @param boolean $default if not passed 1st defined locale is default
         */
        public static function addLocale ($code, $default=false)
        {
            self::$locales[$code] = $code;
            if ($default || self::$locale===null)
            {
                self::$locale = $code;
            }
        }
        /**
         * get current(active) app locale
         * @param  string $code if specified will return locale if exists
         * @return string locale
         */
        public static function getLocale ($code=null)
        {
            if ($code!==null)
            {
                return self::$locales[$code];
            }
            if (!isset($_SESSION['locale']) || !isset(self::$locales[$_SESSION['locale']]))
            {
                return self::$locale;
            }
            return self::$locales[$_SESSION['locale']];
        }
        public static function setLocale ($code)
        {
            if (!isset(self::$locales[$code]))
            {
                $_SESSION['locale'] = self::$locale;
            }
            else
            {
                $_SESSION['locale'] = $code;
            }
        }
        public static function isLocale ($code)
        {
            if (isset(self::$locales[$code]))
            {
                return true;
            }
            return false;
        }
        public static function isUser()
        {

        }
        public static function getUser()
        {

        }
        public static function getUserId ()
        {

        }
        public static function getUserLogin()
        {

        }
        public static function getUserName()
        {

        }
        public static function getUserEmail()
        {

        }
        public static function getUrl ($resource=null)
        {
            return dirname($_SERVER['SCRIPT_NAME']).(self::getLocale()!==null?'/'.self::getLocale():'').($resource!==null?'/'.$resource:'');
        }
        public static function url ($resource=null)
        {
            return self::getUrl($resource);
        }
        public static function getPath()
        {
            if (is_object(self::$action))
            {
                return self::$action->getPath();
            }
        }
        public static function run (Request $request)
        {
            self::$request = $request;
            self::$action = Route::getAction($request);
            //debug (self::$action);
            //debug ($_REQUEST);
            if (self::$action===false)
            {
                header("HTTP/1.0 404 Not Found");
                exit;
                self::$action = Route::getDefault();
            }
            if (self::$action)
            {
                self::$action->execute(self::$request);
            }
        }
    }
