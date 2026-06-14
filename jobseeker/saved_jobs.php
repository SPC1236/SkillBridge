<?php
// saved_jobs.php
// Modernized Saved Jobs Dashboard Grid View - Light Blue Accent Scheme

$page_title = "Saved Jobs";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

// Start output buffering for dashboard layout structure
ob_start();

$db = new Database();
$conn = $db->getConnection();

$user_id = $_SESSION['user_id'];

// Handle asynchronous-like target deletion mechanics cleanly
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $job_id = $_GET['remove'];
    try {
        $delete_sql = "DELETE FROM saved_jobs WHERE user_id = :user_id AND job_id = :job_id";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bindParam(':user_id', $user_id);
        $delete_stmt->bindParam(':job_id', $job_id);
        $delete_stmt->execute();
        header('Location: saved_jobs.php?removed=1');
        exit;
    } catch(PDOException $e) {
        error_log("Error removing saved job: " . $e->getMessage());
    }
}

$saved_jobs = [];
$total_saved = 0;

try {
    $table_check = $conn->query("SHOW TABLES LIKE 'saved_jobs'");
    if ($table_check->rowCount() > 0) {
        $sql = "SELECT sj.*, 
                       j.title, 
                       j.company_name, 
                       j.location, 
                       j.job_type, 
                       j.salary_min, 
                       j.salary_max,
                       j.skills_required,
                       j.description
                FROM saved_jobs sj 
                LEFT JOIN jobs j ON sj.job_id = j.id 
                WHERE sj.user_id = :user_id 
                ORDER BY sj.saved_date DESC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $saved_jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $total_saved = count($saved_jobs);
    }
} catch(PDOException $e) {
    error_log("Error fetching saved jobs: " . $e->getMessage());
}

$success_message = '';
if (isset($_GET['removed'])) {
    $success_message = 'Job removed from saved list successfully!';
}
?>

