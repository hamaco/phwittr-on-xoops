<?php

class Index_Controllers_Register extends Controllers_Application
{
  /**
   * @var Logics_Register
   */
  protected $register = null;
  
  public function initialize()
  {
    $this->register = $this->getLogic("register");
  }
  
  public function prepare()
  {
    $this->userForm = new Form_Object("User");
  }
  
  /**
   * @httpMethod post
   */
  public function doRegister()
  {
    $this->userForm = new Form_Object("User");
    $this->userForm->apply(
      $this->request->fetchPostValues(), array("user_name", "password", "email")
    );
    
    $result = $this->register->preregister($this->userForm->getModel());
    if ($result->isFailure()) {
      $this->userForm->setErrors($result->getErrors());
      $this->view->setName("prepare");
    }
  }
  
  /**
   * @httpMethod get
   *
   * @check param required
   */
  public function auth()
  {
    $this->login($this->register->register($this->param)->aUser);
  }
}
