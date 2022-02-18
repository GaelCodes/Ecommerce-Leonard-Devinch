<?php

define("__ROOT__", dirname(dirname(dirname(dirname(dirname(__FILE__))))));
require_once __ROOT__ . "/loadDependencies.php";

require_once "../clientsAPI.php";
ClientsAPI::register();
