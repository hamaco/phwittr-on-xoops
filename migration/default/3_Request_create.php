<?php

$create->column("user_id")->type(_INT)->nullable(false);
$create->column("request_id")->type(_INT)->nullable(false);
$create->column("created_at")->type(_DATETIME)->nullable(false);

$create->fkey("user_id")->table(XOOPS_DB_PREFIX . "_users")->column("uid");
$create->fkey("request_id")->table(XOOPS_DB_PREFIX . "_users")->column("uid");
$create->primary(array("user_id", "request_id"));
$create->options("engine", "InnoDB");
