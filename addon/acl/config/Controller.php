<?php

/**
 * Acl_Config_Controller
 *
 * @category   Addon
 * @package    addon.acl
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Acl_Config_Controller extends Sabel_Object
{
  protected $isAllow = false;
  protected $authUri = "";
  
  public function isPublic()
  {
    return ($this->isAllow === true);
  }
  
  public function isAllow($roles = null)
  {
    if ($this->isPublic()) return true;
    
    $s = microtime();
    $rule = preg_replace('/[a-z]+/', '"$0"', $this->isAllow);
    foreach ($roles as $role) {
      $rule = str_replace('"' . $role . '"', "true", $rule);
    }
    
    $rule = str_replace(
      array( "|",  "&"),
      array("||", "&&"),
      preg_replace('/"[a-z]+"/', 'false', $rule)
    );
    
    eval ('$match = ' . $rule . ';');
    
    return $match;
  }
  
  public function allow($role = null)
  {
    if ($role === null) {
      $this->isAllow = true;
    } elseif (is_string($role)) {
      $this->isAllow = $role;
    } else {
      $message = __METHOD__ . "() argument must be a string.";
      throw new Sabel_Exception_InvalidArgument($message);
    }
    
    return $this;
  }
  
  public function authUri($uri = null)
  {
    if ($uri === null) {
      return ($this->authUri === "") ? null : $this->authUri;
    } else {
      $this->authUri = $uri;
    }
  }
}
