<?php
// Database configuration
// If DB_HOST environment variable is defined (e.g. on Vercel), use it.
// Otherwise, fall back to local Plesk configuration.
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'wafawef');
define('DB_USER', getenv('DB_USER') ?: 'wearawer');
define('DB_PASS', getenv('DB_PASS') ?: 'V^hdDdc8$0t6wrYt');

