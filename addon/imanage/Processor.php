<?php

/**
 * Imanage_Processor
 *
 * @category   Addon
 * @package    addon.imanage
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Imanage_Processor extends Sabel_Bus_Processor
{
  protected $afterEvents = array("controller" => "setImanage");
  
  /**
   * @var Imanage_Object
   */
  protected $imanage = null;
  
  public function execute($bus)
  {
    $this->imanage = new Imanage_Object();
    $bus->set("imanage", $this->imanage);
    
    $request = $bus->get("request");
    $uri = $request->getUri();
    
    if ($request->isGet() && $request->hasGetValue("name")) {
      $result = null;
      if ($uri === Imanage_Addon::THUMBNAIL_URI) {
        $result = $this->imanage->thumbnail(
          $request->fetchGetValue("name"), $request->fetchGetValue("size")
        );
      } elseif ($uri === Imanage_Addon::DISPLAY_URI) {
        $result = $this->imanage->display($request->fetchGetValue("name"));
      }
      
      if ($result !== null) {
        if ($result) { // output image.
          exit;
        } else {
          $bus->get("response")->getStatus()->setCode(Sabel_Response::NOT_FOUND);
        }
      }
    }
  }
  
  public function setImanage($bus)
  {
    $bus->get("controller")->setAttribute("imanage", $this->imanage);
  }
}
