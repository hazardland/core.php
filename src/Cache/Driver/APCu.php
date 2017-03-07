<?php

    namespace Core\Cache\Driver;

    class APCu implements \Core\Cache\Driver
    {
        public static function set ($key, $value, $ttl=null)
        {
            return apcu_store($key, $value, $ttl);
        }
        public static function get ($key)
        {
            return apcu_fetch($key);
        }
        public static function exists ($key)
        {
            return apcu_exists($key);
        }
        public static function remove ($key)
        {
            return apcu_delete($key);
        }
        public static function clean ()
        {
            return apcu_clear_cache('user');
        }
    }
