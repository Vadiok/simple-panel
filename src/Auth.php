<?php

namespace SimplePanel;


use SimplePanel\Helpers\PasswordHelper;

class Auth extends Singleton implements AuthInterface
{

	protected $managerClass = null;

	/**
	 * @var Manager|null
	 */
	protected $logged = null;

	public function __construct()
	{
		$this->managerClass = defined('SIMPLE_PANEL_MANAGER_CLASS') ? SIMPLE_PANEL_MANAGER_CLASS : Manager::class;
		$this->checkLogging();
	}

	/**
	 * @return bool
	 */
	public function isLogged()
	{
		return !!$this->logged;
	}

	/**
	 * @return Manager|null
	 */
	public function logged()
	{
		return $this->logged;
	}

	/**
	 * Updates $logged
	 */
	public function checkLogging()
	{
		if (App::session()->get('logged_id', false)) {
			$logged = Manager::find(App::session()->get('logged_id'));
			if ($logged) {
				$this->logged = $logged;
				return;
			}
		}
		$this->logged = null;
	}

	/**
	 * @param $email
	 * @param $password
	 * @return Manager|null
	 */
	public function attempt($email, $password)
	{
		/** @var Manager $manager */
		$manager = Manager::where('email', $email)
			->where('password', PasswordHelper::getPasswordHash($password))
			->first();
		if ($manager) {
			$this->logged = $manager;
			App::session()->set('logged_id', $manager->id);
			return $this->logged();
		} else {
			return null;
		}
	}

	public function logout()
	{
		App::session()->clear('logged_id');
		$this->logged = null;
	}
}
