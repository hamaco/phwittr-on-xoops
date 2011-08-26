<?php

class Config_Mail implements Sabel_Config
{
  public function configure()
  {
    return array(
      "host"    => "localhost",
      "port"    => "25",
      "charset" => "ISO-2022-JP",
      "domain"  => "example.com",
    );
  }
}
