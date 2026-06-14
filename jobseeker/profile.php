<?php
$page_title = "My Profile";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

// Get user profile data
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
?>

<!-- Profile Header -->
<section class="dashboard-header">
    <div class="container">
        <div class="dashboard-welcome">
            <h1>My Profile</h1>
            <p>This is how employers see your profile</p>
        </div>
        <div class="dashboard-actions">
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            <a href="dashboard.php" class="btn btn-outline">Back to Dashboard</a>
        </div>
    </div>
</section>

<section class="container">
    <div class="card">
        <!-- Modern profile cover -->
        <div class="profile-cover">
            <div class="profile-avatar">
                <?php echo htmlspecialchars(substr($profile['full_name'] ?? 'U', 0, 1)); ?>
            </div>
            <div class="profile-cover-info">
                <h2 class="profile-title"><?php echo htmlspecialchars($profile['full_name'] ?? ''); ?></h2>

                <?php if(!empty($profile['professional_title'])): ?>
                    <p class="profile-professional">
                        <?php echo htmlspecialchars($profile['professional_title']); ?>
                    </p>
                <?php endif; ?>

                <div class="profile-contact">
                    <span>📧 <?php echo htmlspecialchars($profile['email'] ?? ''); ?></span>
                    <?php if(!empty($profile['phone'])): ?>
                        <span class="profile-dot" aria-hidden="true">•</span>
                        <span>📞 <?php echo htmlspecialchars($profile['phone']); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php
        $skills = [];
        if(!empty($profile['skills'])) {
            $skills = array_values(array_filter(array_map('trim', explode(',', $profile['skills']))));
        }
        $skillsCount = count($skills);
        $skillsPreview = array_slice($skills, 0, 8);
        $skillsMore = array_slice($skills, 8);
        ?>

        <?php if($skillsCount > 0): ?>
            <div class="profile-section">
                <h3 class="profile-section-title">Core Skills</h3>

                <div class="profile-skills" aria-label="Jobseeker skills">
                    <?php foreach($skillsPreview as $skill): ?>
                        <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                    <?php endforeach; ?>

                    <?php if(count($skillsMore) > 0): ?>
                        <span id="skills-more" class="skills-more" style="display:none;">
                            <?php foreach($skillsMore as $skill): ?>
                                <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </span>

                        <button type="button" class="btn btn-outline btn-sm profile-skill-toggle" id="skills-toggle">
                            +<?php echo count($skillsMore); ?> more
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if(!empty($profile['bio'])): ?>
            <div class="profile-section">
                <h3 class="profile-section-title">About</h3>
                <p class="profile-bio"><?php echo nl2br(htmlspecialchars($profile['bio'])); ?></p>
            </div>
        <?php endif; ?>

        <?php if(!empty($profile['portfolio_link'])): ?>
            <div class="profile-section">
                <h3 class="profile-section-title">Portfolio</h3>

                <a href="<?php echo htmlspecialchars($profile['portfolio_link']); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                    View Portfolio
                </a>

                <p class="profile-subtle">
                    Employers can open your work samples in a new tab.
                </p>
            </div>
        <?php endif; ?>

        <div class="profile-footer">
            <p>
                Member since: <?php echo !empty($profile['date_joined']) ? date('F Y', strtotime($profile['date_joined'])) : '—'; ?>
            </p>
        </div>
    </div>

    <script>
        (function () {
            const btn = document.getElementById('skills-toggle');
            const more = document.getElementById('skills-more');
            if (!btn || !more) return;

            btn.addEventListener('click', function () {
                const isHidden = more.style.display === 'none';
                more.style.display = isHidden ? 'inline' : 'none';
                btn.textContent = isHidden ? 'Show less' : ('+' + more.querySelectorAll('.skill-tag').length + ' more');
            });
        })();
    </script>
</section>


<?php require_once '../includes/footer.php'; ?>

