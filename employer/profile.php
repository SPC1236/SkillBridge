<?php
$page_title = "Company Profile";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/header.php';
require_once '../includes/auth_check.php';

// Initialize Database connection matching your platform standard
$database = new Database();
$conn = $database->getConnection();

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// --- HANDLE FORM SUBMISSION (POST REQUEST) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company_name = trim($_POST['company_name']);
    $industry     = trim($_POST['industry']);
    $website      = trim($_POST['website']);
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone']);
    $address      = trim($_POST['address']);
    $bio          = trim($_POST['bio']);

    // Check mandatory fields
    if (empty($company_name) || empty($email)) {
        $error = "Company Name and Business Email are required.";
    } else {
        // FIXED: Changed 'Employer' to 'employers' to match your database schema
        $update_sql = "UPDATE employers SET 
                        company_name = :company_name,
                        industry = :industry,
                        website = :website,
                        email = :email,
                        phone = :phone,
                        address = :address,
                        bio = :bio
                       WHERE id = :id";

        $stmt = $conn->prepare($update_sql);
        $stmt->bindParam(':company_name', $company_name);
        $stmt->bindParam(':industry', $industry);
        $stmt->bindParam(':website', $website);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':id', $user_id);

        if ($stmt->execute()) {
            // Keep header navigation session variables synchronized
            $_SESSION['company_name'] = $company_name;
            
            // Redirect to show the success alert banner cleanly
            header('Location: profile.php?success=updated');
            exit;
        } else {
            $error = "Failed to update profile. Please try again.";
        }
    }
}

