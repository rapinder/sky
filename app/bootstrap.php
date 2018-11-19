<?php
//Load Config
require_once 'config/config.php';

// Autoload Core Libraries
spl_autoload_register(function ($className) {
    require_once 'lib/' . $className . '.php';
});
