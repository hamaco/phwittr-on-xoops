<?php

/**
 * @fixture Test_User
 */
class Unit_Logics_Status extends Unit_Logics_Base
{
  /**
   * @test
   */
  public function post()
  {
    $user   = new User();
    $users  = $user->setOrderBy("id")->setLimit(1)->select();
    $aUser  = $users[0];
    $status = $this->getLogic("status");
    
    $result = $status->post($aUser->id, "");
    $this->isTrue($result->isFailure());
    
    $comment = <<<COMMENT
testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest
testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest
testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest
COMMENT;
    
    $result = $status->post($aUser->id, "");
    $this->isTrue($result->isFailure());
    
    $past = strtotime("2000-01-01 00:00:00");
    $aUser->updated_at = date("Y-m-d H:i:s", $past);
    $aUser->save();
    
    $result = $status->post($aUser->id, "Hello World!");
    $this->isTrue($result->isSuccess());
    
    $aUser = new User($aUser->id);
    $this->isTrue(strtotime($aUser->updated_at) > $past);
    
    $this->clear("Status");
  }
}
