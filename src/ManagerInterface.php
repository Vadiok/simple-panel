<?php

namespace SimplePanel;


interface ManagerInterface
{

	/**
	 * Определяет, позволяется ли менеджеру выполнить указанное действие
	 *
	 * @param string $action
	 * @return bool
	 */
	public function can($action);

	/**
	 * Возвращает массив с разрешенными действиями для менеджера
	 *
	 * @return string[]
	 */
	public function getRights();

}
