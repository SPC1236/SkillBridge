<?php
// Start session and include master configuration settings
require_once '../includes/config.php';

// Force session checking — ensure an unauthenticated visitor cannot trigger updates
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'employer') {
    // If the user isn't logged in as an employer, throw them out to login layer
    header('Location: ' . SITE_URL . '/auth/login.php');
    exit();
}

// Ensure the script only runs for inbound POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Gather form input data & Sanitize to secure baseline strings
    $company_name = isset($_POST['company_name']) ? trim(htmlspecialchars($_POST['company_name'])) : '';
    $industry     = isset($_POST['industry'])     ? trim(htmlspecialchars($_POST['industry']))     : '';
    $website      = isset($_POST['website'])      ? trim(filter_var($_POST['website'], FILTER_SANITIZE_URL)) : '';
    $email        = isset($_POST['email'])        ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
    $phone        = isset($_POST['phone'])        ? trim(htmlspecialchars($_POST['phone']))         : '';
    $address      = isset($_POST['address'])      ? trim(htmlspecialchars($_POST['address']))       : '';
    $bio          = isset($_POST['bio'])          ? trim(htmlspecialchars($_POST['bio']))           : '';
    
    // Extract internal identifier reference from session
    $user_id = $_SESSION['user_id'];
    
    // 2. Validate mandatory business criteria entries
    if (empty($company_name) || empty($email)) {
        $_SESSION['error_message'] = "Company Name and Public Business Email are strictly required fields.";
        header('Location: profile.php');
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Please provide a valid structure for your business email address.";
        header('Location: profile.php');
        exit();
    }

    try {
        // 3. Bind to core Database Instance (assumes $db or $pdo is instantiated inside config.php)
        // If your database global uses a different variable name, update it here (e.g., global $pdo;)
        if (!isset($db)) {
            throw new Exception("Core database engine adapter connection reference not detected.");
        }
        
        // Prepare the target update query pattern matching your database schema structure
        $query = "UPDATE users SET 
                    company_name = :company_name, 
                    industry = :industry, 
                    website = :website, 
                    email = :email, 
                    phone = :phone, 
                    address = :address, 
                    bio = :bio 
                  WHERE id = :id";
                  
        $stmt = $db->prepare($query);
        
        // Safe mapping compilation values
        $stmt->bindValue(':company_name', $company_name, PDO::PARAM_STR);
        $stmt->bindValue(':industry', $industry, PDO::PARAM_STR);
        $stmt->bindValue(':website', $website, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindValue(':address', $address, PDO::PARAM_STR);
        $stmt->bindValue(':bio', $bio, PDO::PARAM_STR);
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
        
        // 4. Fire execution run sequence
        if ($stmt->execute()) {
            // Update active state session values instantly to reflect new name parameters across the navbar/sidebar components
            $_SESSION['company_name'] = $company_name;
            
            // Set dynamic banner message feedback parameter tracking values
            $_SESSION['success_message'] = "Your company profile setup parameters were updated successfully!";
        } else {
            $_SESSION['error_message'] = "The system processing framework encountered an update bottleneck. Please try again.";
        }
        
    } catch (PDOException $e) {
        // Log clean trace errors securely behind runtime variables instead of dumping layout internals publicly
        error_log("Profile Update SQL Execution Defect: " . $e->getMessage());
        $_SESSION['error_message'] = "A secure transaction error occurred while matching records against the persistence layer.";
    } catch (Exception $e) {
        error_log("Profile Profile General Defect: " . $e->getMessage());
        $_SESSION['error_message'] = "Processing Failure: " . $e->getMessage();
    }
    
    // 5. Safely redirect back to profile setup views
    header('Location: profile.php');
    exit();
    
} else {
    // Block manual access bypass routing paths 
    header('HTTP/1.1 405 Method Not Allowed');
    echo "Direct method request call configurations are strictly restricted from executing tasks manually.";
    exit();
}