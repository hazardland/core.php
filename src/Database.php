<?php

	namespace Core;

	class Database
	{
		private static $path;
		private static $extension;
		private static $default = 'default';
		private static $connections = [];
		public static function setPath ($path, $extension='.php')
		{
			self::$path = $path;
			self::$extension = $extension;
		}
		public static function setDefault($name)
		{
			self::$default = $name;
		}
		public static function getDefault()
		{
			return self::$default;
		}
		private static function init ($name)
		{
			if (self::$path===null)
			{
				trigger_error("[\\Core\\Database] database connection file path not defined", E_USER_ERROR);
			}
			include self::$path.'/'.str_replace(['/','\\'],'',$name).self::$extension;
			if (isset(self::$connections[$name]))
			{
				return true;
			}
			return false;
		}
		public static function add (\PDO $connection, $name=null)
		{
			if ($name===null)
			{
				$name = self::$default;
			}
			self::$connections[$name] = $connection;
		}
		public static function get ($name=null)
		{
			if ($name===null)
			{
				$name = self::$default;
			}
			if (!isset(self::$connections[$name]) && !self::init($name))
			{
				return;
			}
			return self::$connections[$name];
		}
	}
