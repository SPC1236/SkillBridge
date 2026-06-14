<?php
// jobseeker/discover.php
// Performance-Tuned Job Discovery Engine - Updated Column Sync & Actions

$page_title = "Discover Opportunities";
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/auth_check.php';

// Instantiate database connection
$database = new Database();
$conn = $database->getConnection();

// Fetch active jobs based strictly on your exact table columns
try {
    $query = "SELECT id, employer_id, title, company_name, location, salary, 
                     job_type, salary_min, salary_max, skills_required, description, status 
              FROM jobs 
              WHERE status = 'active' 
              ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Job Discovery Fetch Failure: " . $e->getMessage());
    $jobs = []; // Fallback to empty array
}

ob_start();
?>

<style>
    :root {
        --glass-bg: rgba(17, 24, 39, 0.7);
        --glass-border: rgba(255, 255, 255, 0.08);
        --accent-glow: rgba(56, 189, 248, 0.15);
    }

    .discover-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    /* Glassmorphic Search & Filter Panel */
    .filter-panel {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    }

    /* Job Card Styling */
    .job-card {
        background: rgba(31, 41, 55, 0.4);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .job-card:hover {
        border-color: #38bdf8;
        transform: translateY(-2px);
        box-shadow: 0 0 20px var(--accent-glow);
        background: rgba(31, 41, 55, 0.6);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-fulltime { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-parttime { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-remote { background: rgba(56, 189, 248, 0.1); color: #38bdf8; }
    .badge-contract { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    /* Action Buttons Design */
    .action-btn-secondary {
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #d1d5db;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .action-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.2);
    }

    .action-btn-primary {
        background: #38bdf8;
        color: #0f172a;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.2s ease;
    }
    .action-btn-primary:hover {
        background: #0ea5e9;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
    }
</style>

<div class="discover-container animate-fade-in">
    <div class="mb-8">
        <span class="inline-block bg-sky-500/10 text-sky-400 px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider mb-2">Marketplace Node</span>
        <h1 class="text-3xl font-extrabold text-white tracking-tight">Explore Live Opportunities</h1>
        <p class="text-gray-400 mt-1">Discover ecosystem entries tailored to your technical stack and baseline constraints.</p>
    </div>

    <div class="filter-panel p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" id="searchKeyword" placeholder="Search by title, technical skills, or keywords..." 
                       class="w-full pl-11 pr-4 py-3 bg-black/30 border border-gray-700/60 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-sky-400 transition-smooth">
            </div>
            
            <div>
                <select id="filterType" class="w-full px-4 py-3 bg-black/30 border border-gray-700/60 rounded-xl text-gray-300 focus:outline-none focus:border-sky-400 transition-smooth">
                    <option value="">All Job Types</option>
                    <option value="full-time">Full-Time</option>
                    <option value="part-time">Part-Time</option>
                    <option value="remote">Remote Deployment</option>
                    <option value="contract">Contractual Node</option>
                </select>
            </div>

            <div>
                <select id="filterLocation" class="w-full px-4 py-3 bg-black/30 border border-gray-700/60 rounded-xl text-gray-300 focus:outline-none focus:border-sky-400 transition-smooth">
                    <option value="">All Locations</option>
                    <option value="freetown">Freetown</option>
                    <option value="remote">Remote / Digital</option>
                    <option value="bo">Bo</option>
                    <option value="kenema">Kenema</option>
                    <option value="makeni">Makeni</option>
                </select>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-400">Showing <span id="visibleCount" class="text-sky-400 font-bold"><?php echo count($jobs); ?></span> verified listings</p>
        <button id="clearFilters" class="text-xs text-gray-500 hover:text-sky-400 transition-smooth hidden">Clear Filters</button>
    </div>

    <div id="jobsGrid" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="job-card p-6 flex flex-col justify-between" 
                     data-title="<?php echo strtolower(htmlspecialchars($job['title'])); ?>"
                     data-skills="<?php echo strtolower(htmlspecialchars($job['skills_required'] ?? '')); ?>"
                     data-type="<?php echo strtolower(htmlspecialchars($job['job_type'])); ?>"
                     data-location="<?php echo strtolower(htmlspecialchars($job['location'] ?? '')); ?>">
                    
                    <div>
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center border border-gray-700 text-sky-400 font-bold text-lg">
                                    <?php echo strtoupper(substr($job['company_name'] ?? $job['title'], 0, 1)); ?>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white leading-snug hover:text-sky-400 transition-smooth">
                                        <a href="job_details.php?id=<?php echo $job['id']; ?>"><?php echo htmlspecialchars($job['title']); ?></a>
                                    </h3>
                                    <p class="text-sm text-gray-400"><?php echo htmlspecialchars($job['company_name'] ?? 'Verified Enterprise'); ?></p>
                                </div>
                            </div>
                            
                            <span class="badge badge-<?php echo str_replace('-', '', strtolower($job['job_type'])); ?>">
                                <?php echo htmlspecialchars($job['job_type']); ?>
                            </span>
                        </div>

                        <p class="text-sm text-gray-400 line-clamp-3 mb-4">
                            <?php echo htmlspecialchars($job['description']); ?>
                        </p>

                        <?php if (!empty($job['skills_required'])): ?>
                            <div class="flex flex-wrap gap-1.5 mb-4">
                                <?php 
                                $skills = explode(',', $job['skills_required']);
                                foreach ($skills as $skill): 
                                ?>
                                    <span class="text-[11px] bg-white/5 border border-white/5 text-gray-300 px-2 py-0.5 rounded">
                                        <?php echo htmlspecialchars(trim($skill)); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="flex items-center gap-4 text-xs text-gray-400 mb-6">
                            <span><i class="fa-solid fa-location-dot mr-1.5 text-sky-500"></i><?php echo htmlspecialchars($job['location'] ?? 'Remote'); ?></span>
                            <?php if (!empty($job['salary'])): ?>
                                <span><i class="fa-solid fa-money-bill-wave mr-1.5 text-emerald-500"></i><?php echo htmlspecialchars($job['salary']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-800/60 flex items-center justify-between text-sm">
                        <a href="job_details.php?id=<?php echo $job['id']; ?>" class="action-btn-secondary text-center">
                            Analyze Details
                        </a>
                        <a href="apply_to_job.php?id=<?php echo $job['id']; ?>" class="action-btn-primary text-center inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-paper-plane text-xs"></i> Apply to Job
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full filter-panel p-12 text-center">
                <i class="fa-solid fa-box-open text-4xl text-gray-600 mb-3"></i>
                <h3 class="text-xl font-bold text-white mb-1">No Active Listings Enlisted</h3>
                <p class="text-gray-400 text-sm">There are currently no active system deployment requirements registered to the grid.</p>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchKeyword = document.getElementById('searchKeyword');
            const filterType = document.getElementById('filterType');
            const filterLocation = document.getElementById('filterLocation');
            const clearFilters = document.getElementById('clearFilters');
            const visibleCount = document.getElementById('visibleCount');
            const jobCards = document.querySelectorAll('.job-card');

            function filterJobs() {
                const searchVal = searchKeyword.value.toLowerCase().trim();
                const typeVal = filterType.value.toLowerCase();
                const locationVal = filterLocation.value.toLowerCase();
                
                let matchesCount = 0;

                if (searchVal || typeVal || locationVal) {
                    clearFilters.classList.remove('hidden');
                } else {
                    clearFilters.classList.add('hidden');
                }

                jobCards.forEach(card => {
                    const title = card.getAttribute('data-title');
                    const skills = card.getAttribute('data-skills');
                    const type = card.getAttribute('data-type');
                    const location = card.getAttribute('data-location');

                    const matchesSearch = !searchVal || title.includes(searchVal) || skills.includes(searchVal);
                    const matchesType = !typeVal || type === typeVal;
                    const matchesLocation = !locationVal || location.includes(locationVal);

                    if (matchesSearch && matchesType && matchesLocation) {
                        card.style.display = 'flex';
                        matchesCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                visibleCount.textContent = matchesCount;
            }

            searchKeyword.addEventListener('input', filterJobs);
            filterType.addEventListener('change', filterJobs);
            filterLocation.addEventListener('change', filterJobs);

            clearFilters.addEventListener('click', function() {
                searchKeyword.value = '';
                filterType.value = '';
                filterLocation.value = '';
                filterJobs();
            });
        });
    </script>
</div>

<?php
$content = ob_get_clean();
require_once '../includes/dashboard_layout.php';
?>