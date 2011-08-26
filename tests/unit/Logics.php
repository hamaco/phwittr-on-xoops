<?php

class Unit_Logics extends Sabel_Test_UnitSuite
{
  public static function suite()
  {
    Sabel::fileUsing(dirname(__FILE__) . DS . "logics" . DS . "Base.php", true);
    
    $suite = new self();
    $suite->add("Logics_Register");
    $suite->add("Logics_Status");
    $suite->add("Logics_Follow");
    $suite->add("Logics_User");
    
    return $suite;
  }
}
