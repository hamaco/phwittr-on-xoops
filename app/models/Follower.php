<?php

class Follower extends Sabel_Db_Model
{
  public static function getFriends($userId, $limit)
  {
    $join = new Sabel_Db_Join(new self());
    $join->add("Users", "", array("id" => "uid", "fkey" => "follow_id"));
    $join->setCondition("Follower.user_id", $userId);
    $join->setOrderBy("Follower.created_at");
    $join->setLimit($limit);
    
    return $join->select();
  }
  
  public static function getFriendIds($userId)
  {
    $self = new self();
    $stmt = $self->prepareStatement(Sabel_Db_Statement::SELECT);
    $stmt->projection(array("follow_id"))
         ->where("WHERE user_id = @user_id@")
         ->constraints(array("order" => array("created_at" => "asc")))
         ->setBindValue("user_id", $userId);
    
    $ids = array();
    if ($rows = $stmt->execute()) {
      foreach ($rows as $row) $ids[] = (int)$row["follow_id"];
    }
    
    return $ids;
  }
  
  public static function getFriendsCount($userId)
  {
    $self = new self();
    return $self->getCount("user_id", $userId);
  }
  
  public static function getFollowersCount($userId)
  {
    $self = new self();
    return $self->getCount("follow_id", $userId);
  }
  
  public static function isFollowed($userId, $targetId)
  {
    $self = new self();
    $self->setCondition("user_id",   $userId);
    $self->setCondition("follow_id", $targetId);
    
    return $self->selectOne()->isSelected();
  }
}
