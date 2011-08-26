<?php

class Schema_SblVersion
{
  public static function get()
  {
    $cols = array();

    $cols['version'] = array('type' => Sabel_Db_Type::INT, 'max' => 2147483647, 'min' => -2147483648, 'increment' => false, 'nullable' => false, 'primary' => false, 'default' => null);


    return $cols;
  }

  public function getProperty()
  {
    $property = array();

    $property['tableEngine'] = 'MyISAM';
    $property['uniques'] = null;
    $property['fkeys'] = null;


    return $property;
  }
}