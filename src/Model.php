<?php

namespace SimplePanel;

use Illuminate\Database\Query\Builder;

/**
 * Class Model
 * @package App\Models
 * @method static Builder|Model|mixed where(string $field, mixed $valueOrOperator, mixed $valueOrNull = null)
 */
abstract class Model extends \Illuminate\Database\Eloquent\Model
{

	/**
	 * Table name or class name to snake if table not set
	 * @return string
	 */
	public static function getTableName()
	{
		return (new static)->getTable();
	}
}
