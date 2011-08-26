<?php

class Config_Database implements Sabel_Config
{
  public function configure()
  {
    $params = array(
      "default" => array(
        "package"  => "sabel.db.mysql",
        "charset"  => "utf8",
        "host"     => XOOPS_DB_HOST,
        "port"     => 3306,
        "database" => XOOPS_DB_NAME,
        "user"     => XOOPS_DB_USER,
        "password" => XOOPS_DB_PASS,
    ));
    
    return $params;
  }
}

Sabel_Db_Validate_Config::setMessages(
  array("maxlength" => "%NAME%は%MAX%文字以下で入力してください",
        "minlength" => "%NAME%は%MIN%文字以上で入力してください",
        "maximum"   => "%NAME%は%MAX%以上を入力してください",
        "minimum"   => "%NAME%は%MIN%以下を入力してください",
        "nullable"  => "%NAME%を入力してください",
        "numeric"   => "%NAME%は数値で入力してください",
        "type"      => "%NAME%の形式が不正です",
        "unique"    => "%NAME%'%VALUE%'は既に使用されています")
);
