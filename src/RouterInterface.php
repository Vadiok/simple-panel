<?php

namespace SimplePanel;


interface RouterInterface
{

	/**
	 * Запуск контроллера роута
	 *
	 * @param string $route
	 * @param string|null $method
	 */
	public function apply($route = '/', $method = null);

	/**
	 * Получение пути текущего роута
	 *
	 * @param string $default
	 * @return string
	 */
	public function getCurrentRoute($default = '/');

	/**
	 * Добавление get запроса с методом контроллера
	 *
	 * @param string $uri
	 * @param string $controller
	 * @param string $action
	 */
	public function get($uri, $controller, $action);

	/**
	 * Добавление post запроса с методом контроллера
	 *
	 * @param string $uri
	 * @param string $controller
	 * @param string $action
	 */
	public function post($uri, $controller, $action);

	/**
	 * Добавление put запроса с методом контроллера
	 *
	 * @param string $uri
	 * @param string $controller
	 * @param string $action
	 */
	public function put($uri, $controller, $action);

	/**
	 * Добавление patch запроса с методом контроллера
	 *
	 * @param string $uri
	 * @param string $controller
	 * @param string $action
	 */
	public function patch($uri, $controller, $action);

	/**
	 * Добавление delete запроса с методом контроллера
	 *
	 * @param string $uri
	 * @param string $controller
	 * @param string $action
	 */
	public function delete($uri, $controller, $action);

	/**
	 * Добавление всех запросов с методом контроллера
	 *
	 * @param string $uri
	 * @param string $controller
	 * @param string $action
	 * @param array $only List of methods
	 */
	public function any($uri, $controller, $action, $only = ['get', 'post', 'put', 'patch', 'delete']);

	/**
	 * Редирект на роут
	 *
	 * @param null $route
	 * @param null $path
	 */
	public function redirect($route = null, $path = null);

	/**
	 * Возвращает метод запроса (в нижнем регистре)
	 *
	 * @return string
	 */
	public function getRequestMethod();

}
