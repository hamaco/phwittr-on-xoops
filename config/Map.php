<?php

class Config_Map extends Sabel_Map_Configurator
{
  public function configure()
  {
    $this->route("default")
           ->uri(":controller/:action/:param")
           ->module("index")
           ->requirements(array("controller" => 'user|index|status|login|register|friendship'))
           ->defaults(array(
             ":controller" => "index",
             ":action"     => "index",
             ":param"      => null)
           );
    
    $this->route("users")
           ->uri(":controller/:action")
           ->module("index")
           ->controller("users")
           ->defaults(array(":action" => "index"));
    
    $this->route("notfound")
           ->uri("*")
           ->module("index")
           ->controller("index")
           ->action("notfound");
  }
}
