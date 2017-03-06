<?php

namespace SimplePanel;


interface RequestInterface
{

	/**
	 * Получить параметр запроса
	 *
	 * @param string $key ключ параметра
	 * @param mixed|null $default значение, если нет парамтера с указанным ключом
	 * @return mixed|null
	 */
	public function get($key, $default = null);

	/**
	 * Массив со всеми параметрами запроса
	 *
	 * @return array
	 */
	public function getAll();

	/**
	 * Проверка, есть ли указанный параметр в запросе
	 * (возвращает true даже, если значение параметра null или false)
	 *
	 * @param $key
	 * @return bool
	 */
	public function has($key);

	/**
	 * Проверка, является ли запрос аяксовым
	 *
	 * @return bool
	 */
	public function isAjax();

}
