<?php

namespace SimplePanel;


/**
 * Class Group
 * @package SimplePanel
 */
abstract class Group implements GroupInterface
{

	public function can($action)
	{
		if (!property_exists($this, $action)) return false;
		return $this->$action;
	}

	/**
	 * @return string[]
	 */
	public function getRights()
	{
		$allRights = get_object_vars($this);
		$rights = [];
		foreach ($allRights as $right => $can) {
			if ($can) $rights[] = $right;
		}
		return $rights;
	}
}
