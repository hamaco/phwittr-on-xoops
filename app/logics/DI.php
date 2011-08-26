<?php

class Logics_DI extends Sabel_Container_Injection
{
  public function configure()
  {
    if (ENVIRONMENT === TEST) {
      $this->bind("Imanage")->to("Imanage_Mock");
    } else {
      $this->bind("Imanage")->to("Imanage_Impl");
    }
    
    if (ENVIRONMENT === PRODUCTION) {
      $this->bind("Mail")->to("Mail_Object");
    } else {
      $this->bind("Mail")->to("Mail_Mock");
    }
    
    $aspect = $this->aspect("Logics_Base");
    $aspect->annotate("transaction", array("Aspects_Transaction"));
    
    if ((ENVIRONMENT & PRODUCTION) > 0) {
      $aspect->annotate("userCache", array("Aspects_UserCache"));
    }
  }
}
