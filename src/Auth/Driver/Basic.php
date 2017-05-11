<?php

	namespace Core\Auth\Driver;

	class Basic implements \Core\Auth\Driver
	{
		public function getName()
		{
			return 'basic';
		}
	}
