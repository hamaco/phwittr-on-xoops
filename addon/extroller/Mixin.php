<?php

/**
 * Extroller_Mixin
 *
 * @category   Addon
 * @package    addon.extroller
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Extroller_Mixin extends Sabel_Controller_Page
{
  public function fetchModel($mdlName, $conditions)
  {
    if (is_array($conditions)) {
      $_model = MODEL($mdlName);
      foreach ($conditions as $column => $value) {
        $_model->setCondition($column, $value);
      }
      
      $model = $_model->selectOne();
    } else {
      $model = MODEL($mdlName, $conditions);
    }
    
    if ($model->isSelected()) {
      return $model;
    } else {
      $this->notFound();
      return false;
    }
  }
  
  public function getLogic($name)
  {
    $className = "Logics_" . ucfirst($name);
    if (Sabel::using($className)) {
      return Sabel_Container::load($className, new Logics_DI());
    }
    
    $message = __METHOD__ . "() logic class not found.";
    throw new Sabel_Exception_ClassNotFound($message);
  }
  
  public function checkClientId($requestKey = "SBL_CLIENT_ID")
  {
    // phwittr bench
    if (ENVIRONMENT === BENCHMARK) return true;
    
    $clientId = $this->request->getValueWithMethod($requestKey);
    return ($this->session->getClientId() === $clientId);
  }
  
  public function isAjaxRequest()
  {
    return ($this->request->getHttpHeader("X-Requested-With") === "XMLHttpRequest");
  }
  
  public function badRequest()
  {
    $this->response->getStatus()->setCode(Sabel_Response::BAD_REQUEST);
  }
  
  public function notFound()
  {
    $this->response->getStatus()->setCode(Sabel_Response::NOT_FOUND);
  }
  
  public function forbidden()
  {
    $this->response->getStatus()->setCode(Sabel_Response::FORBIDDEN);
  }
  
  public function serverError()
  {
    $this->response->getStatus()->setCode(Sabel_Response::INTERNAL_SERVER_ERROR);
  }
}
