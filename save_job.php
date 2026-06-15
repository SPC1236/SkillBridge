<?php
// save_job.php - Handle saving/unsaving jobs
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'jobseeker') {
    header('Location: ' . SITE_URL . '/auth/login.php');
    exit;
}

if(isset($_GET['job_id']) && is_numeric($_GET['job_id'])) {
    $user_id = $_SESSION['user_id'];
    $job_id = $_GET['job_id'];
    $action = isset($_GET['action']) ? $_GET['action'] : 'save';
    
    $db = new Database();
    $conn = $db->getConnection();
    
    if($action == 'save') {
        // Save the job
        $sql = "INSERT IGNORE INTO saved_jobs (user_id, job_id) VALUES (:user_id, :job_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':job_id', $job_id);
        $stmt->execute();
        
        $_SESSION['message'] = 'Job saved successfully!';
    } else {
        // Unsave the job
        $sql = "DELETE FROM saved_jobs WHERE user_id = :user_id AND job_id = :job_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':job_id', $job_id);
        $stmt->execute();
        
        $_SESSION['message'] = 'Job removed from saved!';
    }
}

// Redirect back to the referring page
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard.php';
header('Location: ' . $referrer);
exit;
?>