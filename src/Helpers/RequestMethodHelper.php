<?php

namespace SimplePanel\Helpers;


class RequestMethodHelper
{

	public static function isGetRequest()
	{
		return (!getenv('REQUEST_METHOD') || (getenv('REQUEST_METHOD') === 'GET'));
	}

}
