<?php

namespace SimplePanel;


interface GroupInterface
{

	/**
	 * Определяет, позволяет ли группа выполнять указанное действие
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action);

	/**
	 * Возвращает массив с разрешенными действиями
	 *
	 * @return string[]
	 */
	public function getRights();

}