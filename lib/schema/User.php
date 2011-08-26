<?php

class Schema_User extends Schema_Users
{
  /*
  public static function get()
  {
    $cols = array();

    $cols['uid'] = array('type' => Sabel_Db_Type::INT, 'max' => 2147483647, 'min' => -2147483648, 'increment' => true, 'nullable' => false, 'primary' => true, 'default' => null);
    $cols['user_name'] = array('type' => Sabel_Db_Type::STRING, 'min' => 0, 'max' => 20, 'increment' => false, 'nullable' => false, 'primary' => false, 'default' => null);
    $cols['email'] = array('type' => Sabel_Db_Type::STRING, 'min' => 0, 'max' => 255, 'increment' => false, 'nullable' => false, 'primary' => false, 'default' => null);
    // $cols['password'] = array('type' => Sabel_Db_Type::STRING, 'min' => 0, 'max' => 32, 'increment' => false, 'nullable' => false, 'primary' => false, 'default' => null);
    $cols['image'] = array('type' => Sabel_Db_Type::STRING, 'min' => 0, 'max' => 40, 'increment' => false, 'nullable' => true, 'primary' => false, 'default' => 'default.png');
    // $cols['private_flag'] = array('type' => Sabel_Db_Type::BOOL, 'increment' => false, 'nullable' => true, 'primary' => false, 'default' => false);
    // $cols['delete_flag'] = array('type' => Sabel_Db_Type::BOOL, 'increment' => false, 'nullable' => true, 'primary' => false, 'default' => false);
    // $cols['act_key'] = array('type' => Sabel_Db_Type::STRING, 'min' => 0, 'max' => 32, 'increment' => false, 'nullable' => true, 'primary' => false, 'default' => null);
    // $cols['created_at'] = array('type' => Sabel_Db_Type::DATETIME, 'increment' => false, 'nullable' => false, 'primary' => false, 'default' => null);
    // $cols['updated_at'] = array('type' => Sabel_Db_Type::DATETIME, 'increment' => false, 'nullable' => false, 'primary' => false, 'default' => null);


    return $cols;
  }

  public function getProperty()
  {
    $property = array();

    $property['tableEngine'] = 'InnoDB';
    $property['uniques'][] = array('user_name');
    $property['uniques'][] = array('email');
    $property['fkeys'] = null;


    return $property;
  }
   */
}
