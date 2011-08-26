<?php

/**
 * @fixture Test_User Test_Request
 */
class Unit_Models_Request extends Unit_Models_Base
{
  /**
   * @test
   */
  public function getRequestsByUserId()
  {
    $user2 = $this->getUser("test2");
    $requests = Request::getRequestsByUserId($user2->id);
    
    $this->eq(2, count($requests));
    $this->eq("test3", $requests[0]->User->user_name);
    $this->eq("test1", $requests[1]->User->user_name);
  }
  
  /**
   * @test
   */
  public function getRequestsCount()
  {
    $user2 = $this->getUser("test2");
    $this->eq(2, Request::getRequestsCount($user2->id));
  }
  
  /**
   * @test
   */
  public function isRequested()
  {
    $user1 = $this->getUser("test1");
    $user2 = $this->getUser("test2");
    $user3 = $this->getUser("test3");
    
    $this->isTrue(Request::isRequested($user1->id,  $user2->id));
    $this->isTrue(Request::isRequested($user3->id,  $user2->id));
    $this->isFalse(Request::isRequested($user2->id, $user1->id));
    $this->isFalse(Request::isRequested($user2->id, $user3->id));
  }
}
