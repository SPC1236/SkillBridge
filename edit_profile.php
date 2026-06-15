<?php
$page_title = "Edit Profile";
require_once '../includes/config.php';
require_once '../includes/database.php'; // ADD THIS
require_once '../includes/header.php';
require_once '../includes/auth_check.php';


// Get current profile data
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM job_seekers WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$profile = $stmt->fetch();

if(!$profile) {
    header('Location: dashboard.php');
    exit;
}


// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $professional_title = trim($_POST['professional_title']);
    $skills = trim($_POST['skills']);
    $bio = trim($_POST['bio']);
    $portfolio_link = trim($_POST['portfolio_link']);

    $update_sql = "UPDATE job_seekers SET 
                    full_name = :full_name,
                    phone = :phone,
                    professional_title = :professional_title,
                    skills = :skills,
                    bio = :bio,
                    portfolio_link = :portfolio_link
                   WHERE id = :id";

    $stmt = $conn->prepare($update_sql);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':professional_title', $professional_title);
    $stmt->bindParam(':skills', $skills);
    $stmt->bindParam(':bio', $bio);
    $stmt->bindParam(':portfolio_link', $portfolio_link);
    $stmt->bindParam(':id', $_SESSION['user_id']);

    if($stmt->execute()) {
        $_SESSION['full_name'] = $full_name;
        header('Location: profile.php?success=updated');
        exit;
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}
?>

<section class="dashboard-header">
    <div class="container">
        <div class="dashboard-welcome">
            <h1>Edit Profile</h1>
            <p>Update your professional information</p>
        </div>
        <div class="dashboard-actions">
            <a href="profile.php" class="btn btn-outline">View Profile</a>
            <a href="dashboard.php" class="btn btn-outline">Back to Dashboard</a>
        </div>
    </div>
</section>

<section class="container">
    <div class="card">
        <?php if(isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success">Profile updated successfully!</div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="full_name" class="form-label">Full Name *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" 
                       value="<?php echo htmlspecialchars($profile['full_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="professional_title" class="form-label">Professional Title</label>
                <input type="text" id="professional_title" name="professional_title" class="form-control" 
                       value="<?php echo htmlspecialchars($profile['professional_title']); ?>" 
                       placeholder="e.g., Senior Web Developer, UX Designer">
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" id="phone" name="phone" class="form-control" 
                       value="<?php echo htmlspecialchars($profile['phone']); ?>">
            </div>

            <div class="form-group">
                <label for="skills" class="form-label">Skills</label>
                <textarea id="skills" name="skills" class="form-control" rows="3" 
                          placeholder="List your skills separated by commas (e.g., HTML, CSS, JavaScript, PHP)"><?php echo htmlspecialchars($profile['skills']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="bio" class="form-label">Bio/Description</label>
                <textarea id="bio" name="bio" class="form-control" rows="5" 
          placeholder="Tell employers about yourself, your experience, and what you can offer..."><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
            </div>

          <div class="form-group">
    <label for="portfolio_link" class="form-label">Portfolio Link</label>
    <input type="url" id="portfolio_link" name="portfolio_link" class="form-control" 
           value="<?php echo htmlspecialchars($profile['portfolio_link'] ?? ''); ?>" 
           placeholder="https://yourportfolio.com">
</div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="profile.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>