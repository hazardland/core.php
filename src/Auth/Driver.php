<?php

	namespace Core\Auth;

	interface Driver
	{
		/**
		 * Get driver name
		 * @return [type] [description]
		 */
		public function getName();
	}