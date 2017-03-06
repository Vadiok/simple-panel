<?php

namespace SimplePanel;


interface ViewInterface extends SingletonInterface
{

	/**
	 * Установка вью
	 *
	 * @param string $view
	 * @return $this
	 */
	public function setView($view);

	/**
	 * Получение вью
	 *
	 * @return mixed
	 */
	public function getView();

	/**
	 * Установка параметра вью
	 *
	 * @param string $property
	 * @param mixed $value
	 * @return $this
	 */
	public function set($property, $value);

	/**
	 * Получение параметра вью
	 *
	 * @param $property
	 * @param null $default
	 * @return mixed|null
	 */
	public function get($property, $default = null);

	/**
	 * Отображениее вью
	 *
	 * @param bool $exit
	 * @throws \Exception
	 */
	public function show($exit = true);

	/**
	 * JSON ответ по данным
	 *
	 * @param mixed|null $data
	 * @param bool $exit
	 */
	public function renderJson($data = null, $exit = true);

	/**
	 * JSON ответ по объекту Response
	 *
	 * @param Response $response
	 * @return void
	 */
	public function ajaxResponse($response);

	/**
	 * Заполнение основных параметров вью
	 *
	 * @return mixed
	 */
	public function fillCommonProperties();

}
