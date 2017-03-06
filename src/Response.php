<?php

namespace SimplePanel;


class Response implements ResponseInterface
{

	/**
	 * @var int 1 - success, 0 - error, -1 - warning
	 */
	protected $status = 1;
	/**
	 * @var null|string
	 */
	protected $message = null;
	/**
	 * @var mixed
	 */
	protected $data = null;

	/**
	 * Response constructor.
	 * @param mixed $data
	 * @param null|string $message
	 * @param int $status
	 */
	public function __construct($data = null, $message = null, $status = 1)
	{
		$this->setAll($data, $message, $status);
	}

	/**
	 * @param mixed $data
	 * @param null|string $message
	 * @param int $status
	 * @return $this
	 */
	public function setAll($data, $message, $status)
	{
		$this->setData($data);
		$this->setMessage($message);
		$this->setStatus($status);
		return $this;
	}

	/**
	 * @return integer
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * @param bool $status
	 * @return $this
	 */
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setSuccessStatus()
	{
		$this->status = 1;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setErrorStatus()
	{
		$this->status = 0;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function setWarningStatus()
	{
		$this->status = -1;
		return $this;
	}

	/**
	 * @return null|string
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @param null|string $message
	 * @return $this
	 */
	public function setMessage($message)
	{
		$this->message = $message;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param mixed $data
	 * @return $this
	 */
	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function toArray()
	{
		return [
			'status' => $this->getStatus(),
			'message' => $this->getMessage(),
			'data' => $this->getData(),
		];
	}
}