<style>
    .dashboard-view-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2.25rem;
    }

    .dashboard-view-header h1 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.85rem;
        font-weight: 700;
        margin: 0 0 0.35rem 0;
        color: var(--text-primary);
    }

    .dashboard-view-header p {
        margin: 0;
        font-size: 0.95rem;
        color: var(--text-secondary);
    }

    .header-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #38bdf8; /* Vibrant light blue / sky blue */
        color: #ffffff;
        text-decoration: none;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-lg);
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(56, 189, 248, 0.25);
        transition: var(--transition-smooth);
    }

    .header-action-btn:hover {
        background: #0ea5e9; /* Deepened sky blue */
        transform: translateY(-2px);
    }

    /* System Status Alerts styling upgrades - Sky Blue theme */
    .modern-alert {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(56, 189, 248, 0.06) !important;
        border: 1px solid rgba(56, 189, 248, 0.25) !important;
        border-left: 4px solid #38bdf8 !important;
        padding: 1rem 1.25rem;
        border-radius: var(--radius-lg);
        margin-bottom: 2rem;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .modern-alert i {
        color: #38bdf8;
        font-size: 1.1rem;
    }

    /* Analytical Overview Widget Metrics Row */
    .metric-overview-strip {
        margin-bottom: 2rem;
    }

    .metric-glass-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 1.25rem 1.5rem;
        display: inline-flex;
        align-items: center;
        gap: 1.25rem;
        min-width: 240px;
    }

    .metric-icon-box {
        width: 48px;
        height: 48px;
        background: rgba(56, 189, 248, 0.1);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .metric-icon-box i {
        color: #38bdf8;
        font-size: 1.25rem;
    }

    .metric-data-readout {
        display: flex;
        flex-direction: column;
    }

    .metric-val {
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    .metric-lbl {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Modern Responsive Layout Display Pipeline */
    .saved-jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 1.5rem;
    }

    .modern-job-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: var(--transition-smooth);
    }

    .modern-job-card:hover {
        transform: translateY(-4px);
        border-color: rgba(56, 189, 248, 0.4);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2), 0 0 15px rgba(56, 189, 248, 0.03);
    }

    .card-top-deck {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        margin-bottom: 1.25rem;
    }

    .dynamic-avatar-logo {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #38bdf8, #0ea5e9);
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Poppins', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.15);
    }

    .deck-details-box {
        flex: 1;
        min-width: 0;
    }

    .deck-details-box h2 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0 0 0.25rem 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .company-anchor-link {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.85rem;
        color: var(--text-secondary);
        text-decoration: none;
    }

    .company-anchor-link i {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* Core Dynamic Action Interface Buttons Layout */
    .trash-action-anchor {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.15);
        width: 36px;
        height: 36px;
        border-radius: var(--radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: var(--transition-smooth);
        flex-shrink: 0;
    }

    .trash-action-anchor i {
        color: #ef4444;
        font-size: 0.95rem;
    }

    .trash-action-anchor:hover {
        background: #ef4444;
        border-color: #ef4444;
        transform: scale(1.05);
    }
    
    .trash-action-anchor:hover i {
        color: #ffffff;
    }

    /* Micro Meta Tags Array */
    .meta-tags-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px dashed var(--border-light);
    }

    .cloud-tag-item {
        font-size: 0.8rem;
        color: var(--text-secondary);
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .cloud-tag-item i {
        color: #38bdf8;
        font-size: 0.85rem;
    }

    .cloud-tag-item.salary-highlight {
        color: #38bdf8;
        font-weight: 600;
    }

    /* Developer Skill badge pills array viewports */
    .skills-capsules-strip {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 1.5rem;
    }

    .skill-pill {
        background: rgba(56, 189, 248, 0.06);
        border: 1px solid rgba(56, 189, 248, 0.15);
        color: var(--text-secondary);
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Structural Core Actions Footer Platform Row */
    .card-footer-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: auto;
    }

    .card-footer-actions .btn-workspace {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.65rem 1rem;
        border-radius: var(--radius-lg);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition-smooth);
        cursor: pointer;
    }

    .btn-workspace.btn-view-details {
        background: transparent;
        border: 1px solid var(--border-light);
        color: var(--text-primary);
    }

    .btn-workspace.btn-view-details:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: var(--text-secondary);
    }

    .btn-workspace.btn-trigger-apply {
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.2);
        color: #38bdf8;
    }

    .btn-workspace.btn-trigger-apply:hover {
        background: #38bdf8;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(56, 189, 248, 0.2);
    }

    /* Premium Clean Application Empty Engine System Template State */
    .empty-canvas-state {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        text-align: center;
        padding: 4.5rem 2rem;
        max-width: 500px;
        margin: 2rem auto;
    }

    .empty-canvas-state i {
        font-size: 3.5rem;
        color: var(--text-muted);
        margin-bottom: 1.25rem;
        opacity: 0.4;
    }

    .empty-canvas-state h3 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.35rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        color: var(--text-primary);
    }

    .empty-canvas-state p {
        font-size: 0.95rem;
        color: var(--text-secondary);
        margin: 0 0 1.75rem 0;
        line-height: 1.5;
    }

    /* Core Media Boundary Optimization adjustments queries */
    @media (max-width: 576px) {
        .dashboard-view-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .header-action-btn {
            width: 100%;
            justify-content: center;
        }
        .saved-jobs-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="dashboard-view-header">
    <div>
        <h1>Saved Jobs</h1>
        <p>Manage the dynamic positions you have bookmarked for future reference</p>
    </div>
    <a href="<?php echo SITE_URL; ?>/employer/browse_seekers.php" class="header-action-btn">
        <i class="fas fa-magnifying-glass"></i> Explore Job Directory
    </a>
</div>

<?php if ($success_message): ?>
    <div class="modern-alert">
        <i class="fas fa-circle-check"></i>
        <span><?php echo htmlspecialchars($success_message); ?></span>
    </div>
<?php endif; ?>

<div class="metric-overview-strip">
    <div class="metric-glass-card">
        <div class="metric-icon-box">
            <i class="fas fa-bookmark"></i>
        </div>
        <div class="metric-data-readout">
            <span class="metric-val"><?php echo $total_saved; ?></span>
            <span class="metric-lbl">Total Bookmarks</span>
        </div>
    </div>
</div>

<?php if ($total_saved > 0): ?>
    <div class="saved-jobs-grid">
        <?php foreach ($saved_jobs as $job): 
            $skills = !empty($job['skills_required']) ? array_map('trim', explode(',', $job['skills_required'])) : [];
            $skills = array_slice($skills, 0, 4);
        ?>
            <article class="modern-job-card">
                <div>
                    <div class="card-top-deck">
                        <div class="dynamic-avatar-logo">
                            <?php echo htmlspecialchars(substr($job['company_name'] ?? 'C', 0, 1)); ?>
                        </div>
                        <div class="deck-details-box">
                            <h2><?php echo htmlspecialchars($job['title'] ?? 'Untitled Position'); ?></h2>
                            <span class="company-anchor-link">
                                <i class="fas fa-building"></i> <?php echo htmlspecialchars($job['company_name'] ?? 'Corporate Entity'); ?>
                            </span>
                        </div>
                        <a href="?remove=<?php echo $job['job_id']; ?>" 
                           class="trash-action-anchor" 
                           onclick="return confirm('Are you sure you want to remove this position from your bookmarks?');" 
                           title="Remove Bookmark">
                            <i class="fas fa-trash-can"></i>
                        </a>
                    </div>

                    <div class="meta-tags-cloud">
                        <span class="cloud-tag-item">
                            <i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($job['location'] ?? 'Remote'); ?>
                        </span>
                        <span class="cloud-tag-item">
                            <i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['job_type'] ?? 'Contract'); ?>
                        </span>
                        <?php if (!empty($job['salary_min']) && !empty($job['salary_max'])): ?>
                            <span class="cloud-tag-item salary-highlight">
                                <i class="fas fa-money-bill-wave"></i> $<?php echo number_format($job['salary_min']); ?> - $<?php echo number_format($job['salary_max']); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($skills)): ?>
                        <div class="skills-capsules-strip">
                            <?php foreach ($skills as $skill): ?>
                                <span class="skill-pill"><?php echo htmlspecialchars($skill); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer-actions">
                    <a href="job_details.php?id=<?php echo $job['job_id']; ?>" class="btn-workspace btn-view-details">
                        <i class="fas fa-expand"></i> View Specs
                    </a>
                    <a href="apply.php?id=<?php echo $job['job_id']; ?>" class="btn-workspace btn-trigger-apply">
                        <i class="fas fa-paper-plane"></i> Apply Now
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-canvas-state">
        <i class="fas fa-folder-open"></i>
        <h3>No Bookmarked Positions</h3>
        <p>You haven't saved any career paths or available contracts yet. Explore available jobs to add them to your tracking board.</p>
        <a href="<?php echo SITE_URL; ?>/employer/browse_seekers.php" class="header-action-btn" style="box-shadow: none;">
            <i class="fas fa-magnifying-glass"></i> Explore System Directory
        </a>
    </div>
<?php endif; ?>

<?php
// Capture workspace buffer assignments execution array
$content = ob_get_clean();

// Pass compilation context data over structural template core wrapper array execution matrix
require_once '../includes/dashboard_layout.php';
?>