<?php

$create->column("id")->type(_INT)->primary(true)->increment(true);
$create->column("user_name")->type(_STRING)->length(20)->nullable(false);
$create->column("email")->type(_STRING)->nullable(false);
$create->column("password")->type(_STRING)->length(32)->nullable(false);
$create->column("image")->type(_STRING)->length(40)->value(DEFAULT_IMAGE_NAME);
$create->column("private_flag")->type(_BOOL)->value(false);
$create->column("delete_flag")->type(_BOOL)->value(false);
$create->column("act_key")->type(_STRING)->length(32);
$create->column("created_at")->type(_DATETIME)->nullable(false);
$create->column("updated_at")->type(_DATETIME)->nullable(false);

$create->unique("user_name");
$create->unique("email");
$create->options("engine", "InnoDB");
