<?php
namespace Craft;

//Craft::requirePackage(CraftPackage::Users);

/**
 * Curate user content model class
 *
 * Used for transporting user bookmarks data throughout the system.
 */
class Curate_UsersContentModel extends BaseModel
{
	/**
	 * Use the translated group name as the string representation.
	 *
	 * @return string
	 */
	function __toString()
	{
		return Craft::t($this->name);
	}

	/**
	 * @access protected
	 * @return array
	 */

	protected function defineAttributes()
	{
		$attributes['id'] = AttributeType::Number;
		$attributes['userId'] = AttributeType::Number;
		$attributes['entryId'] = AttributeType::Number;

		return $attributes;
	}
}