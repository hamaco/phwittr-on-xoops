<?php

class Controllers_Application extends Sabel_Controller_Page
{
  public function initialize()
  {
    $root = XCube_Root::getSingleton();
    $user = $root->mContext->mXoopsUser;
    if ($root->mContext->mUser->isInRole("Site.RegisteredUser") && !$this->aclUser->isAuthenticated()) {
      $user = $root->mContext->mXoopsUser;
      $this->login($user, true);
    }

    // Fuckin XOOPS!!!
    // global $xoopsTpl;
    // $xoopsTpl->assign("xoops_module_header2", '<script type="text/javascript" src="'.linkto("js/Sabel.js").'"></script>');
  }

  protected function login($aUser, $back = false)
  {
    $this->updateUserSession($aUser);
    
    if ($back) {
      $this->aclUser->login("n: default, c: user, a: home", "user");
    } else {
      $this->aclUser->authenticate("user");
      $this->redirect->to("n: default, c: user, a: home");
    }
  }
  
  protected function updateUserSession($aUser)
  {
    $aclUser = $this->aclUser;
    
    $aclUser->id    =(int) $aUser->uid();
    $aclUser->name  = $aUser->uname();
    $aclUser->image = $aUser->get("user_avatar");
    $aclUser->isProtected = false;//$aUser->isProtected();
  }
}
