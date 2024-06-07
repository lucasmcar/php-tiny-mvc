<?php 

use App\Utils\DotEnvUtil;

DotEnvUtil::loadEnv('../.env');

define('JWT_TOKEN', $_ENV['JWT_TOKEN']);