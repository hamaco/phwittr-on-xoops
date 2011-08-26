<?php

class Fixture_Test_Request extends Sabel_Test_Fixture
{
  protected $modelName = "Request";
  
  public function upFixture()
  {
    $user  = new User();
    $user1 = $user->selectOne("user_name", "test1");
    $user2 = $user->selectOne("user_name", "test2");
    $user3 = $user->selectOne("user_name", "test3");
    
    $this->insert(array(
      "user_id"    => $user1->id,
      "request_id" => $user2->id,
      "created_at" => "2008-01-01 00:00:00",
    ));
    
    $this->insert(array(
      "user_id"    => $user3->id,
      "request_id" => $user2->id,
      "created_at" => "2008-02-01 00:00:00",
    ));
  }
  
  public function downFixture()
  {
    $this->deleteAll();
  }
}
