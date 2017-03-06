<?php

namespace SimplePanel;


class Session extends Singleton implements SessionInterface
{

	protected $prefix = '';

	public function __construct()
	{
		$this->prefix = App::config()->get('session.prefix', 's_panel_');
		session_start();
	}

	/**
	 * @param string $key
	 * @param null|mixed $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		$realKey = $this->realKey($key);
		return isset($_SESSION[$realKey]) ? $_SESSION[$realKey] : $default;
	}

	/**
	 * @param string $key
	 * @param string $value
	 */
	public function set($key, $value)
	{
		$_SESSION[$this->realKey($key)] = $value;
	}

	/**
	 * @param string $key
	 */
	public function clear($key)
	{
		$realKey = $this->realKey($key);
		if (array_key_exists($realKey, $_SESSION)) {
			unset($_SESSION[$realKey]);
		}
	}

	/**
	 * @param string $key
	 * @return string
	 */
	protected function realKey($key)
	{
		return $this->prefix . $key;
	}
}
