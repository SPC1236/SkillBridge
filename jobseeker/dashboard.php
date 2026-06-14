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

// Calculate profile completion based on exact fields in edit_profile.php
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

$total = count($fields); // 6
$completion_percent = round(($filled / $total) * 100);

// Get real profile views from database
$profile_views = $profile['profile_views'] ?? 0;

// Get applied jobs count (placeholder - replace with actual query if exists)
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
            <a href="#" class="sidebar-link">
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
            <div class="stat-info">
            <div class="stat-value"><?php echo $saved_jobs; ?></div>
            <div class="stat-label">Saved Jobs</div>
            <a href="saved_jobs.php" style="font-size: 0.7rem; color: var(--accent-primary);">View all →</a>
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
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo number_format($profile_views); ?></div>
                    <div class="stat-label">Profile Views</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $applied_jobs; ?></div>
                    <div class="stat-label">Applied Jobs</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bookmark"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $saved_jobs; ?></div>
                    <div class="stat-label">Saved Jobs</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
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
                    <span class="badge badge-success"><?php echo $completion_percent; ?>%</span>
                </div>
                <div class="progress-bar-container" style="margin-bottom: 1.25rem;">
                    <div class="progress-bar-fill" style="width: <?php echo $completion_percent; ?>%"></div>
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
                        <i class="fas fa-info-circle" style="color: var(--accent-primary); margin-right: 0.15rem;"></i> Add your <strong><?php echo implode(', ', array_slice($missing, 0, 3)); ?></strong> to reach 100%
                    <?php else: ?>
                        <i class="fas fa-check-circle" style="color: var(--accent-success); margin-right: 0.15rem;"></i> Perfect! Your profile is complete
                    <?php endif; ?>
                </p>
                <a href="edit_profile.php" class="btn btn-outline" style="width: 100%;">
                    <i class="fas fa-upload"></i> Complete Your Profile
                </a>
            </div>

            <!-- Job Recommendations Widget -->
            <div class="card">
                <div class="card-header">
                    <h3>Recommended Jobs</h3>
                </div>
                <div class="job-recommendation-list">
                    <div class="job-card">
                        <div class="job-card-header">
                            <div>
                                <h4 class="job-card-title">Senior Web Developer</h4>
                                <p class="job-card-meta">TechCorp • Remote</p>
                            </div>
                            <span class="job-card-salary">$80-120/hr</span>
                        </div>
                        <div class="job-card-tags">
                            <span class="job-card-tag">React</span>
                            <span class="job-card-tag">Node.js</span>
                            <span class="job-card-tag">TypeScript</span>
                        </div>
                    </div>
                    <div class="job-card">
                        <div class="job-card-header">
                            <div>
                                <h4 class="job-card-title">UI/UX Designer</h4>
                                <p class="job-card-meta">DesignStudio • Hybrid</p>
                            </div>
                            <span class="job-card-salary">$60-90/hr</span>
                        </div>
                        <div class="job-card-tags">
                            <span class="job-card-tag">Figma</span>
                            <span class="job-card-tag">Adobe XD</span>
                            <span class="job-card-tag">Prototyping</span>
                        </div>
                    </div>
                </div>
                <a href="#" class="btn btn-outline" style="width: 100%; margin-top: 1.25rem;">
                    <i class="fas fa-search"></i> Browse All Jobs
                </a>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="card">
            <div class="card-header" style="flex-direction: column; align-items: flex-start; gap: 0.25rem; margin-bottom: 1.5rem;">
                <h3>Quick Actions</h3>
                <p class="text-muted" style="font-size: 0.8rem; margin: 0;">Manage your freelance profile</p>
            </div>
            <div class="quick-actions-grid">
                <a href="edit_profile.php" class="quick-action-card">
                    <i class="fas fa-user-edit"></i>
                    <strong>Update Profile</strong>
                    <p>Keep your info fresh</p>
                </a>
                <a href="profile.php" class="quick-action-card">
                    <i class="fas fa-id-card"></i>
                    <strong>View Profile</strong>
                    <p>See employer view</p>
                </a>
                <a href="#" class="quick-action-card">
                    <i class="fas fa-folder-open"></i>
                    <strong>Add Portfolio</strong>
                    <p>Showcase your work</p>
                </a>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <div>
                        <div class="stat-value"><?php echo $total_saved; ?></div>
                        <div class="stat-label">Saved Jobs</div>
                        <a href="saved_jobs.php" style="font-size: 0.75rem; color: var(--accent-primary);">View all →</a>
                    </div>
                </div>
                
                <a href="analytics.php" class="quick-action-card">
                    <i class="fas fa-chart-line"></i>
                    <strong>View Analytics</strong>
                    <p>Track your performance</p>
                </a>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="card" style="margin-top: 1.5rem;">
            <div class="card-header">
                <h3>Recent Activity</h3>
            </div>
            <div class="activity-timeline">
                <div class="activity-item">
                    <div class="activity-marker"></div>
                    <div class="activity-content">
                        <p>Your profile was viewed by <strong>TechCorp</strong></p>
                        <span class="activity-time">2 hours ago</span>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-marker"></div>
                    <div class="activity-content">
                        <p>You updated your <strong>Skills</strong></p>
                        <span class="activity-time">Yesterday</span>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-marker"></div>
                    <div class="activity-content">
                        <p>New job match: <strong>Frontend Developer</strong></p>
                        <span class="activity-time">3 days ago</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once '../includes/footer.php'; ?>