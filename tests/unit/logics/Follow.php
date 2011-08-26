<?php

/**
 * @fixture Test_User
 */
class Unit_Logics_Follow extends Unit_Logics_Base
{
  /**
   * @test
   */
  public function follow()
  {
    $follow = $this->getLogic("follow");
    
    /* invalid user id */
    $result = $follow->add("a", "b");
    $this->isTrue($result->isFailure());
    /* --------------- */
    
    $user1 = $this->getUser("test1");
    $user3 = $this->getUser("test3");
    
    $this->isFalse(Follower::isFollowed($user1->id, $user3->id));
    
    $result = $follow->add($user1->id, $user3->id);
    $this->isTrue($result->isSuccess());
    $this->isTrue(Follower::isFollowed($user1->id,  $user3->id));
    $this->isFalse(Follower::isFollowed($user3->id, $user1->id));
    
    $this->clear("Follower");
  }
  
  /**
   * @test
   */
  public function destroy()
  {
    $follow = $this->getLogic("follow");
    
    $user1 = $this->getUser("test1");
    $user3 = $this->getUser("test3");
    $follow->add($user1->id, $user3->id);
    $this->isTrue(Follower::isFollowed($user1->id, $user3->id));
    
    $follow->remove($user1->id, $user3->id);
    $this->isFalse(Follower::isFollowed($user1->id, $user3->id));
  }
  
  /**
   * @test
   */
  public function request()
  {
    $follow = $this->getLogic("follow");
    $user1  = $this->getUser("test1");
    $user2  = $this->getUser("test2");
    
    $this->isTrue($user2->isProtected());
    
    $follow->add($user1->id, $user2->id);
    $this->isTrue(Request::isRequested($user1->id,  $user2->id));
    $this->isFalse(Follower::isFollowed($user1->id, $user2->id));
    
    $this->clear("Request");
  }
  
  /**
   * @test
   */
  public function accept()
  {
    $follow = $this->getLogic("follow");
    $user1  = $this->getUser("test1");
    $user2  = $this->getUser("test2");
    
    $follow->add($user1->id, $user2->id);
    $this->isTrue(Request::isRequested($user1->id,  $user2->id));
    $this->isFalse(Follower::isFollowed($user1->id, $user2->id));
    
    $follow->accept($user2->id, $user1->id);
    $this->isFalse(Request::isRequested($user1->id, $user2->id));
    $this->isTrue(Follower::isFollowed($user1->id,  $user2->id));
    
    $this->clear("Follower");
    $this->clear("Request");
  }
  
  /**
   * @test
   */
  public function deny()
  {
    $follow = $this->getLogic("follow");
    $user1  = $this->getUser("test1");
    $user2  = $this->getUser("test2");
    
    $follow->add($user1->id, $user2->id);
    $this->isTrue(Request::isRequested($user1->id,  $user2->id));
    $this->isFalse(Follower::isFollowed($user1->id, $user2->id));
    
    $follow->deny($user2->id, $user1->id);
    $this->isFalse(Request::isRequested($user1->id, $user2->id));
    $this->isFalse(Follower::isFollowed($user1->id, $user2->id));
    
    $this->clear("Follower");
    $this->clear("Request");
  }
}
