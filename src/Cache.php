<?php

    namespace Core;

    class Cache
    {
        private static $driver;
        public static function init (\Core\Cache\Driver $driver)
        {
            self::$driver = $driver;
        }
        /**
         * store item in cache
         * @param string $key item key
         * @param mixed $value item
         * @param int $ttl time to live
         */
        public static function set($key, $value, $ttl=null)
        {
            return self::$driver->set($key,$value,$ttl);
        }
        /**
         * retrieve item from cache
         * @param string $key item key
         * @param \Closure|null $callback if not found returns callback result
         * @return mixed result
         */
        public static function get($key)
        {
            return self::$driver->get($key);
        }
        /**
         * check if item exist by key
         * @param  string $key item key
         * @return bool exists result
         */
        public static function exists($key)
        {
            return self::$driver->exists($key);
        }
        /**
         * remove single cached item by key
         * @param  string $key item key to remove
         * @return bool success
         */
        public static function remove($key)
        {
            return self::$driver->remove ($key);
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
