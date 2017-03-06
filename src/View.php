<?php

namespace SimplePanel;


class View extends Singleton implements ViewInterface
{
	protected $view = null;
	protected $properties = [];
	protected $commonProperties = [];

	public function __construct() {
		foreach (['panelName', 'baseUrl', 'devMode',] as $configSetting) {
			$this->commonProperties[$configSetting] = App::config()->get($configSetting);
		}
	}

	/**
	 * @param string $view
	 * @return $this
	 */
	public function setView($view)
	{
		$this->view = $view;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * @param string $property
	 * @param mixed $value
	 * @return $this
	 */
	public function set($property, $value)
	{
		$this->properties[$property] = $value;
		return $this;
	}

	/**
	 * @param $property
	 * @param null $default
	 * @return mixed|null
	 */
	public function get($property, $default = null)
	{
		if (array_key_exists($property, $this->properties)) return $this->properties[$property];
		if (array_key_exists($property, $this->commonProperties)) return $this->commonProperties[$property];
		return $default;
	}

	/**
	 * @param bool $exit
	 * @throws \Exception
	 */
	public function show($exit = true)
	{
		$pathInfo = pathinfo($this->view);
		$this->fillCommonProperties();
		$properties = array_merge($this->commonProperties, $this->properties);
		if (empty($pathInfo['extension'])) $this->view .= '.twig';
		$viewFile = __DIR__ . '/../views/' . trim($this->view, '/');
		if (!file_exists($viewFile)) {
			throw new \Exception(sprintf('View file not exist: %s', $viewFile));
		}
		if (empty($pathInfo['extension']) || ($pathInfo['extension'] === 'twig')) {
			$twigLoader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
			$twig = new \Twig_Environment($twigLoader, [
				'debug' => App::config()->get('devMode', false),
				'auto_reload' => true,
			]);
			if (App::config()->isConfigExist() && !App::config()->get('devMode')) {
				$twig->setCache(__DIR__ . '/../../cache/twig');
			}
			echo $twig->render($this->view, $properties);
		} else {
			include __DIR__ . sprintf('/../views/%s', $this->view);
		}
		if ($exit) exit();
	}

	/**
	 * @param mixed|null $data
	 * @param bool $exit
	 */
	public function renderJson($data = null, $exit = true)
	{
		header('Content-Type: application/json');
		echo json_encode($data);
		if ($exit) exit();
	}

	/**
	 * @param Response $response
	 * @return void
	 */
	public function ajaxResponse($response)
	{
		header('Content-Type: application/json');
		$this->renderJson($response->toArray());
	}

	public function fillCommonProperties()
	{
		$this->commonProperties['isLogged'] = App::auth() ? App::auth()->isLogged() : false;
		$this->commonProperties['loggedInfo'] = null;
		$logged = App::auth() ? App::auth()->logged() : null;
		if ($this->commonProperties['isLogged'] && $logged) {
			$this->commonProperties['loggedInfo'] = $logged->toArrayWithRights();
		}
		return $this;
	}
}
