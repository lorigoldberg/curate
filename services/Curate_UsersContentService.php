<?php

namespace Craft;

Craft::requirePackage(CraftPackage::Users);

/**
 *
 */
class Curate_UsersContentService extends BaseApplicationComponent
{

    /**
     * Assigns an entry as a favorite to a user
     *
     * @param int $userId
     * @param int|array $entryId
     * @return bool
     */
    public function saveUserFavorite(Curate_UsersContentModel $userContentModel)
    {
        $faveRecord = new Curate_UsersContentRecord;
    
        // see if we can refactor to something like this:
        //$params = $userContentModel->getAttributes();
        //$faveRecord->setAttributes($params);
    
        $faveRecord->userId = $userContentModel->getAttribute('userId');
        $faveRecord->entryId = $userContentModel->getAttribute('entryId');
    
        // TODO handle errors
        $res = $faveRecord->save();

        return $res;
    }
    
    /**
     * Deletes a fave by its user and entry IDs.
     *
     * @param int $entryId
     * @param int $userId
     * @return bool 
    */
      public function deleteUserFavorite(Curate_UsersContentModel $userContentModel)
      {
          if (empty($userContentModel)) return false;
        
          $params['userId'] = $userContentModel->getAttribute('userId');
          $params['entryId'] = $userContentModel->getAttribute('entryId');
          $res = craft()->db->createCommand()->delete('curate_users_content', $params);
    
          return $res;
      }

    /**
     * Determines whether an entry is faved by a user
     *
     * @param int $userId
     * @param int $entryId
     * @return bool;
     */
    public function isFavedByUser($entryId, $userId)
    {       
        // if there is no userId but there is a session...
        if (empty($userId) && craft()->userSession->isLoggedIn()) {
            $userId = craft()->userSession->getUser()->id;      
        }
        
        if(empty($entryId) || empty($userId)) return false;
        
        $faved = false;
        $favedCount = $this->getCount($entryId, $userId);
        if($favedCount === 1) $faved = true;
        return $faved;
    }
    
    /**
     * Gets count of favorites for an entry
     *
     * @param int $entryId
     * @return int;
     */
    public function getFaveCount($entryId)
    {       
        if(empty($entryId)) return false;
        return $this->getCount($entryId);
    }

    /**
     * Gets user faves by a user ID.
     *
     * @param int $userId
     * (more params here eventually)
     */
    public function getFavesByUserId($userId= '', $indexBy = null)
    {
        // if there is no userId but there is a session...
        if (empty($userId) && craft()->userSession->isLoggedIn()) {
            $userId = craft()->userSession->getUser()->id;
            
            if (empty($userId)) {
                return false;
            }
            
        } 

        // get results
        // this uses findAllByAttributes(array $attributes, mixed $condition='', array $params=array ( ))
		// TODO: add more attributes
        $attributes = array('userId'=>$userId);
        $entryRecords = Curate_UsersContentRecord::model()->findAllByAttributes($attributes);

        $ids = array();
        foreach($entryRecords as $record) {
            $ids[] = $record->getAttribute('entryId');
        }
        
        $entries = $this->getEntriesByIds($ids);
        return $entries;
    }


    /**
     * Gets rank of favorites
     *
     * @param will need some eventually
     */
    public function getRank()
    {
        $rankRes = craft()->db->createCommand()
            ->select('count(entryId) as count, entryId')
            ->from('curate_users_content')
            ->group('entryId')
            ->order('count DESC')
            ->queryAll();
            
        $rank = $ids = array(); 
        foreach($rankRes as $row) 
        {
            $rank[$row['entryId']] = $row['count'];
            $ids[] = $row['entryId'];
        }   
        
        $entries = $this->getEntriesByIds($ids);

        $results = array();
        foreach($entries as $entry) 
        {
            $results[] = array(
                'total' => $rank[$entry['id']],
                'entry' => $entry);
        }
        return $results;
    }

    private function getCount($entryId = '', $userId = '') 
    {
        if (empty($entryId) && empty($userId)) return false;
        $where = array();
        if(!empty($entryId)) $where['entryId'] = $entryId;
        if(!empty($userId))  $where['userId'] = $userId;
    
        $count = craft()->db->createCommand()
            ->select('count(id)')
            ->from('curate_users_content')
            ->where($where)
            ->queryScalar();

        return (int)$count;
    }

    private function getEntriesByIds($ids) {
        // Returns a criteria object for searching on entries
        $criteria = craft()->elements->getCriteria(ElementType::Entry);
        $criteria->id = $ids;
        return $criteria->find();
    }
}

    