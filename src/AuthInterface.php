<?php

namespace SimplePanel;


interface AuthInterface
{

	/**
	 * Проверка, залогинен ли менеджер
	 *
	 * @return bool
	 */
	public function isLogged();

	/**
	 * Экземпляр класса менеджера
	 *
	 * @return null|mixed|Manager
	 */
	public function logged();

}
