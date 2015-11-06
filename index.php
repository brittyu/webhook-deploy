<?php

require_once 'config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Webhook\Github;
use Webhook\ResoverPost;

$webhook = new Github($config, ResoverPost::class);
$webhook->execute();
