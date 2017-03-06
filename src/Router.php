<?php

namespace SimplePanel;


use SimplePanel\Helpers\RequestMethodHelper;

class Router extends Singleton implements RouterInterface
{
	protected $get = [];
	protected $post = [];
	protected $put = [];
	protected $patch = [];
	protected $delete = [];

	public function __construct() {}

	/**
	 * Run route controller
	 *
	 * @param string $route
	 * @param string|null $method
	 */
	public function apply($route = '/', $method = null)
	{
		if (!$method) $method = $this->getRequestMethod();
		if (array_key_exists($route, $this->$method)) {
			$action = explode('@', $this->$method[$route]);
			if (count($action) < 2) {
				App::view()
					->set('message', sprintf('Wrong controller method: %s', $this->$method[$route]));
				App::getController('Controller')->abort(404);
			}
			$controller = App::getController($action[0]);
			if (!$controller) {
				App::view()
					->set('message', sprintf('Controller not found: %s', $this->$method[$route]));
				App::getController('Controller')->abort(404);
			}
			if (!method_exists($controller, $action[1])) {
				App::view()
					->set('message', sprintf('Method %s not found in controller %s', $action[1], $action[0]));
				App::getController('Controller')->abort(404);
			}
			$controllerMethod = $action[1];
			$controller->$controllerMethod();
		} else {
			App::getController('Controller')->abort(404);
		}
	}

	/**
	 * @param string $default
	 * @return string
	 */
	public function getCurrentRoute($default = '/')
	{
		if (!isset($_GET['r'])) return $default;
		$routingUri = !empty($_GET['r']) ? trim($_GET['r'], '/ ') : '/';
		return !empty($routingUri) ? $routingUri : '/';
	}

	/**
	 * Add controller action to "get" method route
	 *
	 * @param string $uri
	 * @param string $controller
	 * @param string $action
	 */
	public function get($uri, $controller, $action)
	{
		$this->addRoute('get', $uri, $controller, $action);
	}

	public function post($uri, $controller, $action)
	{
		$this->addRoute('post', $uri, $controller, $action);
	}

	public function put($uri, $controller, $action)
	{
		$this->addRoute('put', $uri, $controller, $action);
	}

	public function patch($uri, $controller, $action)
	{
		$this->addRoute('patch', $uri, $controller, $action);
	}

	public function delete($uri, $controller, $action)
	{
		$this->addRoute('delete', $uri, $controller, $action);
	}

	/**
	 * Add controller action to all methods
	 *
	 * @param string $uri
	 * @param string $controller
	 * @param string $action
	 * @param array $only List of methods
	 */
	public function any($uri, $controller, $action, $only = ['get', 'post', 'put', 'patch', 'delete'])
	{
		foreach ($only as $method) {
			$this->addRoute($method, $uri, $controller, $action);
		}
	}

	/**
	 * Send redirect to route
	 *
	 * @param null $route
	 * @param null $path
	 */
	public function redirect($route = null, $path = null)
	{
		$baseUrl = App::config()->get('baseUrl', '');
		if (!App::config()->isConfigExist()) {
			$baseUrl = parse_url( $_SERVER['REQUEST_URI'])['path'];
		}
		$fullPath = '/' . trim($baseUrl, '/') . '/';
		if ($path) {
			$fullPath .= trim($path, '/') . '/';
		}
		if ($route) {
			$route = trim($route, '/');
			if (empty($route)) $route = '/';
			$fullPath .= '?r=' . $route;
		}
		header(
			sprintf('Location: %s', $fullPath)
		);
		exit();
	}

	protected function addRoute($method, $uri, $controller, $action)
	{
		$uri = trim($uri, '/');
		if (empty($uri)) $uri = '/';
		$this->$method[$uri] = $controller . '@' . $action;
	}

	/**
	 * @return string
	 */
	public function getRequestMethod()
	{
		if (RequestMethodHelper::isGetRequest()) return 'get';
		$requestMethod = strtolower(getenv('REQUEST_METHOD'));
		if (($requestMethod === 'post')) {
			if (App::request()->get('_method')) {
				$requestMethod = strtolower(App::request()->get('_method'));
			}
		}
		if (($requestMethod === 'post') && isset($_POST['_method'])) {
			$requestMethod = strtolower($_POST['_method']);
		}
		if (in_array($requestMethod, ['post', 'put', 'patch', 'delete',])) return $requestMethod;
		return 'get';
	}

}
