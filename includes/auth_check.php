<?php
// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header('Location: ' . SITE_URL . '/auth/login.php');
    exit;
}

// Get current page role requirement
$current_page = basename($_SERVER['PHP_SELF']);
$page_directory = basename(dirname($_SERVER['PHP_SELF']));

// Define allowed roles for each directory
$allowed_roles = [
    'jobseeker' => ['jobseeker'],
    'employer' => ['employer'], 
    'admin' => ['admin']
];

// Check if user has permission to access this page
if(isset($allowed_roles[$page_directory]) && !in_array($_SESSION['user_role'], $allowed_roles[$page_directory])) {
    header('Location: ' . SITE_URL . '/auth/login.php');
    exit;
}
?>