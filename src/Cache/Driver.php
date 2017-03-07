<?php

    namespace Core\Cache;

    interface Driver
    {
        /**
         * store item in cache
         * @param string $key item key
         * @param mixed $value item
         * @param int $ttl time to live
         */
        public static function set($key, $value, $ttl=null);
        /**
         * [get description]
         * @param  [type]        $key      [description]
         * @param  \Closure|null $callback if not founc call callback
         * @return [type]                  [description]
         */
        public static function get($key);
        /**
         * check if item exist by key
         * @param  string $key item key
         * @return bool exists result
         */
        public static function exists($key);
        /**
         * remove single cached item by key
         * @param  string $key item key to remove
         * @return bool success
         */
        public static function remove($key);
        /**
         * clean all cached items
         * @return bool success
         */
        public static function clean();
    }
