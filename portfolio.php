<?php
// jobseeker/portfolio.php
// Standalone Professional Showcase Engine - Distinct from Account Profile

$page_title = "My Professional Portfolio Node";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'jobseeker') {
    header('Location: /freelance_portal/dashboard.php');
    exit;
}

ob_start();

$database = new Database();
$conn = $database->getConnection();

// --- ROBUST SESSION INTEGRITY CORRECTION ---
if (!isset($_SESSION['user_id']) && isset($_SESSION['id'])) {
    $_SESSION['user_id'] = $_SESSION['id'];
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("<div style='padding:2rem; background:#111827; color:#ef4444; font-family:sans-serif;'>
            <strong>Critical Framework Error:</strong> Active user context identifier could not be resolved. 
            Please clear your browser cookies, log out, and log back in to rebuild your authorization token.
         </div>");
}

$user_id = $_SESSION['user_id'];
// --------------------------------------------

$status_message = '';
$status_type = '';

// Processing Core Pipeline: Portfolio Sync Engine
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headline = trim($_POST['professional_headline']);
    $bio = trim($_POST['core_biography']);
    $skills = trim($_POST['technical_skills']);
    $exp = is_numeric($_POST['years_of_experience']) ? (int)$_POST['years_of_experience'] : 0;
    $qualification = trim($_POST['highest_qualification']);
    $p1_title = trim($_POST['project_one_title']);
    $p1_url = trim($_POST['project_one_url']);
    $p2_title = trim($_POST['project_two_title']);
    $p2_url = trim($_POST['project_two_url']);
    $github = trim($_POST['github_link']);
    $linkedin = trim($_POST['linkedin_link']);
    $avail = trim($_POST['availability_allocation']);

    try {
        $user_verify = $conn->prepare("SELECT id FROM job_seekers WHERE id = :user_id");
        $user_verify->execute([':user_id' => $user_id]);
        
        if (!$user_verify->fetch()) {
            throw new Exception("The user ID ($user_id) found in your session data does not exist in the job_seekers table. Current Database: " . DB_NAME);
        }
        // -------------------------------

        $check_sql = "SELECT id FROM jobseeker_portfolios WHERE jobseeker_id = :user_id";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([':user_id' => $user_id]);
        
        if ($check_stmt->fetch()) {
            $sql = "UPDATE jobseeker_portfolios SET 
                        professional_headline = :headline, core_biography = :bio, technical_skills = :skills,
                        years_of_experience = :exp, highest_qualification = :qualification,
                        project_one_title = :p1_title, project_one_url = :p1_url,
                        project_two_title = :p2_title, project_two_url = :p2_url,
                        github_link = :github, linkedin_link = :linkedin, availability_allocation = :avail
                    WHERE jobseeker_id = :user_id";
        } else {
            $sql = "INSERT INTO jobseeker_portfolios 
                        (jobseeker_id, professional_headline, core_biography, technical_skills, years_of_experience, highest_qualification, project_one_title, project_one_url, project_two_title, project_two_url, github_link, linkedin_link, availability_allocation)
                    VALUES 
                        (:user_id, :headline, :bio, :skills, :exp, :qualification, :p1_title, :p1_url, :p2_title, :p2_url, :github, :linkedin, :avail)";
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':headline' => $headline, ':bio' => $bio, ':skills' => $skills, ':exp' => $exp,
            ':qualification' => $qualification, ':p1_title' => $p1_title, ':p1_url' => $p1_url,
            ':p2_title' => $p2_title, ':p2_url' => $p2_url, ':github' => $github, ':linkedin' => $linkedin,
            ':avail' => $avail, ':user_id' => $user_id
        ]);

        $status_message = "Professional showcase portfolio compiled successfully.";
        $status_type = "success";
    } catch (Exception $e) {
        error_log("Portfolio pipeline database error: " . $e->getMessage());
        $status_message = "Database Sync Engine Failure: " . $e->getMessage();
        $status_type = "error";
    }
}

// Extraction Pipeline: Hydrate Display Nodes
$portfolio = array_fill_keys(['professional_headline', 'core_biography', 'technical_skills', 'years_of_experience', 'highest_qualification', 'project_one_title', 'project_one_url', 'project_two_title', 'project_two_url', 'github_link', 'linkedin_link', 'availability_allocation'], '');
$portfolio['years_of_experience'] = 0;
$portfolio['availability_allocation'] = 'immediate';

