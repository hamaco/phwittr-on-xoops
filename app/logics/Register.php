<?php

class Logics_Register extends Logics_Base
{
  protected $mail = null;
  
  public function setMail(Mail $mail)
  {
    $this->mail = $mail;
  }
  
  /**
   * @transaction
   *
   * @param User $aUser
   *
   * @return Logics_Result
   */
  public function preregister(User $aUser)
  {
    $result = new Logics_Result();
    $aUser->created_at = $aUser->updated_at = now();
    
    if ($errors = $this->validateModel($aUser)) {
      return $result->setErrors($errors);
    }
    
    $actKey = md5hash();
    $aUser->password = md5($aUser->password);
    
    if (ENVIRONMENT === DEVELOPMENT) {
      $aUser->save();
    } else {
      $aUser->save(array("act_key" => $actKey));
    }
    
    $this->mail->sendActivationKey($aUser->email, $actKey);
    
    return $result;
  }
  
  /**
   * @param string $actKey アクティベーション・キー
   *
   * @return Logics_Result
   */
  public function register($actKey)
  {
    $result = new Logics_Result();
    $user   = new User();
    $aUser  = $user->selectOne("act_key", $actKey);
    
    if ($aUser->isSelected()) {
      $aUser->save(array("act_key" => null));
      $result->aUser = $aUser;
    } else {
      throw new Exception_UserNotFound(__METHOD__);
    }
    
    return $result;
  }
}
