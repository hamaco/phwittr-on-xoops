<?php

/**
 * @fixture Test_User Test_Follower
 */
class Unit_Models_Follower extends Unit_Models_Base
{
  /**
   * @test
   */
  public function getFriends()
  {
    $user2 = $this->getUser("test2");
    $friends = Follower::getFriends($user2->id, 100);
    
    $this->eq(2, count($friends));
    $this->eq("test1", $friends[0]->User->user_name);
    $this->eq("test3", $friends[1]->User->user_name);
  }
  
  /**
   * @test
   */
  public function getFriendIds()
  {
    $user2 = $this->getUser("test2");
    $ids = Follower::getFriendIds($user2->id);
    
    $this->eq(2, count($ids));
    $this->eq($this->getUser("test1")->id, $ids[0]);
    $this->eq($this->getUser("test3")->id, $ids[1]);
  }
  
  /**
   * @test
   */
  public function getFriendsCount()
  {
    $user2 = $this->getUser("test2");
    $this->eq(2, Follower::getFriendsCount($user2->id));
    
    $user3 = $this->getUser("test3");
    $this->eq(1, Follower::getFriendsCount($user3->id));
  }
  
  /**
   * @test
   */
  public function getFollowersCount()
  {
    $user2 = $this->getUser("test2");
    $this->eq(0, Follower::getFollowersCount($user2->id));
    
    $user3 = $this->getUser("test3");
    $this->eq(2, Follower::getFollowersCount($user3->id));
  }
  
  /**
   * @test
   */
  public function isFollowed()
  {
    $user1 = $this->getUser("test1");
    $user2 = $this->getUser("test2");
    $user3 = $this->getUser("test3");
    
    $this->isFalse(Follower::isFollowed($user1->id, $user2->id));
    $this->isTrue(Follower::isFollowed($user1->id,  $user3->id));
    $this->isTrue(Follower::isFollowed($user2->id,  $user1->id));
    $this->isTrue(Follower::isFollowed($user2->id,  $user3->id));
    $this->isTrue(Follower::isFollowed($user3->id,  $user1->id));
    $this->isFalse(Follower::isFollowed($user3->id, $user2->id));
  }
}
