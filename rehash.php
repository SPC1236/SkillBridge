<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$db = new Database();
$conn = $db->getConnection();

$hash = password_hash('youcanneverstopme', PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE admin SET password = :password WHERE username = 'admin'");
$stmt->bindParam(':password', $hash);
$stmt->execute();

echo "✅ Done! Login with:<br>";
echo "Username: <strong>admin</strong><br>";
echo "Password: <strong>youcanneverstopme</strong><br>";
echo "<br><strong style='color:red'>Delete this file now!</strong>";
?>