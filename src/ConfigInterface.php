<?php

namespace SimplePanel;


interface ConfigInterface
{

	/**
	 * Обновляет свои свойства, перечитывая конфигурационный файл
	 */
	public function checkConfig();

	/**
	 * Возвращает значение параметра конфигурации
	 *
	 * @param string $key параметр конфигурации, разделитель - точка, пример: db.host
	 * @param null|mixed $default возвращаемое значение, если искомый параметр конфига не найден
	 * @return mixed|null
	 */
	public function get($key, $default = null);

	/**
	 * Создан ли файл конфигурации
	 *
	 * @return bool
	 */
	public function isConfigExist();

}