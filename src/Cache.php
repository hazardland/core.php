<?php

    namespace Core;

    class Cache
    {
        private static $driver;
        private static $prefix='';
        public static function init (\Core\Cache\Driver $driver)
        {
            self::$driver = $driver;
        }
        public static function setPrefix($prefix)
        {
            self::$prefix = $prefix;
        }
        public static function getPrefix()
        {
            return self::$prefix;
        }
        /**
         * store item in cache
         * @param string $key item key
         * @param mixed $value item
         * @param int $ttl time to live
         */
        public static function set($key, $value, $ttl=null)
        {
            //debug(self::$prefix.'.'.$key);exit;
            return self::$driver->set(self::$prefix.'.'.$key,$value,$ttl);
        }
        /**
         * retrieve item from cache
         * @param string $key item key
         * @param \Closure|null $callback if not found returns callback result
         * @return mixed result
         */
        public static function get($key)
        {
            //debug (self::$driver->get(self::$prefix.'.'.$key),self::$prefix.'.'.$key);exit;
            return self::$driver->get(self::$prefix.'.'.$key);
        }
        /**
         * check if item exist by key
         * @param  string $key item key
         * @return bool exists result
         */
        public static function exists($key)
        {
            return self::$driver->exists(self::$prefix.'.'.$key);
        }
        /**
         * remove single cached item by key
         * @param  string $key item key to remove
         * @return bool success
         */
        public static function remove($key)
        {
            return self::$driver->remove (self::$prefix.'.'.$key);
        }
        /**
         * clean all cached items
         * @return bool success
         */
        public static function clean()
        {
            return self::$driver->clean();
        }
    }
