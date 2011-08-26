<?php

class Imanage_Impl implements Imanage
{
  protected $imanage = null;
  
  public function __construct()
  {
    $this->imanage = Sabel_Context::getContext()->getBus()->get("imanage");
  }
  
  public function validate($resource)
  {
    return $this->imanage->validate($resource);
  }
  
  public function upload($resource)
  {
    return $this->imanage->upload($resource);
  }
  
  public function delete($fileName)
  {
    $this->imanage->delete($fileName);
  }
}
