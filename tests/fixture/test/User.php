<?php

class Fixture_Test_User extends Sabel_Test_Fixture
{
  protected $modelName = "User";
  
  public function upFixture()
  {
    for ($i = 1; $i <= 10; $i++) {
      $this->insert(array(
        "user_name"    => "test{$i}",
        "email"        => "test{$i}@example.com",
        "password"     => md5("test{$i}"),
        "private_flag" => ($i === 2),
        "created_at"   => now(),
        "updated_at"   => now(),
      ));
    }
  }
  
  public function downFixture()
  {
    $this->deleteAll();
  }
}
