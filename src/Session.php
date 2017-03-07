<?php

    namespace Core;

    /**
     * If you want that session was initializzed only when
     * you want and not before it and not after it than use this class
     * if you call any method
     */
    class Session
    {
        private static $id;
        private static $name;
        private static $open = false;
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
            if (self::$open===false || $id!==null)
            {
                if (session_id()!=='' && session_id()!=self::$id)
                {
                    debug ('opening session with id '.self::$id);
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
                    self::$id = session_id();
                    self::$open = true;
                }
                else
                {
                    self::$open = false;
                }
            }
            return self::$open;
        }
        public static function close ()
        {
            //if (self::$open)
            //{
                debug ('closing session');
                session_write_close ();
                self::$open = false;
            //}
        }
        public static function destroy ()
        {
            if (self::$open)
            {
                setcookie (session_name(), null, -1, '/');
                session_unset ();
                session_destroy ();
                self::$open = false;
            }
        }
        /**
         * store item in session
         * @param string $key item key
         * @param mixed $value item
         * @param int $ttl time to live
         */
        public static function set($key, $value)
        {
            if (self::open())
            {
                $_SESSION[$key] = $value;
            }
        }
        /**
         * retrieve item from session
         * @param string $key item key
         * @param \Closure|null $callback if not found returns callback result
         * @return mixed result
         */
        public static function get($key)
        {
            if (self::open())
            {
                return $_SESSION[$key];
            }
        }
        public static function all ()
        {
            if (self::open())
            {
                return $_SESSION;
            }
        }
        /**
         * check if item exist by key
         * @param  string $key item key
         * @return bool exists result
         */
        public static function exists($key)
        {
            if (self::open())
            {
                return isset($_SESSION[$key]);
            }
            return false;
        }
        /**
         * remove single sessiond item by key
         * @param  string $key item key to remove
         * @return bool success
         */
        public static function remove($key)
        {
            if (self::open())
            {
                unset($_SESSION[$key]);
            }
            return false;
        }
        public function clean ()
        {
            if (self::open())
            {
                $_SESSION = array();
            }
            return false;
        }
    }
