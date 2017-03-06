<?php

namespace SimplePanel;


interface SessionInterface
{

	/**
	 * Установка значения параметра сессии
	 *
	 * @param string $key
	 * @param null|mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null);

	/**
	 * Установка значения параметра сессии по ключу
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function set($key, $value);

	/**
	 * Очистка значения параметра сессии
	 *
	 * @param string $key
	 */
	public function clear($key);

}
