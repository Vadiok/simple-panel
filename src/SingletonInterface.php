<?php

namespace SimplePanel;


interface SingletonInterface
{

	/**
	 * @return static
	 */
	public static function getInstance();

}
