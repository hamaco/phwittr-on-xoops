<?php

$create->column("id")->type(_INT)->primary(true)->increment(true);
$create->column("user_id")->type(_INT)->nullable(false);
$create->column("reply_user_id")->type(_INT);
$create->column("comment")->type(_STRING)->length(140)->nullable(false);
$create->column("created_at")->type(_DATETIME)->nullable(false);

$create->fkey("user_id")->table(XOOPS_DB_PREFIX . "_users")->column("uid");
$create->fkey("reply_user_id")->table(XOOPS_DB_PREFIX . "_users")->column("uid");
$create->options("engine", "InnoDB");
