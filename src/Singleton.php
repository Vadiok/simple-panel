<?php

namespace SimplePanel;


abstract class Singleton implements SingletonInterface
{

	protected static $instances = [];

	abstract protected function __construct();

	public static function getInstance()
	{
		if (!isset(self::$instances[static::class])) {
			self::$instances[static::class] = new static();

		}
		return self::$instances[static::class];
	}

}
