<?php

/**
 * Extroller_Processor
 *
 * @category   Addon
 * @package    addon.extroller
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Extroller_Processor extends Sabel_Bus_Processor
{
  public function execute($bus)
  {
    $response = $bus->get("response");
    $status   = $response->getStatus();
    if ($status->isFailure()) return;
    
    $controller = $bus->get("controller");
    $controller->mixin("Extroller_Mixin");
    
    $request = $bus->get("request");
    $gets    = $request->fetchGetValues();
    $posts   = $request->fetchPostValues();
    $params  = $request->fetchParameterValues();
    $vCount  = count($gets) + count($posts) + count($params);
    $values  = array_merge($gets, $posts, $params);
    
    if (count($values) !== $vCount) {
      l("Extroller: request key overlaps", SBL_LOG_DEBUG);
      return $status->setCode(Sabel_Response::BAD_REQUEST);
    } else {
      foreach ($values as $name => $value) {
        $controller->setAttribute($name, $value);
      }
      
      $controller->setAttribute("REQUEST_VARS", $values);
      $controller->setAttribute("GET_VARS",     $gets);
      $controller->setAttribute("POST_VARS",    $posts);
    }
    
    $action = $bus->get("destination")->getAction();
    
    if ($controller->hasMethod($action)) {
      $reader = Sabel_Annotation_Reader::create();
      $annots = $reader->readMethodAnnotation($controller, $action);
      
      if (isset($annots["httpMethod"])) {
        $allows = $annots["httpMethod"][0];
        if (!$this->isMethodAllowed($request, $allows)) {
          $response->setHeader("Allow", implode(",", array_map("strtoupper", $allows)));
          return $status->setCode(Sabel_Response::METHOD_NOT_ALLOWED);
        }
      }
      
      if (isset($annots["check_client_id"])) {
        if (!$this->checkClientId($request, $bus->get("session"), $annots["check_client_id"][0])) {
          return $status->setCode(Sabel_Response::BAD_REQUEST);
        }
      }
      
      if (isset($annots["check"]) && ($request->isGet() || $request->isPost())) {
        if (!$result = $this->validate($controller, $values, $request, $annots["check"])) {
          return $status->setCode(Sabel_Response::BAD_REQUEST);
        }
      }
    }
  }
  
  protected function isMethodAllowed($request, $allows)
  {
    $result = true;
    foreach ($allows as $method) {
      if (!($result = $request->{"is" . $method}())) break;
    }
    
    return $result;
  }
  
  protected function checkClientId($request, $session, $methods)
  {
    // for benchmark
    if (ENVIRONMENT === BENCHMARK) return true;
    
    $check = false;
    
    if ($methods === null) {
      $check = true;
    } else {
      foreach ($methods as $method) {
        if ($request->{"is" . $method}()) {
          $check = true;
          break;
        }
      }
    }
    
    if ($check) {
      $clientId = $request->getValueWithMethod("SBL_CLIENT_ID");
      return ($session->getClientId() === $clientId);
    } else {
      return true;
    }
  }
  
  protected function validate($controller, $values, $request, $checks)
  {
    $validator = new Validator();
    $validator->validate($values);
    
    foreach ($checks as $check) {
      $name = array_shift($check);
      $validator->set($name, $check);
    }
    
    $controller->setAttribute("validator", $validator);
    
    $result = true;
    if (!$validator->validate($values)) {
      if ($request->isPost()) {
        $controller->setAttribute("errors", $validator->getErrors());
      } else {
        $result = false;
      }
    }
    
    return $result;
  }
}
