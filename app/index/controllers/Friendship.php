<?php

class Index_Controllers_Friendship extends Controllers_Application
{
  /**
   * @var Logics_Follow
   */
  protected $follow = null;
  
  public function initialize()
  {
    $this->follow = $this->getLogic("follow");
  }
  
  /**
   * @httpMethod post
   *
   * @check_client_id
   * @check id required nNumber
   */
  public function create()
  {
    if ($success = !$this->validator->hasError()) {
      $success = $this->follow->add($this->aclUser->id, $this->id)->isSuccess();
    }
    
    if ($success) {
      $aUser = new User($this->uid);
      $this->redirect->to("n: users, c: {$aUser->uname}, a: ");
    } else {
      $this->badRequest();
    }
  }
  
  /**
   * @httpMethod post
   *
   * @check_client_id
   * @check id required nNumber
   */
  public function destroy()
  {
    if ($success = !$this->validator->hasError()) {
      $success = $this->follow->remove($this->aclUser->id, $this->id)->isSuccess();
    }
    
    if ($success) {
      if (!$this->isAjaxRequest()) {
        $aUser = new User($this->id);
        $this->redirect->to("n: users, c: {$aUser->user_name}, a: ");
      }
    } else {
      $this->badRequest();
    }
  }
  
  public function requests()
  {
    $this->submenu->setUser($this->aclUser->id);
    $this->requests = Request::getRequestsByUserId($this->aclUser->id);
    
    if (count($this->requests) === 0) {
      $this->redirect->to("c: user, a: home");
    } else {
      $this->clientId = $this->session->getClientId();
    }
  }
  
  /**
   * @httpMethod post
   *
   * @check_client_id
   * @check id required nNumber
   */
  public function accept()
  {
    if ($success = !$this->validator->hasError()) {
      $success = $this->follow->accept($this->aclUser->id, $this->id)->isSuccess();
    }
    
    if (!$success) $this->badRequest();
  }
  
  /**
   * @httpMethod post
   *
   * @check_client_id
   * @check id required nNumber
   */
  public function deny()
  {
    if ($success = !$this->validator->hasError()) {
      $success = $this->follow->deny($this->aclUser->id, $this->id)->isSuccess();
    }
    
    if (!$success) $this->badRequest();
  }
}