try {
    $fetch_stmt = $conn->prepare("SELECT * FROM jobseeker_portfolios WHERE jobseeker_id = :user_id");
    $fetch_stmt->execute([':user_id' => $user_id]);
    if ($data = $fetch_stmt->fetch(PDO::FETCH_ASSOC)) { $portfolio = $data; }
} catch (PDOException $e) { error_log("Portfolio fetch initialization failure: " . $e->getMessage()); }
?>

<style>
    .portfolio-wrapper { max-width: 850px; margin: 0 auto; }
    .meta-card { background: var(--bg-secondary); border: 1px solid var(--border-light); padding: 2.25rem; border-radius: var(--radius-xl); margin-bottom: 1.5rem; }
    .section-title { font-size: 1.15rem; font-weight: 600; color: #38bdf8; margin: 0 0 1.25rem 0; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1px solid rgba(56, 189, 248, 0.15); padding-bottom: 0.5rem; }
    .grid-2 { display: grid; grid-template-columns: 1fr; gap: 1.25rem; }
    @media (min-width: 768px) { .grid-2 { grid-template-columns: repeat(2, 1fr); } }
    .field-block { display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.25rem; }
    .field-block label { font-size: 0.8rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.03em; }
    .field-ctrl { padding: 0.75rem 1rem; background: rgba(0, 0, 0, 0.25); border: 1px solid var(--border-light); color: #fff; border-radius: 8px; font-size: 0.95rem; font-family: inherit; transition: var(--transition-smooth); }
    .field-ctrl:focus { border-color: #38bdf8; outline: none; background: rgba(0, 0, 0, 0.35); }
    .alert-banner { display: flex; align-items: center; gap: 0.75rem; padding: 1rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem; font-size: 0.9rem; word-break: break-word; }
    .alert-banner.success { background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2); border-left: 4px solid #10b981; color: var(--text-primary); }
    .alert-banner.error { background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; color: #fca5a5; }
    .deploy-btn { background: #38bdf8; border: none; color: var(--bg-primary); padding: 0.85rem 2rem; border-radius: 8px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: var(--transition-smooth); }
    .deploy-btn:hover { background: #0ea5e9; transform: translateY(-1px); }
</style>

<div class="portfolio-wrapper">
    <div style="margin-bottom: 2rem;">
        <span style="display:inline-block; background:rgba(56, 189, 248, 0.1); color:#38bdf8; padding:0.25rem 0.5rem; border-radius:4px; font-size:0.75rem; font-weight:700; text-transform:uppercase; margin-bottom:0.5rem;">Talent Matrix Ecosystem</span>
        <h1 style="font-size: 1.85rem; font-weight:700; margin:0 0 0.35rem 0; color: var(--text-primary);">Professional Project & Skill Portfolio</h1>
        <p style="margin:0; color: var(--text-secondary);">Manage your experience records, technical competencies, and verified live project assets here.</p>
    </div>

    <?php if ($status_message): ?>
        <div class="alert-banner <?php echo $status_type; ?>">
            <i class="fa-solid <?php echo $status_type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
            <span><?php echo htmlspecialchars($status_message); ?></span>
        </div>
    <?php endif; ?>

    <form action="portfolio.php" method="POST">
        
        <div class="meta-card">
            <h3 class="section-title"><i class="fa-solid fa-graduation-cap"></i> Professional Foundation</h3>
            
            <div class="field-block">
                <label for="professional_headline">Professional Headline</label>
                <input type="text" id="professional_headline" name="professional_headline" value="<?php echo htmlspecialchars($portfolio['professional_headline']); ?>" required placeholder="e.g., Full-Stack Engineer specializing in Tailwind CSS, Node.js and Enterprise Architectures" class="field-ctrl">
            </div>

            <div class="grid-2">
                <div class="field-block">
                    <label for="years_of_experience">Years of Experience</label>
                    <input type="number" id="years_of_experience" name="years_of_experience" min="0" max="40" value="<?php echo htmlspecialchars($portfolio['years_of_experience']); ?>" required class="field-ctrl">
                </div>
                <div class="field-block">
                    <label for="highest_qualification">Highest Qualification / Degree</label>
                    <input type="text" id="highest_qualification" name="highest_qualification" value="<?php echo htmlspecialchars($portfolio['highest_qualification']); ?>" placeholder="e.g., BSc in Business Management & Globalization" class="field-ctrl">
                </div>
            </div>

            <div class="grid-2">
                <div class="field-block">
                    <label for="technical_skills">Technical Stack Capabilities</label>
                    <input type="text" id="technical_skills" name="technical_skills" value="<?php echo htmlspecialchars($portfolio['technical_skills']); ?>" required placeholder="Separate with commas (e.g., PHP, MySQL, Flutter, Glassmorphism UI)" class="field-ctrl">
                </div>
                <div class="field-block">
                    <label for="availability_allocation">Market Availability State</label>
                    <select id="availability_allocation" name="availability_allocation" class="field-ctrl" style="background:#111827;">
                        <option value="immediate" <?php echo $portfolio['availability_allocation'] === 'immediate' ? 'selected' : ''; ?>>Available Immediately</option>
                        <option value="flexible" <?php echo $portfolio['availability_allocation'] === 'flexible' ? 'selected' : ''; ?>>Open to Offers</option>
                        <option value="unavailable" <?php echo $portfolio['availability_allocation'] === 'unavailable' ? 'selected' : ''; ?>>Fully Booked</option>
                    </select>
                </div>
            </div>

            <div class="field-block" style="margin-bottom:0;">
                <label for="core_biography">Professional Biography Summary</label>
                <textarea id="core_biography" name="core_biography" rows="5" required placeholder="Detail your system engineering milestones, framework fluencies, and practical problem-solving capacities..." class="field-ctrl" style="resize:vertical;"><?php echo htmlspecialchars($portfolio['core_biography']); ?></textarea>
            </div>
        </div>

        <div class="meta-card">
            <h3 class="section-title"><i class="fa-solid fa-diagram-project"></i> Project Showcase Repository</h3>
            <p style="margin: -0.5rem 0 1.25rem 0; font-size: 0.85rem; color: var(--text-secondary);">Expose live web deployments or source codes to prove your execution capacities directly to employers.</p>
            
            <div class="grid-2">
                <div class="field-block">
                    <label>Featured Project #1 Title</label>
                    <input type="text" name="project_one_title" value="<?php echo htmlspecialchars($portfolio['project_one_title']); ?>" placeholder="e.g., SkillBridgeSL Platform" class="field-ctrl">
                </div>
                <div class="field-block">
                    <label>Featured Project #1 Live Deployment URL</label>
                    <input type="url" name="project_one_url" value="<?php echo htmlspecialchars($portfolio['project_one_url']); ?>" placeholder="https://project-one.com" class="field-ctrl">
                </div>
            </div>

            <div class="grid-2" style="margin-bottom: 0;">
                <div class="field-block" style="margin-bottom: 0;">
                    <label>Featured Project #2 Title</label>
                    <input type="text" name="project_two_title" value="<?php echo htmlspecialchars($portfolio['project_two_title']); ?>" placeholder="e.g., Interactive Ice Cream UI" class="field-ctrl">
                </div>
                <div class="field-block" style="margin-bottom: 0;">
                    <label>Featured Project #2 Live Deployment URL</label>
                    <input type="url" name="project_two_url" value="<?php echo htmlspecialchars($portfolio['project_two_url']); ?>" placeholder="https://project-two.com" class="field-ctrl">
                </div>
            </div>
        </div>

        <div class="meta-card">
            <h3 class="section-title"><i class="fa-solid fa-link"></i> Professional Anchors</h3>
            <div class="grid-2" style="margin-bottom: 0;">
                <div class="field-block" style="margin-bottom: 0;">
                    <label for="github_link">GitHub Profile URL</label>
                    <input type="url" id="github_link" name="github_link" value="<?php echo htmlspecialchars($portfolio['github_link']); ?>" placeholder="https://github.com/yourusername" class="field-ctrl">
                </div>
                <div class="field-block" style="margin-bottom: 0;">
                    <label for="linkedin_link">LinkedIn Profile URL</label>
                    <input type="url" id="linkedin_link" name="linkedin_link" value="<?php echo htmlspecialchars($portfolio['linkedin_link']); ?>" placeholder="https://linkedin.com/in/yourusername" class="field-ctrl">
                </div>
            </div>
        </div>

        <div style="text-align: right; margin-bottom: 4rem;">
            <button type="submit" class="deploy-btn">
                <i class="fa-solid fa-circle-check"></i>
                <span>Deploy Professional Portfolio</span>
            </button>
        </div>

    </form>
</div>

<?php
$content = ob_get_clean();
require_once '../includes/dashboard_layout.php';
?>