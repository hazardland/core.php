<?php

	namespace Core\Auth\Model;

	class Table implements Core\Auth\Model
	{
		public function getUser ($id)
		{
			$query = Database::get()->prepare("
				SELECT
				`id`,
				`login`,
				`password`
				FROM `users`
				WHERE `id`=:id
				");
			$query->bindParam(':id', self::getId());
			if ($query->execute())
			{
				if ($row = $query->fetch())
				{
					new \App\User($row);
				}
			}
		}
	}