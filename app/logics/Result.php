<?php

class Logics_Result extends Sabel_ValueObject
{
  protected $isSuccess = true;
  protected $errors = array();
  
  public function isSuccess()
  {
    return $this->isSuccess;
  }
  
  public function isFailure()
  {
    return !$this->isSuccess;
  }
  
  public function success()
  {
    $this->isSuccess = true;
    
    return $this;
  }
  
  public function failure()
  {
    $this->isSuccess = false;
    
    return $this;
  }
  
  public function setErrors(array $errors)
  {
    $this->errors = $errors;
    $this->failure();
    
    return $this;
  }
  
  public function getErrors()
  {
    return $this->errors;
  }
  
  public function hasError()
  {
    return !empty($this->errors);
  }
}
