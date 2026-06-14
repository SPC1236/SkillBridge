<?php
// job_details.php
$page_title = "Job Specifications";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

ob_start();

$database = new Database();
$conn = $database->getConnection();

$job_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;
$job = null;

// Fetch job details from database
if ($job_id > 0) {
    $sql = "SELECT * FROM jobs WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $job_id, PDO::PARAM_INT);
    $stmt->execute();
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$job) {
    header('Location: saved_jobs.php?error=not_found');
    exit;
}
?>

<div class="form-container-card">
    <div class="job-context-header" style="text-align: left;">
        <h1 style="font-size: 2rem; color: var(--text-primary); margin-bottom: 0.5rem;"><?php echo htmlspecialchars($job['title']); ?></h1>
        <p style="color: #38bdf8; font-weight: 600; margin-bottom: 1rem;">
            <i class="fas fa-building"></i> <?php echo htmlspecialchars($job['company_name']); ?> 
            &bull; <i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($job['location']); ?>
        </p>
    </div>

    <div style="margin-bottom: 2rem;">
        <h3 style="color: var(--text-primary);">Role Description</h3>
        <p style="color: var(--text-secondary); line-height: 1.6;">
            <?php echo nl2br(htmlspecialchars($job['description'])); ?>
        </p>
    </div>

    <div class="meta-tags-cloud" style="border: none; padding: 0;">
        <span class="cloud-tag-item"><i class="fas fa-briefcase"></i> <?php echo htmlspecialchars($job['job_type']); ?></span>
        <?php if (!empty($job['salary_min'])): ?>
            <span class="cloud-tag-item salary-highlight">
                <i class="fas fa-money-bill-wave"></i> $<?php echo number_format($job['salary_min']); ?> - $<?php echo number_format($job['salary_max']); ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="form-actions" style="justify-content: flex-start;">
        <a href="saved_jobs.php" class="btn-action btn-cancel">Back to Dashboard</a>
        <a href="apply.php?id=<?php echo $job['id']; ?>" class="btn-action btn-submit">Apply Now</a>
    </div>
</div>

<?php 
$content = ob_get_clean();
require_once '../includes/dashboard_layout.php';
?>