<?php

	namespace Core;

	class Cookie
	{
		public static $prefix = 'app';
		public static function setPrefix ($prefix)
		{
			self::$prefix = $prefix;
		}
		public static function set ($key, $value)
		{
			setcookie (self::$prefix.'|'.$key, $value);
		}
		public static function get ($key, $default=null)
		{
			if (!isset($_COOKIE[self::$prefix.'|'.$key]))
			{
				return $default;
			}
			return $_COOKIE[self::$prefix.'|'.$key];
		}
		public static function remove ($key)
		{
			setcookie (self::$prefix.'|'.$key, null,-1);
		}
		public static function clean ()
		{
			//@todo
		}
	}
