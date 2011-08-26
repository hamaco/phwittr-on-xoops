<?php

$create->column("user_id")->type(_INT)->nullable(false);
$create->column("follow_id")->type(_INT)->nullable(false);
$create->column("created_at")->type(_DATETIME)->nullable(false);

$create->fkey("user_id")->table(XOOPS_DB_PREFIX . "_users")->column("uid");
$create->fkey("follow_id")->table(XOOPS_DB_PREFIX . "_users")->column("uid");
$create->primary(array("user_id", "follow_id"));
$create->options("engine", "InnoDB");
