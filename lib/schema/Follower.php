<?php

class Schema_Follower
{
  public static function get()
  {
    $cols = array();

    $cols['user_id'] = array('type' => Sabel_Db_Type::INT, 'max' => 2147483647, 'min' => -2147483648, 'increment' => false, 'nullable' => false, 'primary' => true, 'default' => null);
    $cols['follow_id'] = array('type' => Sabel_Db_Type::INT, 'max' => 2147483647, 'min' => -2147483648, 'increment' => false, 'nullable' => false, 'primary' => true, 'default' => null);
    $cols['created_at'] = array('type' => Sabel_Db_Type::DATETIME, 'increment' => false, 'nullable' => false, 'primary' => false, 'default' => null);


    return $cols;
  }

  public function getProperty()
  {
    $property = array();

    $property['tableEngine'] = 'InnoDB';
    $property['uniques'] = null;
    $property['fkeys']['user_id'] = array('referenced_table'  => 'users',
                                         'referenced_column' => 'uid',
                                         'on_delete'         => 'NO ACTION',
                                         'on_update'         => 'NO ACTION');
    $property['fkeys']['follow_id'] = array('referenced_table'  => 'users',
                                         'referenced_column' => 'uid',
                                         'on_delete'         => 'NO ACTION',
                                         'on_update'         => 'NO ACTION');


    return $property;
  }
}
