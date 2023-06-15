<?php

$create->column("user_id")->type(_INT)->nullable(false);
$create->column("follow_id")->type(_INT)->nullable(false);
$create->column("created_at")->type(_DATETIME)->nullable(false);

$create->fkey("user_id")->table("user")->column("id");
$create->fkey("follow_id")->table("user")->column("id");
$create->primary(array("user_id", "follow_id"));
$create->options("engine", "InnoDB");
