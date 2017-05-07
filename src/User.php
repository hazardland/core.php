<?php

	namespace Core;

	abstract class User
	{
		private $id;
		private $login;
		private $password;
		public function __construct($id,$login)
		{
			$this->id = $id;
			$this->login = $login;
		}
		public function setPassword($password)
		{
			$this->password = $password;
		}
		public function getPassword()
		{
			return $this->password;
		}
		public function getName()
		{
			return $this->login;
		}
	}