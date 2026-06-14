<?php
// admin/upload_resource.php
// Centralized Educational Resources Asset Dispatch - Direct Admin Table Link

$page_title = "Admin: Upload Training Assets";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

// Strict Role Check Guardrail - SYNCED WITH YOUR CORE LAYOUT VARIABLE
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { 
    header('Location: /freelance_portal/dashboard.php'); 
    exit; 
}

ob_start();
$message = '';

// Establish Database connection early to verify the active admin account
$database = new Database();
$conn = $database->getConnection();

try {
    // Look up your actual admin's ID directly from your dedicated 'admin' table
    $find_admin = $conn->query("SELECT id FROM admin LIMIT 1");
    $existing_admin = $find_admin->fetch(PDO::FETCH_ASSOC);

    if ($existing_admin) {
        // Automatically sync your active session to the true ID found in your admin table
        $_SESSION['user_id'] = $existing_admin['id'];
        $admin_id = $existing_admin['id'];
    } else {
        throw new Exception("No admin configuration found inside your 'admin' table registry.");
    }
} catch (Exception $e) {
    $message = "Database Sync Warning: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($admin_id)) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $resource_link = trim($_POST['resource_link']);
    $category = trim($_POST['category']);

    if (!empty($title) && !empty($resource_link)) {
        try {
            // Inserts cleanly with a valid foreign key linking straight back to your 'admin' table
            $sql = "INSERT INTO training_resources (title, description, resource_link, category, uploaded_by) 
                    VALUES (:title, :description, :resource_link, :category, :admin_id)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':resource_link' => $resource_link,
                ':category' => $category,
                ':admin_id' => $admin_id
            ]);
            
            $message = "Asset published successfully.";
        } catch(PDOException $e) { 
            $message = "Database write error: " . $e->getMessage(); 
        }
    }
}
?>

<style>
    .admin-view-header {
        margin-bottom: 2.25rem;
    }
    .admin-badge-indicator {
        display: inline-block;
        background: rgba(56, 189, 248, 0.1);
        color: #38bdf8;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    .modern-form-card {
        max-width: 600px; 
        margin: 0 auto; 
        background: var(--bg-secondary); 
        border: 1px solid var(--border-light); 
        padding: 2.5rem; 
        border-radius: var(--radius-xl);
    }
    .form-input {
        padding: 0.75rem 1rem; 
        background: rgba(0,0,0,0.2); 
        border: 1px solid var(--border-light); 
        color: #fff; 
        border-radius: 8px;
        font-size: 0.95rem;
        transition: var(--transition-smooth);
        width: 100%;
        box-sizing: border-box;
    }
    .form-input:focus {
        border-color: #38bdf8;
        outline: none;
        background: rgba(0,0,0,0.3);
    }
    .submit-btn {
        background: #38bdf8; 
        border: none; 
        color: var(--bg-primary); 
        padding: 0.85rem; 
        border-radius: 8px; 
        font-weight: 600; 
        font-size: 0.95rem;
        cursor: pointer;
        transition: var(--transition-smooth);
    }
    .submit-btn:hover {
        background: #0ea5e9;
        transform: translateY(-1px);
    }
    .form-alert {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #38bdf8; 
        font-size: 0.95rem;
        background: rgba(56, 189, 248, 0.05);
        padding: 0.75rem 1rem;
        border-radius: 6px;
        border: 1px solid rgba(56, 189, 248, 0.2);
        margin-bottom: 1.5rem;
    }
</style>

<div class="admin-view-header">
    <span class="admin-badge-indicator">Resource Repository Control</span>
    <h1 style="font-size: 1.85rem; font-weight:700; margin:0 0 0.35rem 0; color: var(--text-primary);">Distribute Training Assets</h1>
    <p style="margin:0; color: var(--text-secondary);">Add documentation links, video pipelines, or codebase setups for user dashboards.</p>
</div>

<div class="modern-form-card">
    <h2 style="margin-top:0; font-size:1.4rem; font-weight:600; color:#38bdf8; margin-bottom:1.5rem;">Upload Curated Asset</h2>
    
    <?php if($message): ?>
        <div class="form-alert">
            <i class="fa-solid fa-info-circle"></i>
            <span><?php echo $message; ?></span>
        </div>
    <?php endif; ?>
    
    <form action="/freelance_portal/admin/upload_resource.php" method="POST" style="display:flex; flex-direction:column; gap:1.25rem;">
        <input type="text" name="title" placeholder="Resource Title" required class="form-input">
        <input type="text" name="category" placeholder="Category (e.g., UI/UX Design Systems, Database Architecture)" required class="form-input">
        <input type="url" name="resource_link" placeholder="Asset External Anchor URL Link" required class="form-input">
        <textarea name="description" placeholder="Provide a brief summary detailing target competencies..." rows="4" class="form-input" style="font-family:inherit; resize:vertical;"></textarea>
        <button type="submit" class="submit-btn" <?php echo !isset($admin_id) ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : ''; ?>>Publish Resource Node</button>
    </form>
</div>

<?php
$content = ob_get_clean();
require_once '../includes/dashboard_layout.php';
?>