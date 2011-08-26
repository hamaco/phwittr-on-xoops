<?php

/**
 * Imanage_Formats_Gif
 *
 * @abstract
 * @category   Addon
 * @package    addon.imanage
 * @author     Hamanaka Kazuhiro <hamanaka.kazuhiro@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Imanage_Formats_Gif extends Imanage_Formats_Base
{
  protected $type = "gif";
  
  public function resize($size)
  {
    $this->imagick = $this->imagick->coalesceImages();
    
    foreach ($this->imagick as $frame) {
      $frame->cropThumbnailImage($size, $size);
      $frame->setImagePage($size, $size, 0, 0);
    }
    
    return $this;
  }
}
