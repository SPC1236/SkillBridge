<?php
require_once '../includes/config.php';
require_once '../includes/database.php';


if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = trim($_POST['role']);
    $password = $_POST['password'];

    // Validate inputs
    if(empty($role) || empty($password)) {
        header('Location: login.php?error=missing');
        exit;
    }

    // Get login credentials based on role
    if($role == 'admin') {
        $username = trim($_POST['username'] ?? '');
        if(empty($username)) {
            header('Location: login.php?error=missing');
            exit;
        }
    } else {
        $email = trim($_POST['email'] ?? '');
        if(empty($email)) {
            header('Location: login.php?error=missing');
            exit;
        }
    }

    $db = new Database();
    $conn = $db->getConnection();

    try {
        switch($role) {
            case 'jobseeker':
                $table = 'job_seekers';
                $user_role = 'jobseeker';
                $login_field = 'email';
                $login_value = $email;
                break;
            case 'employer':
                $table = 'employers';
                $user_role = 'employer';
                $login_field = 'email';
                $login_value = $email;
                break;
            case 'admin':
                $table = 'admin';
                $user_role = 'admin';
                $login_field = 'username';
                $login_value = $username;
                break;
            default:
                header('Location: login.php?error=invalid');
                exit;
        }

        // Prepare SQL query
        if($role == 'admin') {
            $sql = "SELECT * FROM $table WHERE $login_field = :login_value";
        } else {
            $sql = "SELECT * FROM $table WHERE $login_field = :login_value AND is_active = 1";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':login_value', $login_value);
        $stmt->execute();
        $user = $stmt->fetch();

        // Verify password
        if($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user_role;
            $_SESSION['user_email'] = $role == 'admin' ? $user['username'] : $user['email'];
            
            if($role == 'jobseeker') {
                $_SESSION['full_name'] = $user['full_name'];
                header('Location: ' . SITE_URL . '/jobseeker/dashboard.php');
            } elseif($role == 'employer') {
                $_SESSION['company_name'] = $user['company_name'];
                $_SESSION['contact_person'] = $user['contact_person'];
                header('Location: ' . SITE_URL . '/employer/dashboard.php');
            } elseif($role == 'admin') {
                header('Location: ' . SITE_URL . '/admin/dashboard.php');
            }
            exit;
        } else {
            header('Location: login.php?error=invalid');
            exit;
        }

    } catch(PDOException $e) {
        header('Location: login.php?error=invalid');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>