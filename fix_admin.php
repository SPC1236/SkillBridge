<?php
require_once 'includes/config.php';
require_once 'includes/database.php';

$db = new Database();
$conn = $db->getConnection();

$hash = password_hash('youcanneverstopme', PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE admin SET password = :password WHERE username = 'sarah'");
$stmt->bindParam(':password', $hash);
$stmt->execute();

echo "✅ Done! You can now log in with:<br>";
echo "Username: <strong>sarah</strong><br>";
echo "Password: <strong>youcanneverstopme</strong><br>";
echo "<br><strong style='color:red'>Delete this file now!</strong>";
?>