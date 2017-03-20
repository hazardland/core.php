<?php

	namespace Core;

	class Database
	{
		private static $default;
		private static $connections = [];
		private static $handlers = [];
		/**
		 * Set default connection name
		 * Default is 'default'
		 * @param string $name default connection name
		 */
		public static function setDefault($name)
		{
			self::$default = $name;
		}
		/**
		 * Get default connection name
		 * @return string name
		 */
		public static function getDefault()
		{
			return self::$default;
		}
		/**
		 * Creates \PDO object when called
		 * @param  string $name connection name
		 * @return bool success
		 */
		private static function init ($name)
		{
			if (!isset(self::$connections[$name]))
			{
				return false;
			}
			self::$handlers[$name] = new \PDO (self::$connections['dsn'],
											   self::$connections['username'],
											   self::$connections['password'],
											   self::$connections['options']);
			if (isset(self::$handlers[$name]))
			{
				return true;
			}
			return false;
		}
		/**
		 * Add config for PDO connection
		 * This method does not open connection
		 * Connection is open when Database::get() is called
		 * @param string $dsn      PDO dsn
		 * @param string $username PDO username
		 * @param string $password PDO password
		 * @param array  $options PDO options
		 * @param string $name PDO name
		 */
		public static function add ($dsn, $username, $password, $options=[], $name=null)
		{
			if ($name===null)
			{
				$name = self::$default===null?'default':self::$default;
			}
			self::$connections[$name] = [];
			self::$connections[$name]['dsn'] = $dsn;
			self::$connections[$name]['username'] = $username;
			self::$connections[$name]['password'] = $password;
			self::$connections[$name]['options'] = $options;
			if (self::$default===null)
			{
				self::$default = $name;
			}
		}
		/**
		 * [Open and] get database connection
		 * @param  string $name Connection name if not name passed 'default' is used or name which was set by Database::setDefault
		 * @return \PDO       [description]
		 */
		public static function get ($name=null)
		{
			if ($name===null)
			{
				$name = self::$default===null?'default':self::$default;
			}
			if (!isset(self::$handlers[$name]) && !self::init($name))
			{
				return;
			}
			return self::$handlers[$name];
		}
	}
