<?php
// training.php
$page_title = "Growth & Training Repositories";
require_once 'includes/config.php';
require_once 'includes/database.php';

ob_start();
$database = new Database();
$conn = $database->getConnection();

try {
    $stmt = $conn->query("SELECT * FROM training_resources ORDER BY id DESC");
    $resources = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) { $resources = []; }
?>

<style>
    .resources-wrapper { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
    .resource-glass-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-xl);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .category-pill {
        background: rgba(56, 189, 248, 0.08);
        border: 1px solid rgba(56, 189, 248, 0.2);
        color: #38bdf8;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 0.75rem;
    }
</style>

<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 1.85rem; font-weight:700; color: var(--text-primary); margin:0;">Resource & Up-skilling Center</h1>
    <p style="color: var(--text-secondary); margin:0;">Access curated documentation blueprints to boost your deployment productivity.</p>
</div>

<div class="resources-wrapper">
    <?php if(!empty($resources)): ?>
        <?php foreach($resources as $res): ?>
            <div class="resource-glass-card">
                <div>
                    <span class="category-pill"><?php echo htmlspecialchars($res['category']); ?></span>
                    <h3 style="margin:0 0 0.5rem 0; font-size:1.1rem; color: var(--text-primary);"><?php echo htmlspecialchars($res['title']); ?></h3>
                    <p style="font-size:0.85rem; color: var(--text-secondary); line-height:1.5; margin-bottom:1.25rem;"><?php echo htmlspecialchars($res['description']); ?></p>
                </div>
                <a href="<?php echo htmlspecialchars($res['resource_link']); ?>" target="_blank" style="color:#38bdf8; text-decoration:none; font-size:0.85rem; font-weight:600; display:inline-flex; align-items:center; gap:0.25rem;">
                    Access Resource Material <i class="fas fa-arrow-up-right-from-square"></i>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color: var(--text-muted);">No training programs are broadcast right now.</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
require_once 'includes/dashboard_layout.php';
?>