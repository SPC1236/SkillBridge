<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

// Check if admin
if($_SESSION['user_role'] != 'admin') {
    header('Location: ' . SITE_URL . '/auth/login.php');
    exit;
}

// Check if required parameters are provided
if(!isset($_GET['type']) || !isset($_GET['id'])) {
    header('Location: manage_users.php?error=missing_parameters');
    exit;
}

$user_type = $_GET['type'];
$user_id = $_GET['id'];

// Validate user type
if(!in_array($user_type, ['jobseeker', 'employer'])) {
    header('Location: manage_users.php?error=invalid_type');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

try {
    // Prepare the delete query based on user type
    if($user_type == 'jobseeker') {
        $sql = "DELETE FROM job_seekers WHERE id = :id";
        $table_name = "job_seekers";
    } else {
        $sql = "DELETE FROM employers WHERE id = :id";
        $table_name = "employers";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    
    if($stmt->execute()) {
        // Check if any row was affected
        if($stmt->rowCount() > 0) {
            // Log the deletion (you can implement a proper logging system)
            error_log("Admin {$_SESSION['user_id']} deleted $user_type with ID: $user_id");
            
            header('Location: manage_users.php?success=user_deleted');
            exit;
        } else {
            header('Location: manage_users.php?error=user_not_found');
            exit;
        }
    } else {
        header('Location: manage_users.php?error=delete_failed');
        exit;
    }

} catch(PDOException $e) {
    error_log("User deletion error: " . $e->getMessage());
    
    // Check if it's a foreign key constraint error
    if(strpos($e->getMessage(), 'foreign key constraint') !== false) {
        header('Location: manage_users.php?error=constraint_violation');
    } else {
        header('Location: manage_users.php?error=database_error');
    }
    exit;
}
?>