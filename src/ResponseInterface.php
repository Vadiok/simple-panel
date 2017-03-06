<?php

namespace SimplePanel;


interface ResponseInterface
{

	/**
	 * Установка всех параметров ответа
	 *
	 * @param mixed $data
	 * @param null|string $message
	 * @param int $status
	 * @return $this
	 */
	public function setAll($data, $message, $status);

	/**
	 * Получение статуса
	 *
	 * @return integer
	 */
	public function getStatus();

	/**
	 * Установка статуса
	 *
	 * @param bool $status
	 * @return $this
	 */
	public function setStatus($status);

	/**
	 * Установка успешного статуса
	 *
	 * @return $this
	 */
	public function setSuccessStatus();

	/**
	 * Установка статуса ошибки
	 *
	 * @return $this
	 */
	public function setErrorStatus();

	/**
	 * Установка статуса предупреждения
	 *
	 * @return $this
	 */
	public function setWarningStatus();

	/**
	 * Получение сообщения
	 *
	 * @return null|string
	 */
	public function getMessage();

	/**
	 * Установка сообщения
	 *
	 * @param null|string $message
	 * @return $this
	 */
	public function setMessage($message);

	/**
	 * Получения данных
	 *
	 * @return mixed
	 */
	public function getData();

	/**
	 * Установка данных
	 *
	 * @param mixed $data
	 * @return $this
	 */
	public function setData($data);

	/**
	 * Объект в массив
	 *
	 * @return mixed
	 */
	public function toArray();

}
