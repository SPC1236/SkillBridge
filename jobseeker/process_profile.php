<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $professional_title = trim($_POST['professional_title']);
    $skills = trim($_POST['skills']);
    $bio = trim($_POST['bio']);
    $portfolio_link = trim($_POST['portfolio_link']);

    // Validate required fields
    if(empty($full_name)) {
        header('Location: edit_profile.php?error=name_required');
        exit;
    }

    // Validate portfolio URL if provided
    if(!empty($portfolio_link) && !filter_var($portfolio_link, FILTER_VALIDATE_URL)) {
        header('Location: edit_profile.php?error=invalid_url');
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    try {
        // Update profile in database
        $sql = "UPDATE job_seekers SET 
                full_name = :full_name,
                phone = :phone,
                professional_title = :professional_title,
                skills = :skills,
                bio = :bio,
                portfolio_link = :portfolio_link
               WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':professional_title', $professional_title);
        $stmt->bindParam(':skills', $skills);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':portfolio_link', $portfolio_link);
        $stmt->bindParam(':id', $_SESSION['user_id']);

        if($stmt->execute()) {
            // Update session data
            $_SESSION['full_name'] = $full_name;
            
            // Check if profile is complete for statistics
            $profile_complete = !empty($professional_title) && !empty($skills) && !empty($bio);
            
            if($profile_complete) {
                header('Location: profile.php?success=updated_complete');
            } else {
                header('Location: profile.php?success=updated');
            }
            exit;
        } else {
            header('Location: edit_profile.php?error=update_failed');
            exit;
        }

    } catch(PDOException $e) {
        error_log("Profile update error: " . $e->getMessage());
        header('Location: edit_profile.php?error=database_error');
        exit;
    }
} else {
    // Redirect if not POST request
    header('Location: edit_profile.php');
    exit;
}
?>