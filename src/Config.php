<?php

namespace SimplePanel;


class Config extends Singleton implements ConfigInterface
{
	protected $config = [];
	protected $configExist = false;

	protected function __construct()
	{
		$this->checkConfig();
	}

	public function checkConfig()
	{
		$configFile = defined('SIMPLE_PANEL_CONFIG_FILE') ? SIMPLE_PANEL_CONFIG_FILE : null;
		$this->configExist = false;
		if ($configFile && file_exists($configFile)) {
			$this->config = include $configFile;
			$this->configExist = true;
		}
	}

	public function get($key, $default = null)
	{
		return $this->getConfigProperty($key, $default, $this->config);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @param array|mixed $configPart
	 * @return mixed
	 */
	protected function getConfigProperty($key, $default, $configPart)
	{
		$parts = explode('.', $key);
		$firstPart = $parts[0];
		if (array_key_exists($firstPart, $configPart)) {
			$configBlock = $configPart[$firstPart];
		} else {
			return $default;
		}
		if (count($parts) <= 1) {
			return $configBlock;
		} else {
			unset($parts[0]);
			return $this->getConfigProperty(implode('.', $parts), $default, $configBlock);
		}
	}

	public function isConfigExist()
	{
		return $this->configExist;
	}

}
