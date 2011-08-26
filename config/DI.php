<?php

class Config_DI extends Sabel_Container_Injection
{
  public function configure()
  {
    
  }
}

Sabel_Container::addConfig("default", new Config_DI());
