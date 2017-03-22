<?php

    namespace Core;

    class App
    {
        private static $locale;
        private static $locales = [];
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
        public static function run ($query=null)
        {
            if ($query!==null)
            {
                if (strpos($query,'/')!==false)
                {
                    $locale = substr($query, 0, strpos($query,'/'));
                    if (self::isLocale($locale))
                    {
                        self::setLocale($locale);
                        $query = substr($query,strpos($query,'/')+1);
                    }
                }
                else
                {
                    $locale = $query;
                    if (self::isLocale($locale))
                    {
                        self::setLocale($locale);
                        $query = '/';
                    }
                }
            }
            if ($query===null || $query=='')
            {
                $query = '/';
            }
            debug ($locale,'locale');
            debug ($query,'query');
            $action = Route::match ($query);
            debug ($action, "action");
            if ($action)
            {
                debug ($action->input($query), "input");
            }
        }
    }
