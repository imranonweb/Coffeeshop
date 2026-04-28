<?php
// Site configuration
define('SITE_NAME', 'Coffee Haven');
define('SITE_URL', 'http://localhost/project');
define('CURRENCY', '৳');

// Image upload settings
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Timezone
date_default_timezone_set('America/New_York');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
