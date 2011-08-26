<?php

class Fixture_Test_Follower extends Sabel_Test_Fixture
{
  protected $modelName = "Follower";
  
  public function upFixture()
  {
    $user = new User();
    for ($i = 1; $i <= 10; $i++) {
      ${"user{$i}"} = $user->selectOne("user_name", "test{$i}");
    }
    
    $this->insert(array(
      "user_id"    => $user1->id,
      "follow_id"  => $user3->id,
      "created_at" => "2008-01-01 00:00:00",
    ));
    
    $this->insert(array(
      "user_id"    => $user3->id,
      "follow_id"  => $user1->id,
      "created_at" => "2008-02-01 00:00:00",
    ));
    
    $this->insert(array(
      "user_id"    => $user2->id,
      "follow_id"  => $user1->id,
      "created_at" => "2008-03-01 00:00:00",
    ));
    
    $this->insert(array(
      "user_id"    => $user2->id,
      "follow_id"  => $user3->id,
      "created_at" => "2008-04-01 00:00:00",
    ));
    
    for ($i = 4; $i <= 9; $i++) {
      $this->insert(array(
        "user_id"    => $user10->id,
        "follow_id"  => ${"user{$i}"}->id,
        "created_at" => "2008-10-0{$i} 00:00:00",
      ));
      
      $this->insert(array(
        "user_id"    => ${"user{$i}"}->id,
        "follow_id"  => $user10->id,
        "created_at" => "2008-0{$i}-10 00:00:00",
      ));
    }
  }
  
  public function downFixture()
  {
    $this->deleteAll();
  }
}
