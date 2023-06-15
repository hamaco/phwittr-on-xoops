<?php

class Index_Controllers_Users extends Controllers_Application
{
  public function initialize()
  {
    $uri = $this->request->getUri();
    $parts = explode("/", $uri);
    
    if ($this->aUser = $this->fetchModel("User", array("user_name" => $parts[0]))) {
      if ($this->aUser->isActive()) {
        $this->submenu->setUser($this->aUser->id, Views_Submenu::TYPE_OTHERS);
      } else {
        $this->notFound();
      }
    }
  }
  
  public function index()
  {
    if ($this->aclUser->name === $this->aUser->user_name) { // self
      $this->redirect->to("n: default, c: user, a: home");
    } else {
      $this->home();
    }
  }
  
  protected function home()
  {
    $protected = $this->aUser->isProtected();
    $this->clientId = $this->session->getClientId();
    
    if ($authenticated = $this->aclUser->isAuthenticated()) {
      $this->isFollowed = Follower::isFollowed($this->aclUser->id, $this->aUser->id);
      if ($this->isFollowed) $protected = false;
    }
    
    if ($protected) {
      if ($authenticated) {
        $this->isRequested = Request::isRequested($this->aclUser->id, $this->aUser->id);
      }
      $this->view->setName("protected");
    } else {
      $helper = new Helpers_Paginator_Status();
      $this->paginator = $helper->getStatuses($this->aUser->id, $this->GET_VARS, 50);
      $this->view->setName("home");
    }
  }
  
  public function friends()
  {
    $helper = new Helpers_Paginator_Follower();
    $this->paginator = $helper->getFriends($this->aUser->id, $this->GET_VARS);
  }
  
  public function followers()
  {
    $helper = new Helpers_Paginator_Follower();
    $this->paginator = $helper->getFollowers($this->aUser->id, $this->GET_VARS);
  }
}
