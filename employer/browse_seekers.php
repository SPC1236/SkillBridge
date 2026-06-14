<?php
$page_title = "Browse Freelancers";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

// Get search parameters
$search = $_GET['search'] ?? '';
$skill_filter = $_GET['skills'] ?? '';

// Build query
$db = new Database();
$conn = $db->getConnection();

$sql = "SELECT * FROM job_seekers WHERE is_active = 1";
$params = [];

if(!empty($search)) {
    $sql .= " AND (full_name LIKE :search OR professional_title LIKE :search OR bio LIKE :search)";
    $params[':search'] = "%$search%";
}

if(!empty($skill_filter)) {
    $sql .= " AND skills LIKE :skills";
    $params[':skills'] = "%$skill_filter%";
}

$sql .= " ORDER BY date_joined DESC";

$stmt = $conn->prepare($sql);
foreach($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$freelancers = $stmt->fetchAll();
?>

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo substr($_SESSION['company_name'] ?? 'E', 0, 1); ?>
            </div>
            <h3><?php echo htmlspecialchars($_SESSION['company_name'] ?? 'Employer'); ?></h3>
            <p class="sidebar-role">Employer</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="browse_seekers.php" class="sidebar-link active">
                <i class="fas fa-search"></i>
                <span>Find Talent</span>
            </a>
            <a href="search.php" class="sidebar-link">
                <i class="fas fa-filter"></i>
                <span>Advanced Search</span>
            </a>
            <a href="profile.php" class="sidebar-link">
                <i class="fas fa-building"></i>
                <span>Company Profile</span>
            </a>
            <a href="analytics.php" class="sidebar-link">
                <i class="fas fa-chart-line"></i>
                <span>Analytics</span>
            </a>
            <a href="<?php echo SITE_URL; ?>/public/contact.php" class="sidebar-link">
                <i class="fas fa-headset"></i>
                <span>Support</span>
            </a>
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
        <div class="dashboard-header">
            <div>
                <h1>Find Talent</h1>
                <p class="text-muted">Browse and connect with skilled freelancers</p>
            </div>
        </div>

        <!-- Search Box -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <form method="GET" action="" class="search-form" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
                <div class="form-group" style="margin: 0;">
                    <label for="search" class="form-label">Search by Name or Title</label>
                    <input type="text" id="search" name="search" class="form-control" 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Search freelancers...">
                </div>
                <div class="form-group" style="margin: 0;">
                    <label for="skills" class="form-label">Filter by Skill</label>
                    <input type="text" id="skills" name="skills" class="form-control" 
                           value="<?php echo htmlspecialchars($skill_filter); ?>" 
                           placeholder="e.g., JavaScript, Design">
                </div>
                <div class="form-group" style="margin: 0;">
                    <button type="submit" class="btn btn-primary" style="height: 42px;">Search</button>
                    <a href="browse_seekers.php" class="btn btn-outline" style="height: 42px;">Clear</a>
                </div>
            </form>
        </div>

        <!-- Results -->
        <h2 style="margin-bottom: 1.5rem;">
            Available Freelancers 
            <span style="color: var(--text-muted); font-size: 1rem;">
                (<?php echo count($freelancers); ?> found)
            </span>
        </h2>

        <?php if(empty($freelancers)): ?>
            <div class="card">
                <div style="text-align: center; padding: 3rem;">
                    <h3 style="margin-bottom: 1rem; color: var(--text-muted);">No freelancers found</h3>
                    <p style="color: var(--text-muted);">Try adjusting your search criteria or <a href="browse_seekers.php" style="color: var(--accent-primary);">browse all freelancers</a>.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="dashboard-grid-2"> <!-- Reusing dashboard grid class -->
                <?php foreach($freelancers as $freelancer): ?>
                    <div class="card">
                        <div style="text-align: center; margin-bottom: 1.5rem;">
                            <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--bg-secondary); 
                                      display: flex; align-items: center; justify-content: center; font-size: 1.5rem; 
                                      margin: 0 auto 1rem; border: 3px solid var(--accent-primary); color: var(--accent-primary);">
                                <?php echo strtoupper(substr($freelancer['full_name'], 0, 1)); ?>
                            </div>
                            <h3><?php echo htmlspecialchars($freelancer['full_name']); ?></h3>
                            <?php if($freelancer['professional_title']): ?>
                                <p style="color: var(--accent-primary); font-weight: 600; margin-bottom: 0.5rem;">
                                    <?php echo htmlspecialchars($freelancer['professional_title']); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <?php if($freelancer['skills']): ?>
                            <div style="margin-bottom: 1rem;">
                                <div class="profile-skills">
                                    <?php 
                                    $skills = explode(',', $freelancer['skills']);
                                    $display_skills = array_slice($skills, 0, 3);
                                    foreach($display_skills as $skill): 
                                        if(trim($skill)):
                                    ?>
                                        <span class="skill-tag" style="background: rgba(16, 185, 129, 0.1); color: var(--accent-primary); padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-right: 0.5rem;"><?php echo htmlspecialchars(trim($skill)); ?></span>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    if(count($skills) > 3): ?>
                                        <span class="skill-tag">+<?php echo count($skills) - 3; ?> more</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($freelancer['bio']): ?>
                            <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem; 
                                      display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                <?php echo htmlspecialchars($freelancer['bio']); ?>
                            </p>
                        <?php endif; ?>

                        <a href="seeker_profile.php?id=<?php echo $freelancer['id']; ?>" class="btn btn-primary" style="width: 100%;">
                            View Profile
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<script>
// Sidebar Toggle Script
const mobileToggle = document.getElementById('mobileSidebarToggle');
const sidebar = document.querySelector('.dashboard-sidebar');

mobileToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});
</script>

<?php require_once '../includes/footer.php'; ?>