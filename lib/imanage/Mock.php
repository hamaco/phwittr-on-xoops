<?php

class Imanage_Mock implements Imanage
{
  public function validate($resource)
  {
    return ($resource !== null);
  }
  
  public function upload($resource)
  {
    return md5($resource) . ".gif";
  }
  
  public function delete($fileName)
  {
    
  }
}
