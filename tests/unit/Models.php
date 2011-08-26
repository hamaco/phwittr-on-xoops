<?php

class Unit_Models extends Sabel_Test_UnitSuite
{
  public static function suite()
  {
    Sabel::fileUsing(dirname(__FILE__) . DS . "models" . DS . "Base.php", true);
    
    $suite = new self();
    $suite->add("Models_User");
    $suite->add("Models_Status");
    $suite->add("Models_Request");
    $suite->add("Models_Follower");
    
    return $suite;
  }
}