// --- FETCH CURRENT PROFILE DATA FROM EMPLOYER TABLE ---
// Your fetch query was already correctly using 'employers' here:
$stmt = $conn->prepare("SELECT * FROM employers WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$profile = $stmt->fetch();

// Fallback logic to show existing data or default to session state if new
$company_name = $profile['company_name'] ?? ($_SESSION['company_name'] ?? '');
$industry     = $profile['industry'] ?? '';
$website      = $profile['website'] ?? '';
$email        = $profile['email'] ?? '';
$phone        = $profile['phone'] ?? '';
$address      = $profile['address'] ?? '';
$bio          = $profile['bio'] ?? '';
?>

<div class="dashboard-container">
    <aside class="dashboard-sidebar">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?php echo !empty($company_name) ? strtoupper(substr($company_name, 0, 1)) : 'C'; ?>
            </div>
            <h3><?php echo htmlspecialchars($company_name); ?></h3>
            <p class="sidebar-role">Employer</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="sidebar-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="browse_seekers.php" class="sidebar-link">
                <i class="fas fa-search"></i>
                <span>Find Talent</span>
            </a>
            <a href="search.php" class="sidebar-link">
                <i class="fas fa-filter"></i>
                <span>Advanced Search</span>
            </a>
            <a href="profile.php" class="sidebar-link active">
                <i class="fas fa-building"></i>
                <span>Company Profile</span>
            </a>
             <a href="post_a_job.php" class="sidebar-link">
                <i class="fas fa-file-alt"></i>
                <span>Posted Jobs</span>
            </a>
            <a href="view_applicants.php" class="sidebar-link">
                <i class="fas fa-users"></i>
                <span>Applicants</span>
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

    <main class="dashboard-main">
        <div class="dashboard-header">
            <div>
                <h1>Company Profile</h1>
                <p class="text-muted">Manage your business brand and public information</p>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error" style="background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--accent-primary); color: var(--accent-primary); padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> Profile updated successfully!
            </div>
        <?php endif; ?>

        <div class="card" style="margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <h3 style="margin: 0; color: var(--text-primary); font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-chart-pie" style="color: var(--accent-primary); margin-right: 0.5rem;"></i>Profile Completeness
                </h3>
                <span id="completenessPercentage" style="font-weight: 700; color: var(--accent-primary); font-size: 1.1rem;">0%</span>
            </div>
            <p class="text-muted" style="font-size: 0.875rem; margin-bottom: 1rem;">Ensure all fields are filled out to attract premium talent.</p>
            
            <div style="width: 100%; height: 8px; background: var(--bg-input); border-radius: 4px; overflow: hidden; margin-bottom: 1.25rem; border: 1px solid var(--border-light);">
                <div id="completenessProgressBar" style="width: 0%; height: 100%; background: linear-gradient(90deg, var(--accent-primary), var(--accent-hover)); transition: width 0.4s ease;"></div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 0.75rem; font-size: 0.85rem;">
                <div class="checklist-item" data-field="company_name" style="transition: color 0.2s ease;"><i class="far fa-circle" style="margin-right: 0.5rem;"></i> Company Name</div>
                <div class="checklist-item" data-field="industry" style="transition: color 0.2s ease;"><i class="far fa-circle" style="margin-right: 0.5rem;"></i> Industry Sector</div>
                <div class="checklist-item" data-field="website" style="transition: color 0.2s ease;"><i class="far fa-circle" style="margin-right: 0.5rem;"></i> Website URL</div>
                <div class="checklist-item" data-field="email" style="transition: color 0.2s ease;"><i class="far fa-circle" style="margin-right: 0.5rem;"></i> Business Email</div>
                <div class="checklist-item" data-field="phone" style="transition: color 0.2s ease;"><i class="far fa-circle" style="margin-right: 0.5rem;"></i> Phone Number</div>
                <div class="checklist-item" data-field="address" style="transition: color 0.2s ease;"><i class="far fa-circle" style="margin-right: 0.5rem;"></i> Office Location</div>
                <div class="checklist-item" data-field="bio" style="transition: color 0.2s ease;"><i class="far fa-circle" style="margin-right: 0.5rem;"></i> Company Bio</div>
            </div>
        </div>

        <div class="card">
            <form method="POST" action="" id="profileForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; margin-bottom: 2rem;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-briefcase" style="color: var(--accent-primary); font-size: 1.1rem;"></i>
                            <h3 style="color: var(--text-primary); margin: 0; font-size: 1.1rem; font-weight: 600;">Business Identity</h3>
                        </div>
                        
                        <div class="form-group">
                            <label for="company_name" class="form-label">Company Name *</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" value="<?php echo htmlspecialchars($company_name); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Industry Sector</label>
                            <div style="position: relative;">
                                <select name="industry" id="industry" class="form-control" style="appearance: none; -webkit-appearance: none; padding-right: 2.5rem;">
                                    <option value="">Choose Industry...</option>
                                    <option value="Technology" <?php echo $industry == 'Technology' ? 'selected' : ''; ?>>Technology & IT</option>
                                    <option value="Finance" <?php echo $industry == 'Finance' ? 'selected' : ''; ?>>Finance & Banking</option>
                                    <option value="Marketing" <?php echo $industry == 'Marketing' ? 'selected' : ''; ?>>Marketing & Creative</option>
                                    <option value="Healthcare" <?php echo $industry == 'Healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                                    <option value="Other" <?php echo $industry == 'Other' ? 'selected' : ''; ?>>Other Industry</option>
                                </select>
                                <i class="fas fa-chevron-down" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; font-size: 0.8rem;"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="website" class="form-label">Website URL</label>
                            <input type="url" id="website" name="website" class="form-control" value="<?php echo htmlspecialchars($website); ?>" placeholder="https://example.com">
                        </div>
                    </div>

                    <div>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid rgba(16, 185, 129, 0.1);">
                            <i class="fas fa-envelope-open-text" style="color: var(--accent-primary); font-size: 1.1rem;"></i>
                            <h3 style="color: var(--text-primary); margin: 0; font-size: 1.1rem; font-weight: 600;">Contact Details</h3>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Public Business Email *</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>">
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">Office Location Address</label>
                            <input type="text" id="address" name="address" class="form-control" value="<?php echo htmlspecialchars($address); ?>" placeholder="e.g., Lumley, Freetown">
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 2rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid rgba(16, 185, 129, 0.1);">
                        <i class="fas fa-id-card" style="color: var(--accent-primary); font-size: 1.1rem;"></i>
                        <h3 style="color: var(--text-primary); margin: 0; font-size: 1.1rem; font-weight: 600;">About the Company</h3>
                    </div>
                    <div class="form-group">
                        <label for="bio" class="form-label">Company Overview / Bio</label>
                        <textarea id="bio" name="bio" class="form-control" rows="5" style="resize: vertical; padding: 1rem;" placeholder="Describe what your company does..."><?php echo htmlspecialchars($bio); ?></textarea>
                    </div>
                </div>

                <div class="form-actions" style="text-align: center; display: flex; gap: 1rem; justify-content: flex-end;">
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </main>
</div>

<button class="mobile-sidebar-toggle" id="mobileSidebarToggle">
    <i class="fas fa-bars"></i>
</button>

