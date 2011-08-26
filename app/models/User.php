<?php

Sabel_Db_Model_Localize::setColumnNames("User",
  array(
    "id"           => "id",
    "user_name"    => "ユーザ名",
    "email"        => "メールアドレス",
    "password"     => "パスワード",
    "private_flag" => "公開設定",
    "image"        => "image",
  )
);

class User extends Sabel_Db_Model
{
  protected $tableName = "users";
  
  public function getName()
  {
    return ($this->name) ? $this->name : $this->uname;
  }
  
  public static function findByUsername($username)
  {
    $self = new self();
    return $self->selectOne("uname", $username);
    return $self->selectOne("user_name", $username);
  }
  
  public static function findByUsernameAndPassword($username, $password)
  {
    $self = new self();
    $self->setCondition("user_name", $username);
    $self->setCondition("password",  md5($password));
    
    return $self->selectOne();
  }
  
  public static function findByEmailAndPassword($email, $password)
  {
    $self = new self();
    $self->setCondition("email",    $email);
    $self->setCondition("password", md5($password));
    
    return $self->selectOne();
  }
  
  public function isActive()
  {
    return ($this->isSelected() && $this->act_key === null && !$this->delete_flag);
  }
  
  public function isProtected()
  {
    return $this->private_flag;
  }
  
  public function updatePassword($newPassword)
  {
    $this->save(array("password" => md5($newPassword)));
  }
}
