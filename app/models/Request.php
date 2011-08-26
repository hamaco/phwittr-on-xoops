<?php

class Request extends Sabel_Db_Model
{
  public static function getRequestsByUserId($userId)
  {
    $join = new Sabel_Db_Join("Request");
    $join->setCondition("Request.request_id", $userId);
    $join->setOrderBy("Request.created_at", "desc");
    
    return $join->add("User")->select();
  }
  
  public static function getRequestsCount($userId)
  {
    $self = new self();
    return $self->getCount("request_id", $userId);
  }
  
  public static function isRequested($userId, $targetId)
  {
    $self = new self();
    $self->setCondition("user_id",    $userId);
    $self->setCondition("request_id", $targetId);
    
    return $self->selectOne()->isSelected();
  }
}
