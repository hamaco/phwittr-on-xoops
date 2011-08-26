<?php

interface Mail
{
  public function send($sender, $recipient, $subject, $body);
  public function sendActivationKey($recipient, $key);
}
