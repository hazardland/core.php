<?php

    namespace Core;

    class App
    {
        private static $locales = [];
        private static $locale;
        private static $request;
        private static $action;
        /**
         * Define new locale
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
         * Get current(active) app locale
         * @param  string $code if specified will return locale if exists
         * @return string locale
         */
        public static function getLocale ()
        {
            if (!isset($_SESSION['locale']) || !isset(self::$locales[$_SESSION['locale']]))
            {
                return self::$locale;
            }
            return self::$locales[$_SESSION['locale']];
        }
        /**
         * Check if given locale is App's current active locale
         * @return boolean [description]
         */
        public static function isLocale ($locale)
        {
            return (self::getLocale()==$locale);
        }
        /**
         * Return apps active locale or check if Apps active locale==$check
         * Usage1: if (App::locale()=='en') App locale is 'en'
         * Usage2: if (App::locale('en')) App locale is 'en'
         * @param  string $check locale to check
         * @return mixed bool if $check passed, locale code string if $check not passed
         */
        public static function locale ($check=null)
        {
            if ($check!==null)
            {
                return self::isLocale($check);
            }
            return self::getLocale();
        }
        /**
         * Set app current locale
         * @param string $code locale code to set
         */
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
        /**
         * Check if such locale exists
         * @param  string  $code locale code to check for
         * @return boolean true if exists false if not
         */
        public static function validLocale ($code)
        {
            if (isset(self::$locales[$code]))
            {
                return true;
            }
            return false;
        }
        public static function isUser()
        {
            if (Auth::check)
            {
                return true;
            }
            return false;
        }
        public static function getUser()
        {
            return Auth::user();
        }
        public static function getUserId ()
        {
            Auth::id();
        }
        // public static function getUrl ($resource=null)
        // {

        // }
        /**
         * Generate url for app resource
         *
         * In basic case App::url('js/jquery.js') will return '/js/jquery.js'
         *
         * But if your app is located on server sample.com/my/app than
         * App::url('js/jquery.js') will return '/my/app/js/jquery.js'
         *
         * So basically this function protects breaking your app if you move its code
         * from server to server from folder to folder
         *
         * @param  string $resource resource name
         * @return string url
         */
        public static function url ($resource=null)
        {
            return dirname($_SERVER['SCRIPT_NAME']).($resource!==null?'/'.$resource:'');
        }
        public static function header ($code)
        {
            if ($code==404)
            {
                header("HTTP/1.0 404 Not Found");
            }
        }
        public static function redirect ($resource)
        {
            header ('Location: '.self::url($resource));
            exit;
        }
        public static function getPath()
        {
            if (is_object(self::$action))
            {
                return self::$action->getPath();
            }
        }
/*        public function path ()
        {
            return self::getPath();
        }
*/        public static function run (Request $request)
        {
            self::$request = $request;
            self::$action = Route::getAction($request);
            //debug (self::$action);
            //debug ($_REQUEST);
            if (self::$action===false)
            {
                self::header (404);
                View::render('error/404',['request'=>$request]);
                exit;
                self::$action = Route::getDefault();
            }
            if (self::$action)
            {
                self::$action->execute(self::$request);
            }
        }
    }
