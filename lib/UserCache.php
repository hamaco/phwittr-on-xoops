<?php

class UserCache extends Sabel_Object
{
  private static $instance = null;
  
  protected $cacheDir = "";  // CACHE_DIR_PATH . DS . "user"
  
  protected $cacheKeys = array(
    "friends"   => "int",
    "followers" => "int",
    "statuses"  => "int",
    "comment"   => "string"
  );
  
  private function __construct() {}
  
  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
      self::$instance->cacheDir = CACHE_DIR_PATH . DS . "user";
    }
    
    return self::$instance;
  }
  
  public function read($userId)
  {
    $path = $this->getPath($userId);
    return (is_file($path)) ? $this->_read($path) : false;
  }
  
  public function lock($userId, $mode = "ab+")
  {
    $path = $this->getPath($userId);
    $fp = fopen($path, $mode);
    flock($fp, LOCK_EX);
    
    $data = $this->_read($path);
    ftruncate($fp, 0);
    
    return array($fp, $data);
  }
  
  protected function _read($path)
  {
    include ($path);
    if (isset($data) && $this->check($data)) {
      return $data;
    } else {
      return false;
    }
  }
  
  public function write($fp, $data)
  {
    fwrite($fp, '<?php $data = ' . var_export($data, true) . ';');
    fclose($fp);
  }
  
  protected function check($data)
  {
    if (!is_array($data)) return false;
    
    foreach ($this->cacheKeys as $key => $type) {
      $func = "is_" . $type;
      if (!isset($data[$key]) || !$func($data[$key])) {
        return false;
      }
    }
    
    return true;
  }
  
  protected function getPath($userId)
  {
    return $this->cacheDir . DS . $userId . ".php";
  }
}
