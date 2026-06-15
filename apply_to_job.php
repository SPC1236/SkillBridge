<?php
// apply.php
// Job Seeker Application Portal - Corrected Paths & File Management

$page_title = "Submit Application";

// 1. FIXED: Point to the root includes directory
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

ob_start();

$database = new Database();
$conn = $database->getConnection();
$user_id = $_SESSION['user_id'];

// Safeguard: Ensure a target job is requested
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: discover.php'); // Redirect to your main discover page
    exit;
}

$job_id = $_GET['id'];
$job = null;

// Pull details to verify job exists
try {
    $job_sql = "SELECT id, title, company_name, location FROM jobs WHERE id = :id AND status = 'active'";
    $job_stmt = $conn->prepare($job_sql);
    $job_stmt->bindParam(':id', $job_id, PDO::PARAM_INT);
    $job_stmt->execute();
    $job = $job_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job) {
        header('Location: discover.php?error=not_found');
        exit;
    }

    // Check for existing duplicate records
    $dup_sql = "SELECT id FROM job_applications WHERE job_id = :job_id AND user_id = :user_id";
    $dup_stmt = $conn->prepare($dup_sql);
    $dup_stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
    $dup_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $dup_stmt->execute();
    if ($dup_stmt->rowCount() > 0) {
        header('Location: saved_jobs.php?error=already_applied');
        exit;
    }
} catch (PDOException $e) {
            // Change your code to this for debugging:
            die("Database Error: " . $e->getMessage()); 
        }
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cover_letter = trim($_POST['cover_letter']);
    $resume_dest = null;

    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['resume']['tmp_name'];
        $file_name = $_FILES['resume']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf', 'doc', 'docx'];

        if (in_array($file_ext, $allowed_extensions)) {
            // 2. FIXED: Point to root uploads directory so all uploads are centralized
            $upload_dir = '../uploads/resumes/'; 
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $new_file_name = 'resume_' . $user_id . '_' . time() . '.' . $file_ext;
            $resume_dest = $upload_dir . $new_file_name;

            if (!move_uploaded_file($file_tmp, $resume_dest)) {
                $message = "Failed to write file to system storage.";
            }
        } else {
            $message = "Unsupported document extension. Use PDF, DOC, or DOCX.";
        }
    } else {
        $message = "Resume file upload is mandatory.";
    }


if (empty($message)) {
    try {
        // Changed column names to match your schema exactly
        $ins_sql = "INSERT INTO job_applications (job_id, user_id, cover_letter, status) 
                    VALUES (:job_id, :user_id, :cover_letter, 'pending')";
        
        $ins_stmt = $conn->prepare($ins_sql);
        $ins_stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
        $ins_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $ins_stmt->bindParam(':cover_letter', $cover_letter);

        if ($ins_stmt->execute()) {
            header('Location: saved_jobs.php?applied_success=1');
            exit;
        }
    } catch (PDOException $e) {
        die("Database Error Details: " . $e->getMessage());
    }
}
}

?>

<style>
    .form-container-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 2.25rem;
        max-width: 700px;
        margin: 0 auto;
    }
    .job-context-header {
        background: rgba(56, 189, 248, 0.04);
        border: 1px solid rgba(56, 189, 248, 0.15);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        margin-bottom: 1.75rem;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }
    .form-group label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
    }
    .form-group textarea, .form-group input[type="file"] {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-light);
        padding: 0.75rem 1rem;
        border-radius: var(--radius-lg);
        color: var(--text-primary);
        font-family: inherit;
        font-size: 0.9rem;
        transition: var(--transition-smooth);
    }
    .form-group textarea:focus {
        border-color: #38bdf8;
        outline: none;
        box-shadow: 0 0 10px rgba(56, 189, 248, 0.15);
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
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
    }
    .btn-action.btn-cancel {
        background: transparent;
        border: 1px solid var(--border-light);
        color: var(--text-secondary);
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
</style>

<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.85rem; font-weight:700; margin:0 0 0.35rem 0; color: var(--text-primary);">Application Dispatch</h1>
    <p style="margin:0; color: var(--text-secondary);">Review guidelines and attach credentials to connect directly with hiring managers.</p>
</div>

<?php if ($message): ?>
    <div class="alert-banner">
        <i class="fas fa-circle-exclamation" style="color:#ef4444; margin-right:0.5rem;"></i> <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="form-container-card">
    <div class="job-context-header">
        <span style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #38bdf8; font-weight: 700; display: block; margin-bottom: 0.25rem;">Target Position</span>
        <h2 style="margin: 0 0 0.25rem 0; font-size: 1.2rem; color: var(--text-primary);"><?php echo htmlspecialchars($job['title']); ?></h2>
        <span style="font-size: 0.85rem; color: var(--text-secondary);"><i class="fas fa-building"></i> <?php echo htmlspecialchars($job['company_name']); ?> &bull; <i class="fas fa-location-dot"></i> <?php echo htmlspecialchars($job['location']); ?></span>
    </div>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="resume">Attach Curriculum Vitae / Resume *</label>
            <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
            <span style="font-size: 0.75rem; color: var(--text-muted);">Accepted structures: PDF, DOC, DOCX. Max space payload: 5MB</span>
        </div>

        <div class="form-group">
            <label for="cover_letter">Pitch Deck / Cover Letter Overview</label>
            <textarea id="cover_letter" name="cover_letter" rows="8" placeholder="Introduce yourself and explain why you're a strong fit for this role..."></textarea>
        </div>

        <div class="form-actions">
            <a href="saved_jobs.php" class="btn-action btn-cancel">Abort Pipeline</a>
            <button type="submit" class="btn-action btn-submit">Transmit Application</button>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once '../includes/dashboard_layout.php';
?>