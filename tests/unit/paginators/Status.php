<?php

/**
 * @fixture Test_User Test_Follower Test_Status
 */
class Unit_Paginators_Status extends Unit_Paginators_Base
{
  /**
   * @test
   */
  public function getStatuses()
  {
    $user1 = $this->getUser("test1");
    $user3 = $this->getUser("test3");
    
    $helper = new Helpers_Paginator_Status();
    $paginator = $helper->getStatuses($user1->id, array(), 5);
    
    $this->eq(18, $paginator->count);
    
    $items = $paginator->results;
    $this->eq(5, count($items));
    
    $this->eq("2008-01-09 06:00:00", $items[0]->created_at);
    $this->eq($user3->id, $items[0]->reply_user_id);
    
    $this->eq("2008-01-09 00:00:00", $items[1]->created_at);
    $this->eq(null, $items[1]->reply_user_id);
    
    $this->eq("2008-01-08 06:00:00", $items[2]->created_at);
    $this->eq($user3->id, $items[2]->reply_user_id);
    
    // ------------------------------------------------------
    
    $paginator = $helper->getStatuses($user1->id, array("page" => 2), 5);
    
    $items = $paginator->results;
    $this->eq("2008-01-07 00:00:00", $items[0]->created_at);
    $this->eq(null, $items[0]->reply_user_id);
    
    $this->eq("2008-01-06 06:00:00", $items[1]->created_at);
    $this->eq($user3->id, $items[1]->reply_user_id);
    
    // ------------------------------------------------------
    
    $paginator = $helper->getStatuses($user1->id, array("page" => 4), 5);
    
    $items = $paginator->results;
    $this->eq(3, count($items));
  }
  
  /**
   * @test
   */
  public function getFriendsAndMyStatuses()
  {
    $user1 = $this->getUser("test1");
    $user3 = $this->getUser("test3");
    
    $helper = new Helpers_Paginator_Status();
    $paginator = $helper->getFriendsAndMyStatuses($user1->id, array(), 5);
    
    $this->eq(36, $paginator->count);
    
    $items = $paginator->results;
    
    $this->eq("2008-01-09 18:00:00", $items[0]->created_at);
    $this->eq($user3->id, $items[0]->user_id);
    $this->eq($user1->id, $items[0]->reply_user_id);
    
    $this->eq("2008-01-09 12:00:00", $items[1]->created_at);
    $this->eq($user3->id, $items[1]->user_id);
    $this->eq(null, $items[1]->reply_user_id);
    
    $this->eq("2008-01-09 06:00:00", $items[2]->created_at);
    $this->eq($user1->id, $items[2]->user_id);
    $this->eq($user3->id, $items[2]->reply_user_id);
    
    $this->eq("2008-01-09 00:00:00", $items[3]->created_at);
    $this->eq($user1->id, $items[3]->user_id);
    $this->eq(null, $items[3]->reply_user_id);
    
    $this->eq("2008-01-08 18:00:00", $items[4]->created_at);
    $this->eq($user3->id, $items[4]->user_id);
    $this->eq($user1->id, $items[4]->reply_user_id);
    
    // ------------------------------------------------------
    
    $paginator = $helper->getFriendsAndMyStatuses($user1->id, array("page" => 7), 5);
    $this->eq(5, count($paginator->results));
    
    // ------------------------------------------------------
    
    $paginator = $helper->getFriendsAndMyStatuses($user1->id, array("page" => 8), 5);
    
    $items = $paginator->results;
    $this->eq(1, count($items));
    
    $this->eq("2008-01-01 00:00:00", $items[0]->created_at);
    $this->eq($user1->id, $items[0]->user_id);
    $this->eq(null, $items[0]->reply_user_id);
  }
  
  /**
   * @test
   */
  public function getReplies()
  {
    $user1 = $this->getUser("test1");
    $user3 = $this->getUser("test3");
    
    $helper = new Helpers_Paginator_Status();
    $paginator = $helper->getReplies($user1->id, array(), 5);
    
    $this->eq(9, $paginator->count);
    
    // ------------------------------------------------------
    
    $paginator = $helper->getReplies($user1->id, array("page" => 2), 5);
    $items = $paginator->results;
    
    $this->eq(4, count($items));
    
    $this->eq("2008-01-04 18:00:00", $items[0]->created_at);
    $this->eq($user3->id, $items[0]->user_id);
    $this->eq($user1->id, $items[0]->reply_user_id);
    
    $this->eq("2008-01-03 18:00:00", $items[1]->created_at);
    $this->eq("2008-01-02 18:00:00", $items[2]->created_at);
    $this->eq("2008-01-01 18:00:00", $items[3]->created_at);
  }
  
  /**
   * @test
   */
  public function getPublicStatuses()
  {
    $user1 = $this->getUser("test1");
    $user2 = $this->getUser("test2");
    $user3 = $this->getUser("test3");
    
    $helper = new Helpers_Paginator_Status();
    
    $paginator = $helper->getPublicStatuses(array(), 5);
    $this->eq(36, $paginator->count);
    
    $user2->private_flag = false;
    $user2->save();
    
    $paginator = $helper->getPublicStatuses(array(), 5);
    $this->eq(45, $paginator->count);
    
    $user2->private_flag = true;
    $user2->save();
  }
}
