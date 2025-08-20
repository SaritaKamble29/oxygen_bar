<?php
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "root"); // If you use a password, add it here
define("DB_NAME", "inventory_system");
define("DB_PORT", 3307);


if (!defined('DS')) {
    define("DS", DIRECTORY_SEPARATOR);
}

if (!defined('LIB_PATH_INC')) {
    define("LIB_PATH_INC", __DIR__);
}
?>
