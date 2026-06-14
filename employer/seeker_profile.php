<?php
$page_title = "Freelancer Profile";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

if(!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: browse_seekers.php');
    exit;
}

$freelancer_id = $_GET['id'];

// Get freelancer data
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT * FROM job_seekers WHERE id = :id AND is_active = 1");
$stmt->bindParam(':id', $freelancer_id);
$stmt->execute();
$freelancer = $stmt->fetch();

if(!$freelancer) {
    header('Location: browse_seekers.php');
    exit;
}
?>

<!-- Employer Sidebar Wrapper (added only to this page) -->
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
            <a href="#" class="sidebar-link">
                <i class="fas fa-file-alt"></i>
                <span>Posted Jobs</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>Applicants</span>
            </a>
            <a href="#" class="sidebar-link">
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
        <section class="dashboard-header">
            <div class="container">
                <div class="dashboard-welcome">
                    <h1>Freelancer Profile</h1>
                    <p>View and contact this talented professional</p>
                </div>
                <div class="dashboard-actions">
                    <a href="browse_seekers.php" class="btn btn-outline">Back to Browse</a>
                    <a href="dashboard.php" class="btn btn-outline">Dashboard</a>
                </div>
            </div>
        </section>

        <section class="container">
            <div class="card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <?php echo strtoupper(substr($freelancer['full_name'], 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <h2 class="profile-title"><?php echo htmlspecialchars($freelancer['full_name']); ?></h2>
                        <?php if($freelancer['professional_title']): ?>
                            <p style="font-size: 1.2rem; color: var(--dark-blue); margin-bottom: 1rem;">
                                <?php echo htmlspecialchars($freelancer['professional_title']); ?>
                            </p>
                        <?php endif; ?>

                        <!-- Contact Information (Visible to Employers) -->
                        <div style="background: var(--light-blue); padding: 1rem; border-radius: 0.5rem; margin: 1rem 0;">
                            <h4 style="margin-bottom: 0.5rem; color: var(--dark-blue);">Contact Information</h4>
                            <p>📧 <strong>Email:</strong> <?php echo htmlspecialchars($freelancer['email']); ?></p>
                            <?php if($freelancer['phone']): ?>
                                <p>📞 <strong>Phone:</strong> <?php echo htmlspecialchars($freelancer['phone']); ?></p>
                            <?php endif; ?>
                        </div>

                        <?php if($freelancer['skills']): ?>
                            <div class="profile-skills">
                                <?php 
                                $skills = explode(',', $freelancer['skills']);
                                foreach($skills as $skill): 
                                    if(trim($skill)):
                                ?>
                                    <span class="skill-tag"><?php echo htmlspecialchars(trim($skill)); ?></span>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($freelancer['bio']): ?>
                    <div style="margin: 2rem 0;">
                        <h3 style="margin-bottom: 1rem; color: var(--dark-blue);">About</h3>
                        <p style="line-height: 1.6; color: var(--dark-gray);"> <?php echo nl2br(htmlspecialchars($freelancer['bio'])); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($freelancer['portfolio_link']): ?>
                    <div style="margin: 2rem 0;">
                        <h3 style="margin-bottom: 1rem; color: var(--dark-blue);">Portfolio</h3>
                        <a href="<?php echo htmlspecialchars($freelancer['portfolio_link']); ?>" target="_blank" class="btn btn-outline">
                            View Portfolio Website
                        </a>
                    </div>
                <?php endif; ?>

                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <p style="color: var(--dark-gray);">
                            Member since: <?php echo date('F Y', strtotime($freelancer['date_joined'])); ?>
                        </p>
                        <div>
                            <a href="mailto:<?php echo htmlspecialchars($freelancer['email']); ?>" class="btn btn-primary">
                                📧 Contact <?php echo explode(' ', $freelancer['full_name'])[0]; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Mobile Sidebar Toggle -->
<button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<style>
/* Dashboard Layout Styles (copied from employer/dashboard.php) */
.dashboard-container {
    display: flex;
    min-height: calc(100vh - 70px);
    background: var(--bg-primary);
}

.dashboard-sidebar {
    width: 280px;
    background: var(--bg-secondary);
    border-right: 1px solid var(--border-light);
    padding: 1.5rem;
    position: sticky;
    top: 70px;
    height: calc(100vh - 70px);
    overflow-y: auto;
    transition: left 0.3s ease;
}

.sidebar-user {
    text-align: center;
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-light);
}

.sidebar-avatar {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--accent-primary), var(--accent-hover));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: bold;
    margin: 0 auto 1rem;
    color: white;
}

.sidebar-user h3 {
    font-size: 1rem;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.sidebar-role {
    font-size: 0.75rem;
    color: var(--text-muted);
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    transition: all 0.2s ease;
}

.sidebar-link i {
    width: 20px;
    font-size: 1.125rem;
}

.sidebar-link:hover {
    background: rgba(16, 185, 129, 0.1);
    color: var(--accent-primary);
}

.sidebar-link.active {
    background: rgba(16, 185, 129, 0.15);
    color: var(--accent-primary);
    border-left: 3px solid var(--accent-primary);
}

.sidebar-footer {
    margin-top: auto;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
}

.dashboard-main {
    flex: 1;
    padding: 1.5rem;
    overflow-x: auto;
}

.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-header h1 {
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
    color: var(--text-primary);
}

.dashboard-actions {
    display: flex;
    gap: 0.75rem;
}

/* Mobile */
.mobile-sidebar-toggle {
    display: none;
    position: fixed;
    bottom: 1rem;
    right: 1rem;
    z-index: 1000;
    background: var(--accent-primary);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    color: white;
    font-size: 1.25rem;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

@media (max-width: 768px) {
    .dashboard-sidebar {
        position: fixed;
        left: -280px;
        z-index: 999;
        top: 0;
        height: 100vh;
    }

    .dashboard-sidebar.active {
        left: 0;
    }

    .mobile-sidebar-toggle {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .dashboard-main {
        padding: 1rem;
    }

    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
    }
}
</style>

<script>
// Mobile sidebar toggle
const mobileToggle = document.getElementById('mobileSidebarToggle');
const sidebar = document.querySelector('.dashboard-sidebar');

if (mobileToggle && sidebar) {
    mobileToggle.addEventListener('click', function() {
        sidebar.classList.toggle('active');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth <= 768) {
            if (sidebar && !sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
}
</script>

<?php require_once '../includes/footer.php'; ?>
