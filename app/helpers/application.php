<?php

function h($string, $charset = null)
{
  return htmlescape($string, $charset);
}

function a($uri, $anchor, $uriQuery = "")
{
  if ($uriQuery === "") {
    return sprintf('<a href="%s">%s</a>', uri($uri), $anchor);
  } else {
    return sprintf('<a href="%s?%s">%s</a>', uri($uri), $uriQuery, $anchor);
  }
}

function ah($param, $anchor, $uriQuery = "")
{
  return a($param, h($anchor), $uriQuery);
}

/**
 * create uri for css, image, js, etc...
 */
function linkto($file)
{
  if ($bus = Sabel_Context::getContext()->getBus()) {
    if ($bus->get("NO_VIRTUAL_HOST")) {
      return dirname($_SERVER["SCRIPT_NAME"]) . "/" . $file;
    }
  }
  
  return "/" . $file;
}

function get_uri_prefix($secure = false, $absolute = false)
{
  $prefix = "";
  
  if ($secure || $absolute) {
    $server = (isset($_SERVER["SERVER_NAME"])) ? $_SERVER["SERVER_NAME"] : "localhost";
    $prefix = (($secure) ? "https" : "http") . "://" . $server;
  }
  
  if ($bus = Sabel_Context::getContext()->getBus()) {
    if ($bus->get("NO_VIRTUAL_HOST") && isset($_SERVER["SCRIPT_NAME"])) {
      $prefix .= $_SERVER["SCRIPT_NAME"];
    }
    
    if ($bus->get("NO_REWRITE_MODULE") && defined("NO_REWRITE_PREFIX")) {
      $prefix .= "?" . NO_REWRITE_PREFIX . "=";
    }
  }
  
  return $prefix;
}

/**
 * create uri
 */
function uri($param, $secure = false, $absolute = false)
{
  $context = Sabel_Context::getContext();
  $prefix  = get_uri_prefix($secure, $absolute);
  
  return $prefix . "/" . $context->getCandidate()->uri($param);
}

function datetime($date, $format = "m/d H:i")
{
  return date($format, strtotime($date));
}

function mb_trim($string)
{
  $string = new Sabel_Util_String($string);
  return $string->trim()->toString();
}

function showComment($comment)
{
  static $uri = "";
  
  if ($uri === "") {
    $uri = uri("n: users, c: %s, a: ");
  }
  
  $comment = h($comment);
  if (preg_match('/^@(\w{1,20})/', $comment, $matches) === 1) {
    $_uri = sprintf($uri, $matches[1]);
    $comment = preg_replace(
      "/{$matches[0]}/",
      "@<a href=\"{$_uri}\">{$matches[1]}</a>",
      $comment, 1
    );
  }
  
  // @todo ちゃんとやる? <a href="...">...</a>とか.
  $comment = preg_replace(
    '~(https?://[a-zA-Z0-9][\w\-\.]*\.[a-z]+/[\w\-\.\~\*\?\+\$/@&=%#:;,]*)~',
    '<a href="$0" target="_blank">$1</a>',
    $comment
  );
  
  return $comment;
}

function showUpdatedAt($updatedAt)
{
  $time = strtotime($updatedAt);
  $diff = time() - $time;
  
  if ($diff <= 0) {
    return "1秒前";
  } elseif ($diff <= 60) {
    return $diff . "秒前";
  } elseif ($diff < 3600) {
    return floor($diff / 60) . "分前";
  } elseif ($diff < 86400) {
    return floor($diff / 3600) . "時間前";
  } else {
    return date("n月j日", $time);
    return date("h:i A F d, Y", $time);
  }
}

function user_avatarize($uid) {
  require_once(SMARTY_DIR . "plugins/modifier.xoops_user_avatarize.php");

  return smarty_modifier_xoops_user_avatarize($uid);
}
