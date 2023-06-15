<?php

$create->column("id")->type(_INT)->primary(true)->increment(true);
$create->column("user_id")->type(_INT)->nullable(false);
$create->column("reply_user_id")->type(_INT);
$create->column("comment")->type(_STRING)->length(140)->nullable(false);
$create->column("created_at")->type(_DATETIME)->nullable(false);

$create->fkey("user_id")->table("user")->column("id");
$create->fkey("reply_user_id")->table("user")->column("id");
$create->options("engine", "InnoDB");
