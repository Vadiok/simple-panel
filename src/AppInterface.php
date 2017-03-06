<?php

namespace SimplePanel;


use Illuminate\Database\Connection;

interface AppInterface
{

	public static function init($authClass, $configClass, $requestClass, $routerClass, $sessionClass, $viewClass);

	/**
	 * Класс аутентификации
	 *
	 * @return AuthInterface
	 */
	public static function auth();

	/**
	 * Класс для работы с БД
	 *
	 * @return Connection|null
	 */
	public static function db();

	/**
	 * Попытка подключения к БД
	 *
	 * @return bool
	 */
	public static function retryDbConnection();

	/**
	 * Класс работы с конфигурацией
	 *
	 * @return ConfigInterface
	 */
	public static function config();

	/**
	 * Класс для работы с пользовательскими запросами
	 *
	 * @return RequestInterface
	 */
	public static function request();

	/**
	 * Класс для работы с роутингом
	 *
	 * @return mixed
	 */
	public static function router();
	public static function session();
	public static function view();
	public static function getController($controllerName);

}
