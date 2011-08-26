<?php

class Aspects_UserCache implements Sabel_Aspect_MethodInterceptor
{
  public function invoke(Sabel_Aspect_MethodInvocation $inv)
  {
    try {
      $result = $inv->proceed();
      $method = str_replace("_", "", get_class($inv->getThis()));
      $method = lcfirst($method . ucfirst($inv->getMethod()->getName()));
      
      if (method_exists($this, $method)) {
        $args = $inv->getArguments();
        $args[] = $result;
        call_user_func_array(array($this, $method), $args);
      }
      
      return $result;
    } catch (Exception $e) {
      throw $e;
    }
  }
  
  protected function logicsStatusPost($userId, $comment, $result)
  {
    if ($result->isFailure()) return;
    
    $cache = UserCache::getInstance();
    list ($fp, $data) = $cache->lock($userId);
    
    if ($data) {
      $data["statuses"]++;
      $data["comment"] = $comment;
      $cache->write($fp, $data);
    }
  }
  
  protected function logicsStatusRemove($userId, $statusId, $result)
  {
    if ($result->isFailure()) return;
    
    $cache = UserCache::getInstance();
    list ($fp, $data) = $cache->lock($userId);
    
    if ($data) {
      $data["statuses"]--;
      $data["comment"] = Status::getLatestCommentByUserId($userId);
      $cache->write($fp, $data);
    }
  }
  
  protected function logicsFollowAdd($userId, $targetId, $result)
  {
    if ($result->isFailure() || $result->addType !== "follow") return;
    
    $cache = UserCache::getInstance();
    list ($fp, $data) = $cache->lock($userId);
    
    if ($data) {
      $data["friends"]++;
      $cache->write($fp, $data);
    }
    
    list ($fp, $data) = $cache->lock($targetId);
    
    if ($data) {
      $data["followers"]++;
      $cache->write($fp, $data);
    }
  }
  
  protected function logicsFollowRemove($userId, $targetId, $result)
  {
    if ($result->isFailure() || $result->removeType !== "follow") return;
    
    $cache = UserCache::getInstance();
    list ($fp, $data) = $cache->lock($userId);
    
    if ($data) {
      $data["friends"]--;
      $cache->write($fp, $data);
    }
    
    list ($fp, $data) = $cache->lock($targetId);
    
    if ($data) {
      $data["followers"]--;
      $cache->write($fp, $data);
    }
  }
  
  protected function logicsFollowAccept($userId, $requestorId, $result)
  {
    if ($result->isFailure()) return;
    
    $result->addType = "follow";
    $this->logicsFollowAdd($requestorId, $userId, $result);
  }
}
