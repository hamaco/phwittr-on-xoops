<?php

class Aspects_Transaction implements Sabel_Aspect_MethodInterceptor
{
  public function invoke(Sabel_Aspect_MethodInvocation $inv)
  {
    Sabel_Db_Transaction::activate();
    
    try {
      $result = $inv->proceed();
      Sabel_Db_Transaction::commit();
      return $result;
    } catch (Exception $e) {
      Sabel_Db_Transaction::rollback();
      l($e->getMessage(), SBL_LOG_ERR);
      throw $e;
    }
  }
}
