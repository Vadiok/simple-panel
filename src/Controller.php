<?php

namespace SimplePanel;


class Controller
{

	protected $app = null;

	public function __construct(AppInterface $app)
	{
		$this->app = $app;
	}

	/**
	 * @param int $code
	 * @param string|null $message
	 * @param string|null $view
	 */
	public function abort($code = null, $message = null, $view = null)
	{
		if ($code) {
			http_response_code($code);
			if (!$message && !($this->app)::view()->get('message')) {
				if ($code === 404) {
					$message = 'Страница не найдена';
				} elseif ($code === 403) {
					$message = 'Доступ запрещен';
				}
			}
		}
		if (!$view) $view = 'error';
		if ($message) ($this->app)::view()->set('message', $message);
		($this->app)::view()->setView($view)
			->set('code', $code)
			->show();
		exit();
	}

	/**
	 * @param null|array|Response $data
	 * @param null|string $message
	 * @param int $status
	 * @return void
	 */
	protected function ajaxResponse($data = null, $message = null, $status = 1)
	{
		$response = ($data instanceof Response) ? $data : new Response($data, $message, $status);
		($this->app)::view()->ajaxResponse($response);
	}

	protected function redirect($route = null, $path = null)
	{
		($this->app)::router()->redirect($route, $path);
	}
}
