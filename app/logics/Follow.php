<?php

class Logics_Follow extends Logics_Base
{
  /**
   * @userCache
   *
   * @param int $userId   ユーザID
   * @param int $targetId フォローするユーザのID
   *
   * @return Logics_Result
   */
  public function add($userId, $targetId)
  {
    $result = new Logics_Result();
    $aUser  = new User($userId);
    $target = new User($targetId);
    
    if ($aUser->isActive() && $target->isActive()) {
      if ($target->isProtected()) {
        $request = new Request();
        $request->save(array(
          "user_id"    => $aUser->id,
          "request_id" => $target->id,
          "created_at" => now()
        ));
        
        $result->addType = "request";
      } else {
        $follower = new Follower();
        $follower->save(array(
          "user_id"    => $aUser->id,
          "follow_id"  => $target->id,
          "created_at" => now()
        ));
        
        $result->addType = "follow";
      }
    } else {
      $result->failure();
    }
    
    return $result;
  }
  
  /**
   * @userCache
   *
   * @param int $userId   ユーザID
   * @param int $targetId フォロー中ユーザのID
   *
   * @return Logics_Result
   */
  public function remove($userId, $targetId)
  {
    $result = new Logics_Result();
    $aUser  = new User($userId);
    $target = new User($targetId);
    
    if ($aUser->isSelected() && $target->isSelected()) {
      if (!Follower::isFollowed($userId, $targetId) && $target->isProtected()) {
        $request = new Request();
        $request->setCondition("user_id",    $userId);
        $request->setCondition("request_id", $targetId);
        $request->delete();
        $result->removeType = "request";
      } else {
        $follower = new Follower();
        $follower->setCondition("user_id",   $userId);
        $follower->setCondition("follow_id", $targetId);
        $follower->delete();
        $result->removeType = "follow";
      }
    } else {
      $result->failure();
    }
    
    return $result;
  }
  
  /**
   * @transaction
   * @userCache
   *
   * @param int $userId      リクエストされたユーザのID
   * @param int $requestorId リクエストしたユーザのID
   *
   * @return Logics_Result
   */
  public function accept($userId, $requestorId)
  {
    $result    = new Logics_Result();
    $aUser     = new User($userId);
    $requestor = new User($requestorId);
    
    if ($aUser->isActive() && $requestor->isActive()) {
      $follower = new Follower();
      $follower->save(array(
        "user_id"    => $requestor->id,
        "follow_id"  => $aUser->id,
        "created_at" => now()
      ));
      
      $request = new Request();
      $request->setCondition("user_id",    $requestor->id);
      $request->setCondition("request_id", $aUser->id);
      $request->delete();
    } else {
      $result->failure();
    }
    
    return $result;
  }
  
  /**
   * @param int $userId      リクエストされたユーザのID
   * @param int $requestorId リクエストしたユーザのID
   *
   * @return Logics_Result
   */
  public function deny($userId, $requestorId)
  {
    $result    = new Logics_Result();
    $aUser     = new User($userId);
    $requestor = new User($requestorId);
    
    if ($aUser->isActive() && $requestor->isActive()) {
      $request = new Request();
      $request->setCondition("user_id",    $requestor->id);
      $request->setCondition("request_id", $aUser->id);
      $request->delete();
    } else {
      $result->failure();
    }
    
    return $result;
  }
}
