<?php

class Helpers_Paginator_Follower extends Sabel_Object
{
  const DEFAULT_ITEM_LIMIT = 20;
  
  public function getFriends($userId, $params, $limit = null)
  {
    if ($limit === null) {
      $limit = self::DEFAULT_ITEM_LIMIT;
    }
    
    $join = new Sabel_Db_Join("Follower");
    $paginator = new Paginator($join->add("Users", "", array("id" => "uid", "fkey" => "follow_id")));
    $paginator->setCondition("Follower.user_id", $userId);
    $paginator->setDefaultOrder("Follower.created_at", "desc");
    
    return $paginator->build($limit, $params);
  }
  
  public function getFollowers($userId, $params, $limit = null)
  {
    if ($limit === null) {
      $limit = self::DEFAULT_ITEM_LIMIT;
    }
    
    $join = new Sabel_Db_Join("Follower");
    $paginator = new Paginator($join->add("Users", "", array("id" => "uid", "fkey" => "user_id")));
    $paginator->setCondition("Follower.follow_id", $userId);
    $paginator->setDefaultOrder("Follower.created_at", "desc");
    
    return $paginator->build($limit, $params);
  }
}
