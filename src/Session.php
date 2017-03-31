<?php

    namespace Core;

    /**
     * If you want that session was initializzed only when
     * you want and not before it and not after it than use this class
     * if you call any method
     */
    class Session
    {
        private static $prefix = 'app';
        private static $id;
        private static $name;
        /**
         * set session name
         * if you want that your sessions were created under some name
         * call it above all session calls
         * calling this method will not start session
         * @param string $name session name
         */
        public static function setName ($name=null)
        {
            self::$name = $name;
        }
        /**
         * get session name
         * @return string returns session name if it was set previously
         */
        public static function getName ()
        {
            return self::$name;
        }
        /**
         * set id for upcoming session
         * if session will be open during script run
         * this id will be used
         * @param string $id [description]
         */
        public static function setId ($id)
        {
            self::$id = $id;
        }
        public static function getId ()
        {
            return self::$id;
        }
        /**
         * open session or open session by custom id
         * @param  string $id only needed when opening new session when older already open
         * @return bool session status
         */
        public static function open ($id=null)
        {
            if ($id!==null)
            {
                self::$id = $id;
            }
            if (session_status()==PHP_SESSION_NONE || (self::$id!==null && session_id()!=self::$id))
            {
                if (session_status()==PHP_SESSION_ACTIVE && session_id()!=self::$id)
                {
                    self::close();
                }
                if (self::$id!==null)
                {
                    session_id (self::$id);
                }
                if (self::$name!==null)
                {
                    session_name (self::$name);
                }
                if (session_start())
                {
                    return true;
                }
            }
            return false;
        }
        public static function close ()
        {
            session_write_close ();
        }
        public static function destroy ()
        {
            setcookie (session_name(), null, -1, '/');
            session_unset ();
            session_destroy ();
        }

        //Below are functions which are recommended to use in case of usage Session::setPrefix
        //Otherwise just use $_SESSION global variable which works great

        public static function setPrefix($prefix)
        {
            self::$prefix = $prefix;
        }
        public static function getPrefix()
        {
            return self::$prefix;
        }
        /**
         * store item in session
         * @param string $key item key
         * @param mixed $value item
         * @param int $ttl time to live
         */
        public static function set($key, $value)
        {
            $_SESSION[self::$prefix][$key] = $value;
        }
        /**
         * retrieve item from session
         * @param string $key item key
         * @param \Closure|null $callback if not found returns callback result
         * @return mixed result
         */
        public static function get($key, $default=null)
        {
            if (!isset($_SESSION[self::$prefix][$key]))
            {
                return $default;
            }
            return $_SESSION[self::$prefix][$key];
        }
       /**
         * check if item exist by key
         * @param  string $key item key
         * @return bool exists result
         */
        public static function exists($key)
        {
            return isset($_SESSION[self::$prefix][$key]);
        }
        /**
         * remove single sessiond item by key
         * @param  string $key item key to remove
         * @return bool success
         */
        public static function remove($key)
        {
            unset($_SESSION[self::$prefix][$key]);
        }
        public static function all ($prefix=null)
        {
            if ($prefix!==null)
            {
                if (is_array($_SESSION[self::$prefix]))
                {
                    $result = [];
                    foreach ($_SESSION[self::$prefix] as $key => &$value)
                    {
                        if (strpos($key,$prefix)===0)
                        {
                            $result[$key] = $value;
                        }
                    }
                    return $result;
                }
            }
            else
            {
                return $_SESSION[self::$prefix];
            }
        }
        public static function clean ($prefix=null)
        {
            if ($prefix!==null)
            {
                if (is_array($_SESSION[self::$prefix]))
                {
                    foreach ($_SESSION[self::$prefix] as $key => &$value)
                    {
                        if (strpos($key,$prefix)===0)
                        {
                            unset($_SESSION[self::$prefix][$key]);
                        }
                    }
                }
            }
            else
            {
                $_SESSION[self::$prefix] = array();
            }
        }
    }
