<?php

namespace SimplePanel;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Connection;
use Illuminate\Database\ConnectionInterface;

abstract class App implements AppInterface
{
	/** @var ConfigInterface|null $config */
	protected static $config = null;
	/** @var ConnectionInterface|null $db */
	protected static $db = null;
	/** @var RequestInterface|null $request */
	protected static $request = null;
	/** @var RouterInterface|null $view */
	protected static $router = null;
	/** @var SessionInterface|null $session */
	protected static $session = null;
	/** @var AuthInterface|null $auth */
	protected static $auth = null;
	/** @var ViewInterface|null $view */
	protected static $view = null;

	public static function init($authClass, $configClass, $requestClass, $routerClass, $sessionClass, $viewClass)
	{
		self::$config = $configClass::getInstance();
		self::$request = $requestClass::getInstance();
		self::$router = $routerClass::getInstance();
		self::$session = $sessionClass::getInstance();
		self::$view = $viewClass::getInstance();
		self::includeSystemRoutes();
		if (!self::config()->isConfigExist()) {
			if (!(self::router()->getCurrentRoute() === 'install')) self::router()->redirect('install');
			self::router()->apply('install');
			return;
		}
		if (!App::connectToDB()) {
			echo 'DB connection error';
			die();
		}
		self::$auth = $authClass::getInstance(); // Must be after Session & ORM configuration!
		require_once __DIR__ . '/routes.php';
		$routingUri = !empty($_GET['r']) ? $_GET['r'] : '/';
		self::router()->apply($routingUri);
	}

	/**
	 * @return AuthInterface
	 */
	public static function auth()
	{
		return self::$auth;
	}

	/**
	 * @return ConnectionInterface
	 */
	public static function db()
	{
		return self::$db;
	}

	/**
	 * @return bool
	 */
	public static function retryDbConnection()
	{
		return self::connectToDB();
	}

	/**
	 * @return ConfigInterface
	 */
	public static function config()
	{
		return self::$config;
	}

	/**
	 * @return RequestInterface
	 */
	public static function request()
	{
		return self::$request;
	}

	/**
	 * @return RouterInterface
	 */
	public static function router()
	{
		return self::$router;
	}

	/**
	 * @return SessionInterface
	 */
	public static function session()
	{
		return self::$session;
	}

	/**
	 * @return ViewInterface
	 */
	public static function view()
	{
		return self::$view;
	}

	/**
	 * @param string $controllerName
	 * @return Controller|null
	 */
	public static function getController($controllerName)
	{
		try {
			$controllerClass = '\\' . $controllerName;
			if (!class_exists($controllerClass, true)) {
				$controllerClass = '\\App\\Controllers\\' . $controllerName;
			}
			$controller = new $controllerClass();
			return $controller;
		} catch (\Exception $exception) {
			return null;
		}
	}

	/**
	 * @param string[] $connectOptions
	 * @return bool
	 */
	protected static function connectToDB($connectOptions = [])
	{
		$driver   = !empty($connectOptions['driver']) ?       $connectOptions['driver'] :       self::$config->get('db.driver', 'mysql');
		$host     = !empty($connectOptions['host']) ?         $connectOptions['host'] :         self::$config->get('db.host', 'localhost');
		$database = !empty($connectOptions['name']) ?         $connectOptions['name'] :         self::$config->get('db.name');
		$username = !empty($connectOptions['user']) ?         $connectOptions['user'] :         self::$config->get('db.user', null);
		$password = !empty($connectOptions['password']) ?     $connectOptions['password'] :     self::$config->get('db.password', null);
		$prefix   = !empty($connectOptions['table_prefix']) ? $connectOptions['table_prefix'] : self::$config->get('db.table_prefix', null);
		$capsule = new Capsule();
		$capsule->addConnection([
			'driver'    => $driver,
			'host'      => $host,
			'database'  => $database,
			'username'  => $username,
			'password'  => $password,
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => $prefix,
		]);
		$capsule->setAsGlobal();
		$capsule->bootEloquent();
		try {
			$capsule->getConnection()->getPdo();
		} catch (\Exception $e) {
			return false;
		}
		self::$db = $capsule->getConnection();
		return true;
	}

	protected static function includeSystemRoutes()
	{
		require_once __DIR__ . '/routes.php';
	}

}
