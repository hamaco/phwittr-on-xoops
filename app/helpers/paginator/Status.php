<?php

class Helpers_Paginator_Status extends Sabel_Object
{
  const DEFAULT_ITEM_LIMIT = 20;
  
  public function getStatuses($userId, $params, $limit = null)
  {
    if ($limit === null) {
      $limit = self::DEFAULT_ITEM_LIMIT;
    }
    
    $join = new Sabel_Db_Join("Status");
    $paginator = new Paginator($join->add("Users"));
    $paginator->setCondition("Users.uid", $userId);
    $paginator->setDefaultOrder("Status.created_at", "desc");
    
    return $paginator->build($limit, $params);
  }
  
  public function getFriendsAndMyStatuses($userId, $params, $limit = null)
  {
    if ($limit === null) {
      $limit = self::DEFAULT_ITEM_LIMIT;
    }
    
    $ids = Follower::getFriendIds($userId);
    $ids[] = $userId;
    
    $join = new Sabel_Db_Join("Status");
    $paginator = new Paginator($join->add("Users"));
    $paginator->setCondition(C::create(C::IN, "Users.uid", $ids));
    $paginator->setDefaultOrder("Status.created_at", "desc");
    
    return $paginator->build($limit, $params);
  }
  
  public function getReplies($userId, $params, $limit = null)
  {
    if ($limit === null) {
      $limit = self::DEFAULT_ITEM_LIMIT;
    }
    
    $join = new Sabel_Db_Join("Status");
    $paginator = new Paginator($join->add("Users"));
    $paginator->setCondition("Status.reply_user_id", $userId);
    $paginator->setDefaultOrder("Status.created_at", "desc");
    
    return $paginator->build($limit, $params);
  }
  
  public function getPublicStatuses($params, $limit = null)
  {
    if ($limit === null) {
      $limit = self::DEFAULT_ITEM_LIMIT;
    }
    
    $join = new Sabel_Db_Join("Status");
    $paginator = new Paginator($join->add("User"));
    // $paginator->setCondition("User.private_flag", false);
    $paginator->setDefaultOrder("Status.created_at", "desc");
    
    return $paginator->build($limit, $params);
  }
}
