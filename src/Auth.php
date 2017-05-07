<?php

	namespace Core;

	/**
	 * Rule for drivers:
	 * 		User id is stored via Auth::setId ($userId)
	 * 		If user id is set user is authorized
	 * Rule for models
	 * 		User class is \App\User
	 * 		Model must return user object via using user id
	 *
	 * Scenario:
	 * 		Driver: Basic,Facebook
	 * 		Model: Table
	 */

	class Auth
	{
		private $drivers = [];
		private $model;
		public static function addDriver (\Core\Auth\Driver $driver)
		{
			self::$drivers[$driver->getName()] = $driver;
		}
		public static function getDriver($name)
		{
			$name = strtolower($name);
			if (isset($this->drivers[$name]))
			{
				return $this->drivers[$name];
			}
		}
		public static function setModel (\Core\Auth\Model $model)
		{
			$this->model = $model;
		}
		public static function setId($id)
		{
			Session::set('user',$id);
		}
		public static function getId()
		{
			Session:get('user');
		}
		public static function user()
		{
			if (self::$user===null && self::getId())
			{
				self::$user = $this->model->getUser(self::getId());
			}
		}
		public static function check ()
		{
			if (self::getId())
			{
				return true;
			}
			return false;
		}
	}