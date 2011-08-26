<?php

class Logics_User extends Logics_Base
{
  /**
   * @var Imanage
   */
  protected $imanage = null;
  
  public function setImanage(Imanage $imanage)
  {
    $this->imanage = $imanage;
  }
  
  /**
   * @transaction
   *
   * @param User $aUser
   *
   * @return Logics_Result
   */
  public function updateSettings(User $aUser)
  {
    $result = new Logics_Result();
    $result->aUser = $aUser;
    
    if ($errors = $this->validateModel($aUser)) {
      return $result->setErrors($errors);
    }
    
    $aUser->save();
    if (!$aUser->private_flag) {
      $request = new Request();
      if ($requests = $request->select("request_id", $aUser->id)) {
        foreach ($requests as $aRequest) {
          $aFollower = new Follower();
          $aFollower->save(array(
            "user_id"    => $aRequest->user_id,
            "follow_id"  => $aRequest->request_id,
            "created_at" => now()
          ));
          
          $aRequest->delete();
        }
      }
    }
    
    return $result;
  }
  
  /**
   * @param int    $userId   ユーザID
   * @param string $resource 画像データ
   *
   * @return Logics_Result
   */
  public function uploadIcon($userId, $resource)
  {
    $result = new Logics_Result();
    
    if ($this->imanage->validate($resource)) {
      $aUser = new User($userId);
      if ($aUser->isActive()) {
        if ($aUser->image !== DEFAULT_IMAGE_NAME) {
          $this->imanage->delete($aUser->image);
        }
        
        $aUser->save(array("image" => $this->imanage->upload($resource)));
        $result->aUser = $aUser;
      } else {
        throw new Exception_UserNotFound(__METHOD__);
      }
    } else {
      $result->setErrors(array("サイズが大きすぎるか、非対応のフォーマットです"));
    }
    
    return $result;
  }
  
  /**
   * @transaction
   *
   * @param int $userId ユーザID
   *
   * @return Logics_Result
   */
  public function destroy($userId)
  {
    $result = new Logics_Result();
    $aUser  = new User($userId);
    
    if ($aUser->isSelected()) {
      $or = new Sabel_Db_Condition_Or();
      $or->add(C::create(C::EQUAL, "user_id", $aUser->id));
      $or->add(C::create(C::EQUAL, "request_id", $aUser->id));
      $request = new Request();
      $request->delete($or);
      
      $or = new Sabel_Db_Condition_Or();
      $or->add(C::create(C::EQUAL, "user_id", $aUser->id));
      $or->add(C::create(C::EQUAL, "follow_id", $aUser->id));
      $follower = new Follower();
      $follower->delete($or);
      
      $status = new Status();
      $status->delete("user_id", $userId);
      
      $aUser->save(array("delete_flag" => true));
    }
    
    return $result;
  }
}
