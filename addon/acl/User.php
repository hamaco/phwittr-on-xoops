<?php

/**
 * Acl_User
 *
 * @category   Addon
 * @package    addon.acl
 * @author     Mori Reo <mori.reo@sabel.jp>
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Acl_User extends Sabel_ValueObject
{
  const SESSION_KEY  = "sbl_acl_user";
  const AUTHED_KEY   = "sbl_acl_authenticated";
  const AUTH_URI_KEY = "sbl_acl_auth_uri";  // login page
  
  /**
   * @var Sabel_Session_Abstract
   */
  private $session = null;
  
  public function __construct(Sabel_Session_Abstract $session)
  {
    $this->session = $session;
    
    if ($values = $session->read(self::SESSION_KEY)) {
      $this->values = $values;
    } else {
      $this->values = array();
    }
  }
  
  public function save()
  {
    $this->session->write(self::SESSION_KEY, $this->values);
  }
  
  public function isAuthenticated()
  {
    $v = $this->values;
    return (isset($v[self::AUTHED_KEY]) && $v[self::AUTHED_KEY]);
  }
  
  public function authenticate($role, $regenerateId = true)
  {
    l("ACL: authenticate.", SBL_LOG_DEBUG);
    
    $this->values[self::AUTHED_KEY] = true;
    $this->addRole($role);
    
    if ($regenerateId) $this->session->regenerateId();
  }
  
  public function deAuthenticate()
  {
    l("ACL: deAuthenticate.", SBL_LOG_DEBUG);
    
    $this->values = array(self::AUTHED_KEY => false);
  }
  
  public function login($redirectTo)
  {
    $roles = func_get_args();
    array_shift($roles);
    $this->authenticate($roles[0]);
    
    if (($c = count($roles)) > 1) {
      for ($i = 1; $i < $c; $i++) {
        $this->addRole($roles[$i]);
      }
    }
    
    if ($response = Sabel_Context::getResponse()) {
      $backUri = null;
      if ($request = Sabel_Context::getRequest()) {
        $backUri = $request->getValueWithMethod("back_uri");
      }
      
      if ($backUri === null) {
        $response->getRedirector()->to($redirectTo);
      } else {
        l("ACL: back to the page before authentication.", SBL_LOG_DEBUG);
        $response->getRedirector()->uri($backUri);
      }
    }
    
    $this->remove("login_uri");
    $this->remove("back_uri");
  }
  
  public function logout($removeCookie = true)
  {
    $this->deAuthenticate();
    
    if ($removeCookie) {
      Sabel_Cookie_Factory::create()->delete($this->session->getName());
    }
  }
  
  public function addRole($add)
  {
    $role = $this->get("role");
    
    if ($role === null) {
      $this->values["role"] = array($add);
    } elseif (!in_array($add, $role, true)) {
      $role[] = $add;
      $this->values["role"] = $role;
    }
  }
  
  public function hasRole($role)
  {
    $roles = $this->get("role");
    
    if (is_array($roles)) {
      return in_array($role, $roles, true);
    } else {
      return false;
    }
  }
  
  public function removeRole($remove)
  {
    $role = $this->get("role");
    
    if (is_array($role)) {
      unset($role[$remove]);
      $this->values["role"] = $role;
    }
  }
}
