<?php

class Views_Submenu extends Sabel_ValueObject
{
  const TYPE_DEFAULT = 1;
  const TYPE_SELF    = 2;
  const TYPE_OTHERS  = 3;
  
  public function __construct()
  {
    $this->templateDir = dirname(__FILE__) . DS . "submenu";
  }
  
  public function type($type)
  {
    if ($type >= 1 && $type <= 3) {
      $this->type = $type;
    } else {
      $message = __METHOD__ . "() invalid user type.";
      throw new Sabel_Exception_Runtime($message);
    }
    
    return $this;
  }
  
  public function setUser($userId, $type = self::TYPE_SELF)
  {
    $this->userId = $userId;
    $this->type($type);
    
    return $this;
  }
  
  public function rendering(Sabel_View_Renderer $renderer)
  {
    switch ($this->type) {
      case self::TYPE_SELF:
        return $this->myContents($renderer);
      case self::TYPE_OTHERS:
        return $this->otherContents($renderer);
      default:
        return $this->defaultContents($renderer);
    }
  }
  
  protected function myContents($renderer)
  {
    if ($this->userId === null) {
      $message = __METHOD__ . "() muse set userId with setUser().";
      throw new Sabel_Exception_Runtime($message);
    }
    
    $userId = $this->userId;
    $aUser = new User($userId);
    $userData = $this->getUserData();
    
    /*
    if ($aUser->isProtected()) {
      $userData->requestCount = Request::getRequestsCount($userId);
    }
     */
    
    // $userData->name  = $aUser->user_name;
    $userData->name  = $aUser->getName();
    $userData->image = $aUser->image;
    $this->userData  = $userData;
    
    $contents = file_get_contents($this->templateDir . DS . "user.tpl");
    return $renderer->rendering($contents, $this->values);
  }
  
  protected function otherContents($renderer)
  {
    if ($this->userId === null) {
      $message = __METHOD__ . "() muse set userId with setUser().";
      throw new Sabel_Exception_Runtime($message);
    }
    
    $aUser = new User($this->userId);
    $this->userData = $this->getUserData();
    $this->userData->name = $aUser->getName();
    
    $contents = file_get_contents($this->templateDir . DS . "others.tpl");
    return $renderer->rendering($contents, $this->values);
  }
  
  protected function defaultContents($renderer)
  {
    $contents = file_get_contents($this->templateDir . DS . "default.tpl");
    return $renderer->rendering($contents, $this->values);
  }
  
  protected function getUserData()
  {
    $userId = $this->userId;
    $cache  = UserCache::getInstance();
    $data   = $cache->read($userId);
    
    if ((ENVIRONMENT & PRODUCTION) > 0 && !$data) {
      list ($fp) = $cache->lock($userId);
      $data = $this->_getUserData($userId);
      $cache->write($fp, $data);
    }
    
    if (!$data) {
      $data = $this->_getUserData($userId);
    }
    
    return Sabel_ValueObject::fromArray(array(
      "userId"         => $userId,
      "friends"        => Follower::getFriends($userId, FRIENDS_ICON_LIMIT + 1),
      "friendsCount"   => $data["friends"],
      "followersCount" => $data["followers"],
      "statusesCount"  => $data["statuses"],
      "latestComment"  => $data["comment"]
    ));
  }
  
  protected function _getUserData($userId)
  {
    return array(
      "friends"   => Follower::getFriendsCount($userId),
      "followers" => Follower::getFollowersCount($userId),
      "statuses"  => Status::getCountByUserId($userId),
      "comment"   => Status::getLatestCommentByUserId($userId),
    );
  }
}
