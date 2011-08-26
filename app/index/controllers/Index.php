<?php

class Index_Controllers_Index extends Controllers_Application
{
  public function index()
  {
    l('hoge');
    if ($this->aclUser->isAuthenticated()) {
      $this->redirect->to("c: user, a: home");
    }
  }
  
  public function timeline()
  {
    // これしょぼす
    $this->rsslink = '<link rel="alternate" type="application/rss+xml" '
                   . 'title="Phwittr public timeline" '
                   . 'href="http://' . $_SERVER["SERVER_NAME"] . uri("a: rss") . '">';
    
    $helper = new Helpers_Paginator_Status();
    $this->paginator = $helper->getPublicStatuses($this->GET_VARS);
    
    if ($this->aclUser->isAuthenticated()) {
      $this->clientId = $this->session->getClientId();
      // $this->isProtected = $this->aclUser->isProtected;
      $this->isProtected = false;
      $this->submenu->setUser($this->aclUser->id);
    }
  }
  
  public function rss()
  {
    $this->layout = false;
    $this->response->setHeader("Content-Type", "application/rss+xml; charset=utf-8");
    
    $rss = new Sabel_Rss_Writer();
    $rss->setInfo(array("title" => "Phwittr public timeline", "language" => "ja"));
    
    $helper = new Helpers_Paginator_Status();
    $paginator = $helper->getPublicStatuses(array());
    
    if ($paginator->results) {
      foreach ($paginator->results as $item) {
        $rss->addItem(array(
          "uri"     => "http://" . $_SERVER["SERVER_NAME"] . uri("a: status, param: {$item->id}"),
          "title"   => mb_strimwidth($item->User->user_name . ": " . $item->comment, 0, 35, "..."),
          "content" => $item->User->user_name . ": " . $item->comment,
          "date"    => $item->created_at
        ));
      }
    }
    
    $this->contents = $rss->output();
  }
  
  /**
   * @check param required nNumber
   */
  public function status()
  {
    $this->aStatus = $this->fetchModel("Status", $this->param);
    if (!$this->aStatus) return;
    
    $isSelf = false;
    $this->aUser = $aUser = new User($this->aStatus->user_id);
    // $this->protected = $aUser->isProtected();
    $this->protected = false;
    
    if ($this->aclUser->isAuthenticated()) {
      if ($this->aclUser->id === $aUser->uid) {
        $isSelf = true;
        $this->protected = false;
      } elseif (Follower::isFollowed($this->aclUser->id, $aUser->uid)) {
        $this->protected = false;
      }
    }
    
    if ($isSelf) {
      $this->submenu->setUser($this->aclUser->id);
    } else {
      $this->submenu->setUser($aUser->uid, Views_Submenu::TYPE_OTHERS);
    }
  }
}
