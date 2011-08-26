<?php

/**
 * @fixture Test_User
 */
class Unit_Models_Status extends Unit_Models_Base
{
  /**
   * @test
   */
  public function getLatestCommentByUserId()
  {
    $user  = new User();
    $aUser = $user->selectOne("user_name", "test1");
    $this->insertComments($aUser);
    
    $this->eq("comment3", Status::getLatestCommentByUserId($aUser->id));
    
    $this->clear("Status");
  }
  
  /**
   * @test
   */
  public function getCountByUserId()
  {
    $user  = new User();
    $aUser = $user->selectOne("user_name", "test1");
    $this->insertComments($aUser);
    
    $this->eq(3, Status::getCountByUserId($aUser->id));
    
    $this->clear("Status");
  }
  
  /**
   * @test
   */
  public function normalizeComment()
  {
    $comment = <<<COMMENT
test   test
test   test
COMMENT;
    
    $comment = Status::normalizeComment($comment);
    $this->eq("test test test test", $comment);
  }
  
  /**
   * @test
   */
  public function post()
  {
    $comment = <<<COMMENT
test   test
test   test
COMMENT;
    
    $user  = new User();
    $aUser = $user->selectOne("user_name", "test1");
    
    $aStatus = Status::post($aUser->id, $comment);
    $this->eq("test test test test", $aStatus->comment);
    
    $user    = new User();
    $user2   = $user->selectOne("user_name", "test2");
    $comment = "@test2 test";
    $aStatus = Status::post($aUser->id, $comment);
    $this->eq($user2->id, $aStatus->reply_user_id);
    
    $this->clear("Status");
  }
  
  protected function insertComments(User $aUser)
  {
    $status = new Status();
    
    $status->insert(array(
      "user_id"    => $aUser->id,
      "comment"    => "comment1",
      "created_at" => "2008-01-01 00:00:00",
    ));
    
    $status->insert(array(
      "user_id"    => $aUser->id,
      "comment"    => "comment2",
      "created_at" => "2008-02-01 00:00:00",
    ));
    
    $status->insert(array(
      "user_id"    => $aUser->id,
      "comment"    => "comment3",
      "created_at" => "2008-03-01 00:00:00",
    ));
  }
}
