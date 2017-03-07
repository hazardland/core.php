<?php

    namespace Core\Cache\Driver;

    class APC implements \Core\Cache\Driver
    {
        public static function set ($key, $value, $ttl=null)
        {
            return apc_store($key, $value, $ttl);
        }
        public static function get ($key)
        {
            return apc_fetch($key);
        }
        public static function exists ($key)
        {
            return apc_exists($key);
        }
        public static function remove ($key)
        {
            return apc_delete($key);
        }
        public static function clean ()
        {
            return apc_clear_cache('user');
        }
    }
