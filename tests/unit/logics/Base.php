<?php

class Unit_Logics_Base extends Sabel_Test_TestCase
{
  protected function getLogic($name)
  {
    $logicName = "Logics_" . ucfirst($name);
    return Sabel_Container::load($logicName, new Logics_DI());
  }
  
  protected function getUser($username)
  {
    $user = new User();
    return $user->selectOne("user_name", $username);
  }
  
  protected function clear($mdlName)
  {
    MODEL($mdlName)->delete();
  }
}
