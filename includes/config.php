<?php
// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Your existing config...
?>

<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'freelance_portal');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration - MAKE SURE THIS IS CORRECT
define('SITE_NAME', 'SkillBridge');
define('SITE_URL', 'http://localhost/freelance_portal');

// File upload paths
define('UPLOAD_RESUME_PATH', 'assets/images/uploads/resumes/');
define('UPLOAD_PROFILE_PIC_PATH', 'assets/images/uploads/profile_pics/');

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>