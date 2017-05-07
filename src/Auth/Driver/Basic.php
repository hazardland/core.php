<?php

	namespace Core\Auth\Driver;

	class Basic implements \Core\Auth\Driver
	{
		public function getName()
		{
			return 'basic';
		}
	}

	Auth::getDriver('basic')->login($_REQUEST['email'],$_REQUEST['password']);
	Auth::getModel()->hit($id);