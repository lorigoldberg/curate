<?php
namespace Craft;

class CurateVariable        
{

    public function getFavesByUserId($userId = '')
    {
        return craft()->curate_usersContent->getFavesByUserId($userId);
    }


    public function isFavedByUser($entryId, $userId = '')
    {   
        if (empty($entryId)) {
            return false;
        }
        
        return craft()->curate_usersContent->isFavedByUser($entryId, $userId);
    }


    public function getRank()
    {   
        
        return craft()->curate_usersContent->getRank();
    }
}