<?php

require_once __DIR__ . '/vendor/autoload.php';

use Webhook\Github;
use Webhook\ResolvePost;

$config = require_once 'config.php';

$webhook = new Github($config, new ResolvePost);
$webhook->execute();
