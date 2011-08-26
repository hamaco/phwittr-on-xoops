<?php

class Index_Controllers_Login extends Controllers_Application
{
  public function initialize()
  {
    parent::initialize();
    if ($this->aclUser->isAuthenticated()) {
      $this->redirect->to("c: user, a: home");
    }
    
    $this->isLoginPage = true;
  }
  
  /**
   * @httpMethod post
   *
   * @check username_or_email required
   * @check password required
   */
  public function doLogin()
  {
    if ($this->validator->hasError()) {
      $this->view->setName("prepare");
    } else {
      if (strpos($this->username_or_email, "@") === false) {
        $aUser = User::findByUsernameAndPassword($this->username_or_email, $this->password);
      } else {
        $aUser = User::findByEmailAndPassword($this->username_or_email, $this->password);
      }
      
      if ($aUser->isActive()) {
        $this->login($aUser, true);
      } else {
        $this->errors = array("ユーザー名/メールアドレス、パスワードの組み合わせが間違っています");
        $this->view->setName("prepare");
      }
    }
  }
}
