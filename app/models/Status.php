<?php

class Status extends Sabel_Db_Model
{
  const COMMENT_MAX_LENGTH = 140;
  
  public static function getLatestCommentByUserId($userId)
  {
    $status = new self();
    $status->setProjection(array("comment"));
    $status->setCondition("user_id", $userId);
    $status->setOrderBy("created_at", "desc");
    $status->setLimit(1);
    
    $statuses = $status->select();
    return (isset($statuses[0])) ? $statuses[0]->comment : "";
  }
  
  public static function getCountByUserId($userId)
  {
    $status = new self();
    return $status->getCount("user_id", $userId);
  }
  
  public static function normalizeComment($comment)
  {
    return preg_replace('/ {2,}/', " ", preg_replace('/\r?\n/', " ", $comment));
  }
  
  public static function post($userId, $comment)
  {
    $status  = new self();
    $comment = self::normalizeComment($comment);
    if (preg_match('/^@(\w{1,20})/', $comment, $matches) === 1) {
      $targetUser = User::findByUsername($matches[1]);
      if ($targetUser->isActive()) {
        $status->reply_user_id = $targetUser->id;
      }
    }
    
    $status->user_id    = $userId;
    $status->comment    = $comment;
    $status->created_at = now();
    $status->save();
    
    return $status;
  }
}
