<?php

/**
 * @fixture Test_User
 */
class Unit_Logics_Register extends Unit_Logics_Base
{
  /**
   * @test
   */
  public function preRegister()
  {
    $register = $this->getLogic("register");
    
    /*  empty user_name, email */
    $aUser  = new User();
    $result = $register->preregister($aUser);
    $this->isTrue($result->isFailure());
    $this->eq(3, count($result->getErrors()));
    /*  ---------------------- */
    
    /*  conflict user_name */
    $aUser = new User();
    $aUser->user_name = "test1";
    $aUser->password = "test";
    $aUser->email = "abc@example.com";
    $result = $register->preregister($aUser);
    $this->isTrue($result->isFailure());
    $this->eq(1, count($result->getErrors()));
    /*  ------------------ */
    
    $aUser = new User();
    $aUser->user_name = "user";
    $aUser->password = "user";
    $aUser->email = "user@example.com";
    $result = $register->preregister($aUser);
    
    $this->isTrue($result->isSuccess());
    $this->eq(32, strlen($aUser->act_key));  // activation key(md5)
  }
  
  /**
   * @test
   */
  public function register()
  {
    $register = $this->getLogic("register");
    
    try {
      $register->register("foobar");
      $this->fail();
    } catch (Exception_UserNotFound $unf) {
    }
    
    $aUser = new User();
    $aUser->user_name = "sabel";
    $aUser->password = "sabel";
    $aUser->email = "sabel@example.com";
    $register->preregister($aUser);
    
    $this->isNotNull($aUser->act_key);
    
    $result = $register->register($aUser->act_key);
    $aUser  = $result->aUser;
    
    $this->isNull($aUser->act_key);
  }
}
