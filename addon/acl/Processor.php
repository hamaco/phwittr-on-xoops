<?php

/**
 * Acl_Processor
 *
 * @category   Addon
 * @package    addon.acl
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Acl_Processor extends Sabel_Bus_Processor
{
  /**
   * @var Acl_User
   */
  protected $user = null;
  
  public function execute($bus)
  {
    $config   = new Acl_Config();
    $configs  = $config->configure();
    $request  = $bus->get("request");
    $response = $bus->get("response");
    
    $this->user = $user = new Acl_User($bus->get("session"));
    if (isset($user->login_uri) && $user->login_uri !== $request->getUri()) {
      $user->remove("login_uri");
      $user->remove("back_uri");
    }
    
    $controller = $bus->get("controller");
    $controller->setAttribute("aclUser", $user);
    
    if ($response->isFailure() || $response->isRedirected()) return;
    
    $destination = $bus->get("destination");
    list ($modName, $ctrlName, $actName) = $destination->toArray();
    if (!isset($configs[$modName])) return;
    
    $modConfig  = $configs[$modName];
    $ctrlConfig = $modConfig->getController($ctrlName);
    
    if ($ctrlConfig === null) {
      if ($this->isAllow($modConfig)) return;
      $authUri = $modConfig->authUri();
    } else {
      if ($this->isAllow($ctrlConfig)) return;
      $authUri = $ctrlConfig->authUri();
      
      if ($authUri === null) {
        $authUri = $modConfig->authUri();
      }
    }
    
    l("ACL: access denied.", SBL_LOG_DEBUG);
    
    if ($controller->hasMethod("aclForbidden")) {
      $result = $controller->aclForbidden($actName);
      if ($result === false) return;
      
      if ($result !== null) {
        $user->back_uri = $result;
      }
    }
    
    if ($authUri === null) {
      $response->getStatus()->setCode(Sabel_Response::FORBIDDEN);
    } else {
      $user->login_uri = ltrim($response->getRedirector()->to($authUri), "/");
    }
  }
  
  public function shutdown($bus)
  {
    $this->user->save();
  }
  
  private function isAllow($config)
  {
    if ($config->isPublic()) {
      return true;
    } elseif ($this->user->isAuthenticated()) {
      return $config->isAllow($this->user->role);
    } else {
      return false;
    }
  }
}
