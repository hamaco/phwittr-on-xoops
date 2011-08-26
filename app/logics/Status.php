<?php

class Logics_Status extends Logics_Base
{
  /**
   * @transaction
   * @userCache
   *
   * @param int    $userId  ユーザID
   * @param string $comment コメント
   *
   * @return Logics_Result
   */
  public function post($userId, $comment)
  {
    $result = new Logics_Result();
    $length = mb_strlen($comment);
    
    if ($length < 1) {
      $result->setErrors(array("コメントを入力してください"));
    } elseif ($length > Status::COMMENT_MAX_LENGTH) {
      $result->setErrors(array(
        "コメントは" . Status::COMMENT_MAX_LENGTH . "以内で入力してください"
      ));
    } else {
      // $aUser = new User($userId);
      // if ($aUser->isActive()) {
        // $aUser->save(array("updated_at" => now()));
        $result->aStatus = Status::post($userId, $comment);
      // } else {
        // throw new Exception_UserNotFound(__METHOD__);
      // }
    }
    
    return $result;
  }
  
  /**
   * @userCache
   *
   * @param int $userId   ユーザID
   * @param int $statusId ステータスID
   *
   * @return Logics_Result
   */
  public function remove($userId, $statusId)
  {
    $result  = new Logics_Result();
    $aStatus = new Status($statusId);
    
    if ($aStatus->isSelected() && $aStatus->user_id === $userId) {
      $aStatus->delete();
    } else {
      $result->failure();
    }
    
    return $result;
  }
}
