<?php

namespace SimplePanel;


use SimplePanel\Helpers\RequestMethodHelper;

class Request extends Singleton implements RequestInterface
{

	/**
	 * @var array|null
	 */
	protected $requestData = null;
	protected $isAjaxRequest = null;

	public function __construct()
	{
		$this->fillRequestData();
		if (is_null($this->isAjaxRequest)) {
			$this->isAjaxRequest = (
				!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
				strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
			);
		}
	}

	public function get($key, $default = null)
	{
		return $this->has($key) ? $this->requestData[$key] : $default;
	}

	/**
	 * @return array
	 */
	public function getAll()
	{
		return $this->requestData;
	}

	public function has($key)
	{
		return array_key_exists($key, $this->requestData);
	}

	public function isAjax()
	{
		return $this->isAjaxRequest;
	}

	protected function fillRequestData()
	{
		$this->requestData = empty($_GET) ? [] : $_GET;
		if (!RequestMethodHelper::isGetRequest()) {
			$data = $_POST;
			if (empty($data)) {
				$data = [];
				$jsonString = file_get_contents('php://input');
				if (!empty($jsonString)) {
					$inputData = json_decode($jsonString, true);
					if ((json_last_error() == JSON_ERROR_NONE) && is_array($inputData)) {
						$this->isAjaxRequest = true;
						$data = $inputData;
					}
				}
			}
			$this->requestData = array_merge($this->requestData, $data);
		}
	}
}
