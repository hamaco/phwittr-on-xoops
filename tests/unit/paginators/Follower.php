<?php

/**
 * @fixture Test_User Test_Follower
 */
class Unit_Paginators_Follower extends Unit_Paginators_Base
{
  /**
   * @test
   */
  public function getFriends()
  {
    $user10 = $this->getUser("test10");
    $helper = new Helpers_Paginator_Follower();
    $paginator = $helper->getFriends($user10->id, array(), 5);
    
    $this->eq(6, $paginator->count);
    
    $items = $paginator->results;
    $this->eq(5, count($items));
    
    $user9 = $this->getUser("test9");
    $this->eq($user9->id, $items[0]->follow_id);
    $this->eq($user9->id, $items[0]->User->id);
    $this->eq("2008-10-09 00:00:00", $items[0]->created_at);
    
    $user8 = $this->getUser("test8");
    $this->eq($user8->id, $items[1]->follow_id);
    $this->eq($user8->id, $items[1]->User->id);
    $this->eq("2008-10-08 00:00:00", $items[1]->created_at);
    
    // ------------------------------------------------------
    
    $paginator = $helper->getFriends($user10->id, array("page" => 2), 5);
    
    $items = $paginator->results;
    $this->eq(1, count($items));
    
    $user4 = $this->getUser("test4");
    $this->eq($user4->id, $items[0]->follow_id);
    $this->eq($user4->id, $items[0]->User->id);
    $this->eq("2008-10-04 00:00:00", $items[0]->created_at);
  }
  
  /**
   * @test
   */
  public function getFollowers()
  {
    $user10 = $this->getUser("test10");
    $helper = new Helpers_Paginator_Follower();
    $paginator = $helper->getFollowers($user10->id, array(), 5);
    
    $this->eq(6, $paginator->count);
    
    $items = $paginator->results;
    $this->eq(5, count($items));
    
    $user9 = $this->getUser("test9");
    $this->eq($user9->id, $items[0]->user_id);
    $this->eq($user9->id, $items[0]->User->id);
    $this->eq("2008-09-10 00:00:00", $items[0]->created_at);
    
    $user8 = $this->getUser("test8");
    $this->eq($user8->id, $items[1]->user_id);
    $this->eq($user8->id, $items[1]->User->id);
    $this->eq("2008-08-10 00:00:00", $items[1]->created_at);
    
    // ------------------------------------------------------
    
    $paginator = $helper->getFollowers($user10->id, array("page" => 2), 5);
    
    $items = $paginator->results;
    $this->eq(1, count($items));
    
    $user4 = $this->getUser("test4");
    $this->eq($user4->id, $items[0]->user_id);
    $this->eq($user4->id, $items[0]->User->id);
    $this->eq("2008-04-10 00:00:00", $items[0]->created_at);
  }
}
