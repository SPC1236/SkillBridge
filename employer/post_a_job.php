<?php
// employer/post_job.php
$page_title = "Post a New Job";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

// Ensure the user logged in actually has employer privileges
// if ($_SESSION['role'] !== 'employer') { header('Location: ../index.php'); exit; }

ob_start();

$database = new Database();
$conn = $database->getConnection();
$employer_id = $_SESSION['user_id'];

$message = '';
$status_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CAPTURE THE DATA FROM THE FORM
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $company_name = isset($_POST['company_name']) ? trim($_POST['company_name']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $job_type = isset($_POST['job_type']) ? trim($_POST['job_type']) : '';
    $salary_min = !empty($_POST['salary_min']) ? intval($_POST['salary_min']) : null;
    $salary_max = !empty($_POST['salary_max']) ? intval($_POST['salary_max']) : null;
    $skills_required = isset($_POST['skills_required']) ? trim($_POST['skills_required']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Now the variables are populated, the empty() check will work correctly
    if (!empty($title) && !empty($company_name) && !empty($description)) {
        try {
            $sql = "INSERT INTO jobs (employer_id, title, company_name, location, job_type, salary_min, salary_max, skills_required, description) 
                    VALUES (:employer_id, :title, :company_name, :location, :job_type, :salary_min, :salary_max, :skills_required, :description)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':employer_id', $employer_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':company_name', $company_name);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':job_type', $job_type);
            $stmt->bindParam(':salary_min', $salary_min, PDO::PARAM_INT);
            $stmt->bindParam(':salary_max', $salary_max, PDO::PARAM_INT);
            $stmt->bindParam(':skills_required', $skills_required);
            $stmt->bindParam(':description', $description);
            
            if ($stmt->execute()) {
                $message = "Success! Your job listing has been published.";
                $status_type = "success";
            }
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            $message = "An internal processing error occurred.";
            $status_type = "error";
        }
    } else {
        $message = "Please fill out all mandatory fields (Job Title, Company Name, and Description).";
        $status_type = "error";
    }
}
?>

<style>
    .form-container-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 2.25rem;
        max-width: 800px;
        margin: 0 auto;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .form-group.full-width {
        grid-column: span 2;
    }
    .form-group label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
    }
    .form-group input, .form-group select, .form-group textarea {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-light);
        padding: 0.75rem 1rem;
        border-radius: var(--radius-lg);
        color: var(--text-primary);
        font-family: inherit;
        font-size: 0.9rem;
        transition: var(--transition-smooth);
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        border-color: #38bdf8;
        outline: none;
        box-shadow: 0 0 10px rgba(56, 189, 248, 0.15);
    }
    .form-actions-row {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    .alert-success {
    padding: 1rem;
    border-radius: var(--radius-lg);
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    border-left: 4px solid #22c55e;
    background: rgba(34, 197, 94, 0.08);
    color: #22c55e;
    font-weight: 600;
}
    .btn-action {
        padding: 0.75rem 1.75rem;
        border-radius: var(--radius-lg);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition-smooth);
    }
    .btn-action.btn-submit {
        background: #38bdf8;
        border: none;
        color: #fff;
        box-shadow: 0 4px 14px rgba(56, 189, 248, 0.25);
    }
    .btn-action.btn-submit:hover {
        background: #0ea5e9;
        transform: translateY(-1px);
    }
    .btn-action.btn-cancel {
        background: transparent;
        border: 1px solid var(--border-light);
        color: var(--text-secondary);
    }
    .btn-action.btn-cancel:hover {
        background: rgba(255, 255, 255, 0.02);
        color: var(--text-primary);
    }
    .alert-banner {
        padding: 1rem;
        border-radius: var(--radius-lg);
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        border-left: 4px solid #ef4444;
        background: rgba(239, 68, 68, 0.08);
        color: var(--text-primary);
    }
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-group.full-width { grid-column: span 1; }
    }
</style>

<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.85rem; font-weight:700; margin:0 0 0.35rem 0; color: var(--text-primary);">Post a Job Opening</h1>
    <p style="margin:0; color: var(--text-secondary);">Fill in the operational criteria below to broadcast your vacancy.</p>
</div>

<?php if ($message): ?>
    <div class="<?php echo ($status_type === 'success') ? 'alert-success' : 'alert-banner'; ?>">
        <i class="fas fa-circle-<?php echo ($status_type === 'success') ? 'check' : 'exclamation'; ?>" style="margin-right:0.5rem;"></i> 
        <?php echo htmlspecialchars($message); ?>
        
        <?php if ($status_type === 'success'): ?>
            <br><a href="manage_jobs.php" style="color: #22c55e; text-decoration: underline; font-size: 0.8rem;">View in Dashboard</a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="form-container-card">
    <form action="" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label for="title">Job Title *</label>
                <input type="text" id="title" name="title" required placeholder="e.g., Senior Full-Stack Engineer">
            </div>
            <div class="form-group">
                <label for="company_name">Company Name *</label>
                <input type="text" id="company_name" name="company_name" required placeholder="e.g., Acme Tech Solutions">
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" id="location" name="location" placeholder="e.g., Freetown, SL (or Remote)">
            </div>
            <div class="form-group">
                <label for="job_type">Employment Term</label>
                <select id="job_type" name="job_type">
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Contract">Contract</option>
                    <option value="Internship">Internship</option>
                </select>
            </div>
            <div class="form-group">
                <label for="salary_min">Minimum Compensation ($)</label>
                <input type="number" id="salary_min" name="salary_min" placeholder="Min dollar amount">
            </div>
            <div class="form-group">
                <label for="salary_max">Maximum Compensation ($)</label>
                <input type="number" id="salary_max" name="salary_max" placeholder="Max dollar amount">
            </div>
            <div class="form-group full-width">
                <label for="skills_required">Required Stack Skills (Comma-separated)</label>
                <input type="text" id="skills_required" name="skills_required" placeholder="e.g., Tailwind CSS, PHP, MySQL, Flutter">
            </div>
            <div class="form-group full-width">
                <label for="description">Job Specifications & Scope *</label>
                <textarea id="description" name="description" rows="6" required placeholder="Outline the complete day-to-day responsibilities, expectations, and experience qualifications..."></textarea>
            </div>
        </div>
        <div class="form-actions-row">
            <a href="manage_jobs.php" class="btn-action btn-cancel">Cancel Workspace</a>
            <button type="submit" class="btn-action btn-submit">Publish Listing</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once '../includes/dashboard_layout.php';
?>