<?php

	namespace Core\Auth;

	interface Method
	{
		/**
		 * Get driver name
		 * @return [type] [description]
		 */
		public function getName();
		public function logout();
	}