<?php

class Config_Addon implements Sabel_Config
{
  public function configure()
  {
    return array(
      "acl",
      "imanage",
      "form",
      "extroller",
      "renderer",
    );
  }
}
