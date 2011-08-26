<?php

if (!class_exists("C", false)) {
  class C extends Sabel_Db_Condition {}
}

class Unit_Paginators extends Sabel_Test_UnitSuite
{
  public static function suite()
  {
    $bus = new Sabel_Bus();
    
    Sabel::fileUsing(dirname(__FILE__) . DS . "paginators" . DS . "Base.php", true);
    
    $suite = new self();
    $suite->add("Paginators_Status");
    $suite->add("Paginators_Follower");
    
    return $suite;
  }
}
