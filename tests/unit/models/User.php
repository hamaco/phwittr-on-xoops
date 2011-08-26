<?php

/**
 * @fixture Test_User
 */
class Unit_Models_User extends Unit_Models_Base
{
  /**
   * @test
   */
  public function findByUsername()
  {
    $user1 = User::findByUsername("test1");
    $this->isTrue($user1->isSelected());
    
    $notfound = User::findByUsername("notfound");
    $this->isFalse($notfound->isSelected());
  }
  
  /**
   * @test
   */
  public function findByUsernameAndPassword()
  {
    $user1 = User::findByUsernameAndPassword("test1", "foo");
    $this->isFalse($user1->isSelected());
    
    $user1 = User::findByUsernameAndPassword("test1", "test1");
    $this->isTrue($user1->isSelected());
  }
  
  /**
   * @test
   */
  public function findByEmailAndPassword()
  {
    $user1 = User::findByEmailAndPassword("foo@example.com", "test1");
    $this->isFalse($user1->isSelected());
    
    $user1 = User::findByEmailAndPassword("test1@example.com", "test1");
    $this->isTrue($user1->isSelected());
  }
  
  /**
   * @test
   */
  public function isActive()
  {
    $user1 = User::findByUsername("test1");
    $this->isTrue($user1->isActive());
    
    $user1->save(array("delete_flag" => true));
    
    $user1 = new User($user1->id);
    $this->isFalse($user1->isActive());
    
    $user1->save(array("delete_flag" => false, "act_key" => md5hash()));
    
    $user1 = new User($user1->id);
    $this->isFalse($user1->isActive());
    
    $user1->save(array("act_key" => null));
  }
  
  /**
   * @test
   */
  public function isProtected()
  {
    $user1 = User::findByUsername("test1");
    $this->isFalse($user1->isProtected());
    
    $user1->save(array("private_flag" => true));
    
    $user1 = new User($user1->id);
    $this->isTrue($user1->isProtected());
    
    $user1->save(array("private_flag" => false));
  }
  
  /**
   * @test
   */
  public function updatePassword()
  {
    $user1 = User::findByUsername("test1");
    $this->eq(md5("test1"), $user1->password);
    
    $user1->updatePassword("foo");
    
    $user1 = new User($user1->id);
    $this->eq(md5("foo"), $user1->password);
    
    $user1->updatePassword("test1");
  }
}
