<?php

/**
 * Used for PHP built-in server
 * NOTE: For testing only!
 *
 * php -S 127.0.0.1:5902 previewer.php if using VPN
 */

chdir(__dir__);
require 'rocket/autoloader.php';
$server = new \dev\DevelopmentServer('/theme');
$server->serve();
