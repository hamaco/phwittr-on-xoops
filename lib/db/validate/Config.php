<?php

/**
 * Custom Validation
 *
 * @category   DB
 * @package    lib.db
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Db_Validate_Config extends Sabel_Db_Validate_Config
{
  public function configure()
  {
    $this->model("User")->column("user_name")->validator("validateUserName");
    $this->model("User")->column("email")->validator("validateEmail");
  }
  
  public function validateUserName($model, $colName, $localizedName)
  {
    if (($userName = $model->$colName) !== null) {
      if (preg_match('/^\w{1,20}$/', $userName) === 0) {
        return $localizedName . "は英数字と'_'が使えます";
      }
    }
  }
  
  public function validateEmail($model, $colName, $localizedName)
  {
    if (($email = $model->$colName) !== null) {
      $parts = explode("@", $email);
      if (count($parts) !== 2 || $parts[0] === "" || $parts[1] === "") {
        return $localizedName . "の形式が不正です";
      }
    }
  }
  
  public function VALIDATE_METHOD2($model, $colName, $localizedName, $arg1, $arg2)
  {
    // if (!EXPRESSION) {
    //    return "ERROR_MESSAGE";
    // }
  }
}
