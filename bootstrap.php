<?php

chdir(__DIR__);
require_once "vendor/autoload.php";

// load env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();