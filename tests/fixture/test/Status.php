<?php

class Fixture_Test_Status extends Sabel_Test_Fixture
{
  protected $modelName = "Status";
  
  public function upFixture()
  {
    $user  = new User();
    $user1 = $user->selectOne("user_name", "test1");
    $user2 = $user->selectOne("user_name", "test2");
    $user3 = $user->selectOne("user_name", "test3");
    
    for ($i = 1; $i < 10; $i++) {
      $this->insert(array(
        "user_id"    => $user1->id,
        "comment"    => "comment{$i}",
        "created_at" => "2008-01-0{$i} 00:00:00",
      ));
    }
    
    for ($i = 1; $i < 10; $i++) {
      $this->insert(array(
        "user_id"       => $user1->id,
        "reply_user_id" => $user3->id,
        "comment"       => "@test3 comment{$i}",
        "created_at"    => "2008-01-0{$i} 06:00:00",
      ));
    }
    
    for ($i = 1; $i < 10; $i++) {
      $this->insert(array(
        "user_id"       => $user3->id,
        "comment"       => "comment{$i}",
        "created_at"    => "2008-01-0{$i} 12:00:00",
      ));
    }
    
    for ($i = 1; $i < 10; $i++) {
      $this->insert(array(
        "user_id"       => $user3->id,
        "reply_user_id" => $user1->id,
        "comment"       => "@test1 comment{$i}",
        "created_at"    => "2008-01-0{$i} 18:00:00",
      ));
    }
    
    for ($i = 1; $i < 10; $i++) {
      $this->insert(array(
        "user_id"       => $user2->id,
        "comment"       => "comment{$i}",
        "created_at"    => "2008-01-0{$i} 23:59:59",
      ));
    }
  }
  
  public function downFixture()
  {
    $this->deleteAll();
  }
}
