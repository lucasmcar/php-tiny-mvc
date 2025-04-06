<?php 

use App\Utils\DotEnvUtil;


DotEnvUtil::loadEnv(realpath(__DIR__.'/../.env'));

define('JWT_TOKEN', $_ENV['JWT_TOKEN']);