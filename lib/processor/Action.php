<?php

/**
 * Processor_Action
 *
 * @category   Processor
 * @package    lib.processor
 * @author     Mori Reo <mori.reo@sabel.jp>
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Processor_Action extends Sabel_Bus_Processor
{
  public function execute($bus)
  {
    $response   = $bus->get("response");
    $status     = $response->getStatus();
    $controller = $bus->get("controller");
    
    if ($response->isFailure() || $response->isRedirected()) return;
    
    $action = $bus->get("destination")->getAction();
    $controller->setAction($action);
    
    if ($isAjax = $bus->get("AJAX_REQUEST")) {
      $controller->setAttribute("ajax", new AjaxResult());
    }
    
    $controller->setAttribute("submenu", new Views_Submenu());
    
    try {
      $controller->initialize();
      
      if ($response->isSuccess() && !$response->isRedirected()) {
        $controller->execute();
      }
      
      $controller->finalize();
      
      if ($isAjax && $response->isFailure()) {
        $status->setCode(Sabel_Response::OK);
        $controller->ajax->code = $status->getCode();
      }
    } catch (Exception_UserNotFound $unf) {
      if ($isAjax) {
        $controller->ajax->code = Sabel_Response::NOT_FOUND;
      } else {
        $status->setCode(Sabel_Response::NOT_FOUND);
      }
      
      Sabel_Context::getContext()->setException($e);
    } catch (Exception $e) {
      if ($isAjax) {
        $controller->ajax->code = Sabel_Response::INTERNAL_SERVER_ERROR;
      } else {
        $status->setCode(Sabel_Response::INTERNAL_SERVER_ERROR);
      }
      
      Sabel_Context::getContext()->setException($e);
    }
    
    if ($controller->getAttribute("layout") === false) {
      $bus->set("NO_LAYOUT", true);
    }
  }
}
