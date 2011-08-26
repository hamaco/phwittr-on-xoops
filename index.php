<?php

require_once("../../mainfile.php");
require_once(XOOPS_ROOT_PATH . "/header.php");

ob_start();

// define("RUN_BASE", dirname(realpath(".")));
define("RUN_BASE",  dirname(__FILE__));

//require ("Sabel"  . DIRECTORY_SEPARATOR . "Sabel.php");
require (RUN_BASE . DIRECTORY_SEPARATOR . "Sabel"  . DIRECTORY_SEPARATOR . "Sabel.php");
require (RUN_BASE . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "INIT.php");
require (RUN_BASE . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "environment.php");

if (!defined("ENVIRONMENT")) {
  echo "SABEL FATAL ERROR: must define ENVIRONMENT in config/environment.php";
  exit;
}

if ((ENVIRONMENT & PRODUCTION) > 0) {
  Sabel::init();
  $out = Sabel_Bus::create()->run(new Config_Bus());
  Sabel::shutdown();
} else {
  $out = Sabel_Bus::create()->run(new Config_Bus());
}

if (Sabel_Context::getContext()->getBus()->get("AJAX_REQUEST")) {
  echo $out;
  exit;
}

$root =& XCube_Root::getSingleton();
$target =& $root->mContext->mModule->getRenderTarget();
$target->setResult($out);
$target->setAttribute('legacy_buffertype', null);

$theme =& $root->mController->_mStrategy->getMainThemeObject();
$renderSystem =& $root->getRenderSystem($theme->get('render_system'));
$renderSystem->_commonPrepareRender();

if (isset($GLOBALS['xoopsUserIsAdmin'])) {
    $renderSystem->mXoopsTpl->assign('xoops_isadmin', $GLOBALS['xoopsUserIsAdmin']);
}
$renderSystem->mXoopsTpl->assign('xoops_block_header', '');
$renderSystem->mXoopsTpl->assign('xoops_module_header', '');

$theme =& $root->mController->_mStrategy->getMainThemeObject();
$renderSystem =& $root->getRenderSystem($theme->get('render_system'));
$renderSystem->_commonPrepareRender();

if (isset($GLOBALS['xoopsUserIsAdmin'])) {
    $renderSystem->mXoopsTpl->assign('xoops_isadmin', $GLOBALS['xoopsUserIsAdmin']);
}
$renderSystem->mXoopsTpl->assign('xoops_block_header', '');


require_once(XOOPS_ROOT_PATH . "/footer.php");

ob_flush();
