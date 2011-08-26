<?php

/**
 * @fixture Test_User
 */
class Unit_Logics_User extends Unit_Logics_Base
{
  /**
   * @test
   */
  public function updateSettings()
  {
    $logic = $this->getLogic("user");
    $aUser = $this->getUser("test3");
    
    $aUser->user_name = "test1";  // duplicate
    $result = $logic->updateSettings($aUser);
    
    $this->isTrue($result->isFailure());
    $this->eq(1, count($result->getErrors()));
    
    // ----------------------------------------------
    
    $aUser->user_name = "test1"; // duplicate
    $aUser->email = "test1@example.com"; // duplicate
    $result = $logic->updateSettings($aUser);
    
    $this->isTrue($result->isFailure());
    $this->eq(2, count($result->getErrors()));
    
    // ----------------------------------------------
    
    $aUser->user_name = "test3";
    $aUser->email = "sabel@example.com";
    $result = $logic->updateSettings($aUser);
    
    $this->isTrue($result->isSuccess());
    
    $updated = $this->getUser("test3");
    $this->eq($aUser->id, $updated->id);
    $this->eq("sabel@example.com", $updated->email);
  }
  
  /**
   * @test
   */
  public function uploadIcon()
  {
    $user1 = $this->getUser("test1");
    $logic = $this->getLogic("user");
    
    $result = $logic->uploadIcon($user1->id, null);
    $this->isTrue($result->isFailure());
    $this->isTrue($result->hasError());
    
    try {
      $result = $logic->uploadIcon("a", "image data");
      $this->fail();
    } catch (Exception_UserNotFound $unf) {
    }
    
    $this->eq(DEFAULT_IMAGE_NAME, $user1->image);
    $result = $logic->uploadIcon($user1->id, "image data");
    $this->isTrue($result->isSuccess());
    
    $this->eq(md5("image data") . ".gif", $this->getUser("test1")->image);
  }
  
  /**
   * @test
   */
  public function moveRequestsToFollower()
  {
    $user2 = $this->getUser("test2");
    $this->isTrue($user2->isProtected());
    
    $user1 = $this->getUser("test1");
    $user3 = $this->getUser("test3");
    
    $request = new Request();
    $request->insert(array(
      "user_id"    => $user1->id,
      "request_id" => $user2->id,
      "created_at" => now()
    ));
    
    $request->insert(array(
      "user_id"    => $user3->id,
      "request_id" => $user2->id,
      "created_at" => now()
    ));
    
    $this->isTrue(Request::isRequested($user1->id,  $user2->id));
    $this->isTrue(Request::isRequested($user3->id,  $user2->id));
    $this->isFalse(Follower::isFollowed($user1->id, $user2->id));
    $this->isFalse(Follower::isFollowed($user3->id, $user2->id));
    
    $user2->private_flag = false;
    $this->getLogic("user")->updateSettings($user2);
    $this->isFalse($user2->isProtected());
    
    $this->isFalse(Request::isRequested($user1->id,  $user2->id));
    $this->isFalse(Request::isRequested($user3->id,  $user2->id));
    $this->isTrue(Follower::isFollowed($user1->id, $user2->id));
    $this->isTrue(Follower::isFollowed($user3->id, $user2->id));
    
    $this->clear("Request");
    $this->clear("Follower");
  }
}
