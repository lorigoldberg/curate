<?php
namespace Craft;

/**
 * Handles user/entries relationships
 */
class Curate_UserEntriesController extends BaseController
{
    /**
     * Adds a favorite for the currently logged in user
     */
    public function actionSaveUserFave()
    {
       $this->requirePostRequest();
        $this->requireAjaxRequest();

        $userfave = new Curate_UsersContentModel();
        $entryId = craft()->request->getPost('entryId');

        // if there is no userId but there is a session...
        if (empty($userId) && craft()->userSession->isLoggedIn()) {
            $userId = craft()->userSession->getUser()->id;      
        }
        
        $atts = array('entryId' => $entryId, 'userId' => $userId);
        $userfave->setAttributes($atts);
        $success = craft()->curate_usersContent->saveUserFavorite($userfave);

        $response = array('error'=>'', 'success' => 'false');
        
       if ($success) {
          //craft()->user->setNotice(Craft::t('Ingredient saved.'));
            $response['success'] = true;
       } else {
         $response['error'] = 'could not save favorite';
       }
        $this->returnJson($response);

    }
    
    /**
     * Deletes a favorite for a user
     */
    public function actionDeleteUserFave()
    {
        $this->requirePostRequest();
        $this->requireAjaxRequest();

        $response = array('error'=>'', 'success' => 'false');

        $userfave = new Curate_UsersContentModel();
        $entryId = craft()->request->getPost('entryId');

        if(empty($entryId)) {
             $response['error'] = "No entryId given";
        } else {
            // if there is no userId but there is a session...
            if (empty($userId) && craft()->userSession->isLoggedIn()) {
                $userId = craft()->userSession->getUser()->id;      
            }
            if(empty($userId)) $response['error'] = "User ID could not be found";
        }

        if(empty($response['error']))
        {
            $atts = array('entryId' => $entryId, 'userId' => $userId);
            $userfave->setAttributes($atts);
            $success = craft()->curate_usersContent->deleteUserFavorite($userfave);
            
            if ($success) {
                $response['success'] = true;
            } else {
              $response['error'] = 'could not delete favorite';
            }
        }
        $this->returnJson($response);
    }
}


