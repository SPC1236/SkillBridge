<?php
require_once '../includes/config.php';
require_once '../includes/database.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if(empty($email) || empty($password) || strlen($password) < 6) {
        header('Location: register_' . $role . '.php?error=password');
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    try {
        // Check if email already exists
        if($role == 'jobseeker') {
            $check_sql = "SELECT id FROM job_seekers WHERE email = :email";
            $required_fields = ['full_name'];
        } else {
            $check_sql = "SELECT id FROM employers WHERE email = :email";
            $required_fields = ['company_name', 'contact_person'];
        }

        // Validate required fields
        foreach($required_fields as $field) {
            if(empty(trim($_POST[$field]))) {
                header('Location: register_' . $role . '.php?error=missing');
                exit;
            }
        }

        $stmt = $conn->prepare($check_sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if($stmt->fetch()) {
            header('Location: register_' . $role . '.php?error=email_exists');
            exit;
        }

        // Insert new user
        if($role == 'jobseeker') {
            $sql = "INSERT INTO job_seekers (full_name, email, password, phone, professional_title, skills) 
                    VALUES (:full_name, :email, :password, :phone, :professional_title, :skills)";
        } else {
            $sql = "INSERT INTO employers (company_name, email, password, contact_person, company_phone) 
                    VALUES (:company_name, :email, :password, :contact_person, :company_phone)";
        }

        $stmt = $conn->prepare($sql);
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Bind common parameters
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        // Bind role-specific parameters
        if($role == 'jobseeker') {
            $stmt->bindParam(':full_name', trim($_POST['full_name']));
            $stmt->bindParam(':phone', trim($_POST['phone']));
            $stmt->bindParam(':professional_title', trim($_POST['professional_title']));
            $stmt->bindParam(':skills', trim($_POST['skills']));
        } else {
            $stmt->bindParam(':company_name', trim($_POST['company_name']));
            $stmt->bindParam(':contact_person', trim($_POST['contact_person']));
            $stmt->bindParam(':company_phone', trim($_POST['company_phone']));
        }

        if($stmt->execute()) {
            header('Location: login.php?success=registered');
            exit;
        } else {
            header('Location: register_' . $role . '.php?error=unknown');
            exit;
        }

    } catch(PDOException $e) {
        header('Location: register_' . $role . '.php?error=unknown');
        exit;
    }
} else {
    header('Location: register_jobseeker.php');
    exit;
}
?>