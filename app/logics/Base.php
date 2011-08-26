<?php

class Logics_Base extends Sabel_Object
{
  protected function validateModel(Sabel_Db_Model $model)
  {
    $validator = new Sabel_Db_Validator($model);
    return $validator->validate();
  }
}
