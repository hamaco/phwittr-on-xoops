<?php

class Index_Controllers_User extends Controllers_Application
{
  public function initialize()
  {
    parent::initialize();
    $this->clientId = $this->session->getClientId();
  }
  
  public function finalize()
  {
    if ($this->response->isSuccess() && $this->action !== "logout") {
      $this->submenu->setUser($this->aclUser->id);
    }
  }
  
  public function home()
  {
    $helper = new Helpers_Paginator_Status();
    $this->paginator = $helper->getFriendsAndMyStatuses($this->aclUser->id, $this->GET_VARS);
    $this->view->setName("common");
  }
  
  public function replies()
  {
    $helper = new Helpers_Paginator_Status();
    $this->paginator = $helper->getReplies($this->aclUser->id, $this->GET_VARS);
    $this->view->setName("common");
  }
  
  public function archive()
  {
    $this->noUsername = true;
    
    $helper = new Helpers_Paginator_Status();
    $this->paginator = $helper->getStatuses($this->aclUser->id, $this->GET_VARS);
    $this->view->setName("common");
  }
  
  public function friends()
  {
    $helper = new Helpers_Paginator_Follower();
    $this->paginator = $helper->getFriends($this->aclUser->id, $this->GET_VARS);
  }
  
  public function followers()
  {
    $helper = new Helpers_Paginator_Follower();
    $this->paginator = $helper->getFollowers($this->aclUser->id, $this->GET_VARS);
  }
  
  /**
   * @check_client_id post
   */
  public function settings()
  {
    $this->settingsForm = new Form_Object(new User($this->aclUser->id));
    if (!$this->request->isPost()) return;
    
    $this->settingsForm->apply(
      $this->request->fetchPostValues(), array("user_name", "email", "private_flag")
    );
    
    $result = $this->getLogic("user")->updateSettings($this->settingsForm->getModel());
    
    if ($result->isSuccess()) {
      if ($this->mode === "save") {
        $this->updateUserSession($result->aUser);
        $this->view->setName("settings_save");
      } else {
        $this->view->setName("settings_confirm");
      }
    } else {
      $this->settingsForm->setErrors($result->getErrors());
    }
  }
  
  /**
   * @check_client_id post
   */
  public function password()
  {
    if (!$this->request->isPost()) return;
    
    if ($this->password === null) {
      $this->errors = array("新規パスワードを入力してください");
    } elseif ($this->password !== $this->password2) {
      $this->errors = array("入力が一致しません");
    } else {
      $aUser = new User($this->aclUser->id);
      $aUser->updatePassword($this->password);
      $this->view->setName("password_save");
    }
  }
  
  /**
   * @check_client_id post
   */
  public function picture()
  {
    if (!$this->request->isPost()) return;
    
    if (!isset($_FILES["picture"]) || $_FILES["picture"]["error"] === UPLOAD_ERR_NO_FILE) {
      $this->errors = array("ファイルを選択してください");
    } else {
      $result = $this->getLogic("user")->uploadIcon(
        $this->aclUser->id, file_get_contents($_FILES["picture"]["tmp_name"])
      );
      
      if ($result->isSuccess()) {
        $this->updateUserSession($result->aUser);
        $this->view->setName("picture_save");
      } else {
        $this->errors = $result->getErrors();
      }
    }
  }
  
  /**
   * @check_client_id
   */
  public function destroy()
  {
    $result = $this->getLogic("user")->destroy($this->aclUser->id);
    
    if ($result->isSuccess()) {
      $this->aclUser->logout();
      $this->redirect->uri(get_uri_prefix());
    }
  }
  
  public function logout()
  {
    $this->aclUser->logout();
  }
  
  public function aclForbidden($action)
  {
    if ($action !== "logout") {
      return $this->request->getUri(true);
    }
  }
}
