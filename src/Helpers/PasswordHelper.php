<?php

namespace SimplePanel\Helpers;


class PasswordHelper
{

	/**
	 * @param string $password
	 * @return string
	 */
	public static function getPasswordHash($password)
	{
		if (!is_int($password) && !is_string($password)) return null;
		return hash('sha256', $password);
	}
}
