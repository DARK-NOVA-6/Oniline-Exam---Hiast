<?php

require_once 'application/config/config.php';

if (isset($config)) {
	$app = new Application(config: $config);
}