<style>
/* Dashboard Styles context settings matching user ecosystem */
.dashboard-container { display: flex; min-height: calc(100vh - 70px); background: var(--bg-primary); }
.dashboard-sidebar { width: 280px; background: var(--bg-secondary); border-right: 1px solid var(--border-light); padding: 1.5rem; position: sticky; top: 70px; height: calc(100vh - 70px); overflow-y: auto; }
.sidebar-user { text-align: center; padding-bottom: 1.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-light); }
.sidebar-avatar { width: 80px; height: 80px; background: linear-gradient(135deg, var(--accent-primary), var(--accent-hover)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; margin: 0 auto 1rem; color: white; }
.sidebar-user h3 { font-size: 1rem; margin-bottom: 0.25rem; color: var(--text-primary); }
.sidebar-role { font-size: 0.75rem; color: var(--text-muted); }
.sidebar-nav { display: flex; flex-direction: column; gap: 0.25rem; }
.sidebar-link { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 8px; color: var(--text-secondary); text-decoration: none; }
.sidebar-link i { width: 20px; font-size: 1.125rem; }
.sidebar-link:hover, .sidebar-link.active { background: rgba(16, 185, 129, 0.1); color: var(--accent-primary); }
.sidebar-footer { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid var(--border-light); }
.dashboard-main { flex: 1; padding: 1.5rem; }
.dashboard-header { margin-bottom: 1.5rem; }
.dashboard-header h1 { font-size: 1.5rem; color: var(--text-primary); margin: 0; }
.text-muted { color: var(--text-muted); }
.card { background: var(--bg-card); border-radius: 16px; padding: 1.5rem; border: 1px solid var(--border-light); }
.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; margin-bottom: 0.5rem; color: var(--text-secondary); font-weight: 500; font-size: 0.875rem; }
.form-control { width: 100%; padding: 0.75rem 1rem; background: var(--bg-input); border: 1px solid var(--border-light); border-radius: 12px; color: var(--text-primary); font-size: 0.875rem; }
.form-control:focus { outline: none; border-color: var(--accent-primary); }
.btn { display: inline-flex; align-items: center; justify-content: center; padding: 0.625rem 1.25rem; font-size: 0.875rem; font-weight: 600; border-radius: 12px; text-decoration: none; cursor: pointer; border: none; }
.btn-primary { background: var(--accent-primary); color: white; }
.btn-secondary { background: transparent; color: var(--text-secondary); border: 1px solid var(--border-light); }
.mobile-sidebar-toggle { display: none; position: fixed; bottom: 1rem; right: 1rem; background: var(--accent-primary); width: 50px; height: 50px; border-radius: 50%; color: white; border: none; }
.form-actions { padding-top: 1.5rem; border-top: 1px solid var(--border-light); }
@media (max-width: 768px) {
    [style*="grid-template-columns: 1fr 1fr"] { grid-template-columns: 1fr !important; gap: 1rem !important; }
}
</style>

<script>
// Real-time completeness check tracker logic
document.addEventListener('DOMContentLoaded', function() {
    const fieldsToTrack = {
        company_name: document.getElementById('company_name'),
        industry: document.getElementById('industry'),
        website: document.getElementById('website'),
        email: document.getElementById('email'),
        phone: document.getElementById('phone'),
        address: document.getElementById('address'),
        bio: document.getElementById('bio')
    };

    function calculateCompleteness() {
        let filledCount = 0;
        const totalFields = Object.keys(fieldsToTrack).length;

        Object.keys(fieldsToTrack).forEach(key => {
            const element = fieldsToTrack[key];
            const itemLabel = document.querySelector(`.checklist-item[data-field="${key}"]`);
            
            if (element && element.value.trim() !== "") {
                filledCount++;
                if (itemLabel) {
                    itemLabel.style.color = "var(--accent-primary)";
                    itemLabel.querySelector('i').className = "fas fa-check-circle";
                }
            } else {
                if (itemLabel) {
                    itemLabel.style.color = "var(--text-muted)";
                    itemLabel.querySelector('i').className = "far fa-circle";
                }
            }
        });

        const scorePercent = Math.round((filledCount / totalFields) * 100);
        document.getElementById('completenessPercentage').innerText = scorePercent + "%";
        document.getElementById('completenessProgressBar').style.width = scorePercent + "%";
    }

    calculateCompleteness();
    Object.values(fieldsToTrack).forEach(inputElement => {
        if (inputElement) {
            inputElement.addEventListener('input', calculateCompleteness);
            inputElement.addEventListener('change', calculateCompleteness);
        }
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>