<?php
namespace Craft;

class Curate_UsersContentRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'curate_users_content';
    }


   public function defineRelations()
    {
		return array(
			'entry' => array(static::BELONGS_TO, 'EntryRecord', 'required' => true, 'onDelete' => static::CASCADE),
			'user'  => array(static::BELONGS_TO, 'UserRecord',  'required' => true, 'onDelete' => static::CASCADE)
		);
    }


   /**
   * @return array
    */
 	public function defineIndexes()
        {
         	return array(
                        array('columns' => array('entryId', 'userId'), 'unique' => true),
                );
        }
}