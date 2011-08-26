<?php

/**
 * Imanage_Formats_Base
 *
 * @abstract
 * @category   Addon
 * @package    addon.imanage
 * @author     Hamanaka Kazuhiro <hamanaka.kazuhiro@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
abstract class Imanage_Formats_Base extends Sabel_Object
{
  protected $imagick = null;
  
  public function __construct($resource)
  {
    $this->imagick = new Imagick();
    $this->imagick->readImageBlob($resource);
  }
  
  public function resize($size)
  {
    // Phwittr(Twitter)用にcropに変更
    
    $imagick = $this->imagick;
    $imagick->cropThumbnailImage($size, $size);
    
    $x = ($size - $imagick->getImageWidth())  / 2;
    $y = ($size - $imagick->getImageHeight()) / 2;
    
    $buf = new Imagick();
    $buf->newImage($size, $size, new ImagickPixel("#fff"), $this->type);
    $buf->compositeImage($imagick, Imagick::COMPOSITE_OVER, $x, $y);
    
    $this->imagick = $buf;
    
    return $this;
  }
  
  public function save($path)
  {
    $this->imagick->writeImages($path, true);
    
    return $this;
  }
}
