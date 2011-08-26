<?php

class AjaxResult extends Sabel_ValueObject
{
  public function __construct()
  {
    $this->success();
    
    $this->useTemplate = false;
  }
  
  public function __toString()
  {
    return json_encode($this->values);
  }
  
  public function success()
  {
    $this->code = Sabel_Response::OK;
  }
  
  public function failure()
  {
    $this->code = Sabel_Response::BAD_REQUEST;
  }
  
  public function setErrors(array $errors)
  {
    $this->failure();
    $this->errors = $errors;
  }
}
