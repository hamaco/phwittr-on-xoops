<?php

/**
 * Imanage_Addon
 *
 * @category   Addon
 * @package    addon.imanage
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Imanage_Addon extends Sabel_Object
{
  const VERSION = 1.0;
  
  const DISPLAY_URI   = "image";
  const THUMBNAIL_URI = "thumbnail";
  
  public function execute($bus)
  {
    $bus->getProcessorList()->insertNext(
      "router", "imanage", new Imanage_Processor("imanage")
    );
    
    Imanage_Object::setConfig(array(
      "maximumFileSize"      => 700,
      "defaultThumbnailSize" => 48,
      "thumbnailSizes" => array(24, 48),
    ));
  }
}

function image_uri($name, $size = null)
{
  $uri = get_uri_prefix() . "/" . Imanage_Addon::DISPLAY_URI;
  return $uri . "?name={$name}";
}

function thumbnail_uri($name, $size = null)
{
  $uri = get_uri_prefix() . "/" . Imanage_Addon::THUMBNAIL_URI;
  return $uri . "?name={$name}&size={$size}";
}
