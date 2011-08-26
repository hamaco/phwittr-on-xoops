<?php

Sabel_Db_Model_Localize::setColumnNames("Users",
  array(
    "id"           => "id",
    "user_name"    => "ユーザ名",
    "email"        => "メールアドレス",
    "password"     => "パスワード",
    "private_flag" => "公開設定",
    "image"        => "image",
  )
);

class Users extends User
{
}
