<?php

namespace SimplePanel;


use SimplePanel\Helpers\PasswordHelper;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Manager
 * @package App\Models
 *
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $group
 */
class Manager extends Model implements ManagerInterface
{
	use SoftDeletes;

	protected $fillable = [
		'email',
		'name',
		'password',
		'group',
	];

	protected $hidden = [
		'password',
	];

	/**
	 * @var Group|null
	 */
	protected $groupInstance = null;

	/**
	 * @param string $action
	 * @return bool
	 */
	public function can($action)
	{
		if (!$this->group) return false;
		if (!$this->groupInstance) $this->setGroupInstance();
		if (!$this->groupInstance) return false;
		return $this->groupInstance->can($action);
	}

	/**
	 * @return array
	 */
	public function getRights()
	{
		if (!$this->group) return [];
		if (!$this->groupInstance) $this->setGroupInstance();
		if (!$this->groupInstance) return [];
		return $this->groupInstance->getRights();
	}

	/**
	 * @return array
	 */
	public function toArrayWithRights()
	{
		$rights = $this->getRights();
		$array = parent::toArray();
		return array_merge($array, ['rights' => $rights]);
	}

	public function setPasswordAttribute($password)
	{
		$this->attributes['password'] = PasswordHelper::getPasswordHash($password);
	}

	protected function setGroupInstance()
	{
		if ($this->group) {
			$this->groupInstance = call_user_func($this->group . '::getInstance');
		}
	}

}
