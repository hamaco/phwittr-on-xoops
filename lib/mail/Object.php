<?php

class Mail_Object extends Sabel_Object implements Mail
{
  protected $config = null;
  
  public function __construct()
  {
    $mailConfig   = new Config_Mail();
    $this->config = $mailConfig->configure();
  }
  
  public function send($sender, $recipient, $subject, $body)
  {
    $smtp = $this->getSmtp();
    $smtp->setFrom($sender);
    $smtp->setTo($recipient);
    $smtp->setSubject($subject);
    $smtp->setBody($body);
    $smtp->send();
  }
  
  public function sendActivationKey($recipient, $key)
  {
    $subject = "Phwittr本登録用メール";
    $body = <<<BODY
Phwittrへのご登録ありがとうございます。

Phwittrは「いまなにしてる？」という質問に短い文章で答えることによって、
友だちや家族、職場の同僚とつながり合うサービスです。

登録を完了するために下記のURLをクリックしてください。

http://%s
BODY;
    
    $body = sprintf($body, $_SERVER["SERVER_NAME"] . uri("c: register, a: auth, param: {$key}"));
    $this->send("register@" . $this->config["domain"], $recipient, $subject, $body);
  }
  
  protected function getSmtp()
  {
    $smtp = new Sabel_Mail($this->config["charset"]);
    $smtp->setSender(new Sabel_Mail_Sender_Smtp(array(
      "host" => $this->config["host"],
      "port" => $this->config["port"],
    )));
    
    return $smtp;
  }
}
