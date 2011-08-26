<?php

class Unit_Models_Base extends Sabel_Test_TestCase
{
  protected function getUser($name)
  {
    $user = new User();
    return $user->selectOne("user_name", $name);
  }
  
  protected function clear($mdlName)
  {
    MODEL($mdlName)->delete();
  }
}
