<?php

/**
 * Imanage_Object
 *
 * @category   Addon
 * @package    addon.imanage
 * @author     Hamanaka Kazuhiro <hamanaka.kazuhiro@sabel.jp>
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Imanage_Object extends Sabel_Object
{
  /**
   * @var array
   */
  protected static $config = array(
    "formats" => array("jpeg", "gif", "png"),
    "maximumFileSize" => 100,  // KBytes
    "defaultThumbnailSize" => 100,  // px
    "thumbnailSizes" => array(50, 100, 150, 200),
  );
  
  public static function setConfig(array $config)
  {
    if (isset($config["thumbnailSizes"])) {
      sort($config["thumbnailSizes"], SORT_NUMERIC);
    }
    
    self::$config = array_merge(self::$config, $config);
  }
  
  public static function getFormats()
  {
    return self::$config["formats"];
  }
  
  public static function validate($resource)
  {
    return (self::checkSize($resource) && self::checkFormat($resource));
  }
  
  public static function checkSize($resource)
  {
    return (strlen($resource) <= self::$config["maximumFileSize"] * 1024);
  }
  
  public static function checkFormat($resource)
  {
    $type = self::getType($resource);
    if ($type === null) return false;
    
    return in_array(strtolower($type), self::getFormats(), true);
  }
  
  public static function getType($resource)
  {
    if (preg_match('/^\xff\xd8/', $resource) === 1) {
      return "jpeg";
    } elseif (preg_match('/^GIF8[79]a/', $resource) === 1) {
      return "gif";
    } elseif (preg_match('/^\x89PNG\x0d\x0a\x1a\x0a/', $resource) === 1) {
      return "png";
    } else {
      return null;
    }
  }
  
  /**
   * @param string $resource image data
   *
   * @return string
   */
  public function upload($resource)
  {
    $fileName = md5hash() . "." . $this->getType($resource);
    file_put_contents($this->getSourceDir() . DS . $fileName, $resource);
    
    return $fileName;
  }
  
  /**
   * @param string $fileName file name
   *
   * @return void
   */
  public function delete($fileName)
  {
    $baseDir = $this->getBaseDir();
    foreach (scandir($baseDir) as $item) {
      if (substr($item, 0, 1) === ".") continue;
      
      if (is_dir($baseDir . DS . $item)) {
        if (is_file($baseDir . DS . $item . DS . $fileName)) {
          unlink($baseDir . DS . $item . DS . $fileName);
        }
      }
    }
  }
  
  /**
   * @param string $fileName
   *
   * @return boolean
   */
  public function display($fileName)
  {
    if (($resource = $this->getSourceImage($fileName)) === null) {
      return false;
    } else {
      $type = $this->getType($resource);
      header("Content-Type: image/{$type}");
      echo $resource;
      return true;
    }
  }
  
  /**
   * @param string $fileName
   * @param int    $size
   *
   * @return void
   */
  public function thumbnail($fileName, $size)
  {
    if (!is_natural_number($size)) {
      $size = self::$config["defaultThumbnailSize"];
    } elseif (!in_array((int)$size, self::$config["thumbnailSizes"], true)) {
      $_size = 0;
      $diff = PHP_INT_MAX;
      foreach (self::$config["thumbnailSizes"] as $tSize) {
        if (($abs = abs($tSize - $size)) <= $diff) {
          $diff  = $abs;
          $_size = $tSize;
        }
      }
      
      $size = $_size;
    }
    
    $filePath = $this->getThumbnailDir($size) . DS . $fileName;
    
    if (!is_file($filePath)) {
      if (($source = $this->getSourceImage($fileName)) === null) {
        return false;
      } else {
        $image = $this->createImageObject($source);
        $image->resize($size)->save($filePath);
      }
    }
    
    $resource = file_get_contents($filePath);
    $type = $this->getType($resource);
    header("Content-Type: image/{$type}");
    echo $resource;
    
    return true;
  }
  
  protected function getSourceDir()
  {
    $dir = $this->getBaseDir() . DS . "source";
    
    if (!is_dir($dir)) {
      mkdir($dir);
      chmod($dir, 0777);
    }
    
    return $dir;
  }
  
  protected function getThumbnailDir($size)
  {
    $dir = $this->getBaseDir() . DS . $size;
    
    if (!is_dir($dir)) {
      mkdir($dir);
      chmod($dir, 0777);
    }
    
    return $dir;
  }
  
  protected function getBaseDir()
  {
    if (isset(self::$config["baseDir"])) {
      $dir = RUN_BASE . DS . self::$config["baseDir"];
    } else {
      $dir = RUN_BASE . DS . "data" . DS . "images";
    }
    
    if (!is_dir($dir)) {
      mkdir($dir);
      chmod($dir, 0777);
    }
    
    return $dir;
  }
  
  protected function getSourceImage($fileName)
  {
    $filePath = $this->getSourceDir() . DS . $fileName;
    return (is_file($filePath)) ? file_get_contents($filePath) : null;
  }
  
  protected function createImageObject($resource)
  {
    switch (strtolower($this->getType($resource))) {
      case "gif":
        return new Imanage_Formats_Gif($resource);
        
      case "jpeg":
        return new Imanage_Formats_Jpeg($resource);
        
      case "png":
        return new Imanage_Formats_Png($resource);
        
      default:
        $message = __METHOD__ . "() invalid image type.";
        throw new Sabel_Exception_Runtime($message);
    }
  }
}
