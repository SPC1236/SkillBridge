<?php
$page_title = "Job Seeker Dashboard";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

// Fetch profile data
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM job_seekers WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$profile = $stmt->fetch();

// Calculate profile completion based on exact fields
$fields = [
    'full_name'          => $profile['full_name']          ?? '',
    'professional_title' => $profile['professional_title'] ?? '',
    'phone'              => $profile['phone']              ?? '',
    'skills'             => $profile['skills']             ?? '',
    'bio'                => $profile['bio']                ?? '',
    'portfolio_link'     => $profile['portfolio_link']     ?? '',
];

$filled = 0;
foreach($fields as $value) {
    if(!empty(trim($value))) $filled++;
}

$total = count($fields); 
$completion_percent = round(($filled / $total) * 100);

// Get real profile views from database
$profile_views = $profile['profile_views'] ?? 0;

// Dashboard telemetry variables
$applied_jobs = 0;
$saved_jobs = 0;
$messages_unread = 0;
?>

<!-- Modern Dashboard Layout -->
<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo substr($_SESSION['full_name'] ?? 'U', 0, 1); ?>
            </div>
            <h3><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?></h3>
            <p class="sidebar-role">Freelancer</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="profile.php" class="sidebar-link">
                <i class="fas fa-user"></i>
                <span>My Profile</span>
            </a>
            <a href="edit_profile.php" class="sidebar-link">
                <i class="fas fa-edit"></i>
                <span>Edit Profile</span>
            </a>
            <a href="applied_jobs.php" class="sidebar-link">
                <i class="fas fa-briefcase"></i>
                <span>Applied Jobs</span>
                <span class="badge"><?php echo $applied_jobs; ?></span>
            </a>
            <a href="analytics.php" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/public/contact.php" class="sidebar-link">
                <i class="fas fa-headset"></i>
                <span>Support</span>
            </a>
            <div class="stat-info" style="padding: 1rem 0.75rem; margin-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <div class="stat-value"><?php echo $saved_jobs; ?></div>
                <div class="stat-label">Saved Jobs</div>
                <a href="saved_jobs.php" style="font-size: 0.7rem; color: var(--accent-primary); text-decoration: none;">View all →</a>
            </div>
        </nav>
        
        <div class="sidebar-footer">
            <a href="<?php echo SITE_URL; ?>/auth/logout.php" class="sidebar-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">
        <!-- Header -->
        <div class="dashboard-header">
            <div>
                <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?>! 👋</h1>
                <p class="text-muted" style="margin-top: 0.15rem;">Here's what's happening with your freelance profile</p>
            </div>
            <div class="dashboard-actions">
                <a href="profile.php" class="btn btn-secondary">
                    <i class="fas fa-eye"></i> View Profile
                </a>
                <a href="edit_profile.php" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>

        <!-- Stats Widgets -->
        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-eye"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($profile_views); ?></div>
                    <div class="stat-label">Profile Views</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $applied_jobs; ?></div>
                    <div class="stat-label">Applied Jobs</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-bookmark"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $saved_jobs; ?></div>
                    <div class="stat-label">Saved Jobs</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-envelope"></i></div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $messages_unread; ?></div>
                    <div class="stat-label">Unread Messages</div>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="dashboard-grid-2">
            <!-- Profile Completion Widget -->
            <div class="card">
                <div class="card-header">
                    <h3>Profile Completion</h3>
                    <span class="badge badge-success" style="background:#10b981; padding:2px 8px; border-radius:12px; color:#fff; font-size:0.75rem;"><?php echo $completion_percent; ?>%</span>
                </div>
                <div class="progress-bar-container" style="background: rgba(255,255,255,0.05); height: 8px; border-radius: 4px; overflow: hidden; margin: 1rem 0;">
                    <div class="progress-bar-fill" style="width: <?php echo $completion_percent; ?>%; height:100%; background:var(--accent-primary);"></div>
                </div>
                <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 1.25rem;">
                    <?php if($completion_percent < 100): ?>
                        <?php
                        $missing = [];
                        $labels = [
                            'full_name'          => 'Full Name',
                            'professional_title' => 'Professional Title',
                            'phone'              => 'Phone Number',
                            'skills'             => 'Skills',
                            'bio'                => 'Bio',
                            'portfolio_link'     => 'Portfolio Link',
                        ];
                        foreach($fields as $key => $value) {
                            if(empty(trim($value))) $missing[] = $labels[$key];
                        }
                        ?>
                        <i class="fas fa-info-circle" style="color: var(--accent-primary); margin-right: 0.15rem;"></i> Add your <strong><?php echo implode(', ', array_slice($missing, 0, 2)); ?></strong> to reach 100%
                    <?php else: ?>
                        <i class="fas fa-check-circle" style="color: #10b981; margin-right: 0.15rem;"></i> Perfect! Your profile is complete
                    <?php endif; ?>
                </p>
                <a href="edit_profile.php" class="btn btn-outline" style="display:block; text-align:center; padding:0.5rem; border:1px solid var(--accent-primary); color:var(--accent-primary); text-decoration:none; border-radius:6px;">
                    <i class="fas fa-upload"></i> Complete Your Profile
                </a>
            </div>

            <!-- Saved Opportunities Widget -->
            <div class="card">
                <div class="card-header">
                    <h3>Saved Opportunities</h3>
                </div>
                <div style="padding: 1rem 0;">
                    <div class="stat-value" style="color: #f59e0b; font-size: 2rem; font-weight:700;"><?php echo $saved_jobs; ?></div>
                    <p style="margin: 0; font-size: 0.85rem; color: var(--text-muted); display: flex; justify-content: space-between; align-items: center;">
                        <span>Bookmarked positions</span>
                        <a href="saved_jobs.php" style="color: var(--accent-primary); font-weight: 600; text-decoration:none;">View all →</a>
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>