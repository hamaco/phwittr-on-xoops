<?php

$create->column("user_id")->type(_INT)->nullable(false);
$create->column("request_id")->type(_INT)->nullable(false);
$create->column("created_at")->type(_DATETIME)->nullable(false);

$create->fkey("user_id")->table("user")->column("id");
$create->fkey("request_id")->table("user")->column("id");
$create->primary(array("user_id", "request_id"));
$create->options("engine", "InnoDB");
