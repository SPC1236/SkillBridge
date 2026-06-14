<?php
// dashboard_layout.php
// Modernized Dashboard Core Wrapper - Light Blue Theme System Sync

// Security check
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . (defined('SITE_URL') ? SITE_URL : '/freelance_portal') . '/auth/login.php');
    exit;
}

// Helper to determine active classes cleanly
if (!function_exists('is_active_page')) {
    function is_active_page($page_names) {
        $current_page = basename($_SERVER['PHP_SELF']);
        if (is_array($page_names)) {
            return in_array($current_page, $page_names) ? 'active' : '';
        }
        return $current_page === $page_names ? 'active' : '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - ' : ''; ?><?php echo htmlspecialchars(defined('SITE_NAME') ? SITE_NAME : 'Freelance Portal'); ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="/freelance_portal/assets/css/style.css">
    <link rel="stylesheet" href="/freelance_portal/assets/css/dashboard.css">
    
    <style>
        /* Modern Design Tokens synced with your Light Blue UI framework */
        :root {
            --bg-primary: #0b0f19;
            --bg-secondary: #111827;
            --bg-card: #1e293b;
            --bg-card-hover: #374151;
            --accent-primary: #3b82f6;
            --accent-hover: #2563eb;
            --accent-glow: rgba(56, 189, 248, 0.12);
            --text-primary: #F9FAFB;
            --text-secondary: #cbd5e1;
            --text-muted: #64748b;
            --border-light: #1e293b;
            --sidebar-width: 260px;
            --topbar-height: 70px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --transition-smooth: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        /* Structured App Container Setup */
        .app-container {
            display: flex;
            min-height: 100vh;
        }

        /* Modernized Layout Sidebar Components */
        .dashboard-sidebar {
            width: var(--sidebar-width);
            background: var(--bg-secondary);
            border-right: 1px solid var(--border-light);
            padding: 1.75rem 1.25rem;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: var(--transition-smooth);
            box-sizing: border-box;
        }

        /* Interactive Profile Branding Deck */
        .sidebar-user {
            text-align: center;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .sidebar-avatar {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-hover));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0 auto 0.85rem;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(56, 189, 248, 0.2);
        }

        .sidebar-user h3 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-role {
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--accent-primary);
            margin: 0;
        }

        /* Navigation Links List Design Elements */
        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex: 1;
            overflow-y: auto;
            padding-right: 2px;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--border-light);
            border-radius: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-lg);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition-smooth);
        }

        .sidebar-link i {
            width: 20px;
            font-size: 1.1rem;
            text-align: center;
            transition: var(--transition-smooth);
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-primary);
        }

        .sidebar-link.active {
            background: var(--accent-glow);
            color: var(--accent-primary);
            font-weight: 600;
        }
        
        .sidebar-link.active i {
            color: var(--accent-primary);
        }

        .sidebar-footer {
            padding-top: 1.25rem;
            border-top: 1px solid var(--border-light);
            margin-top: auto;
        }

        .sidebar-link.logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* Main Display Canvas Ecosystem */
        .dashboard-main-view {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-width: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: var(--transition-smooth);
        }

        .dashboard-content-frame {
            flex: 1;
            padding: 2.25rem 2.5rem;
        }

        /* Responsive UI & Native Mobile Drawers */
        .mobile-sidebar-toggle {
            display: none;
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 101;
            background: var(--accent-primary);
            border: none;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            color: #ffffff;
            font-size: 1.35rem;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(56, 189, 248, 0.4);
            transition: var(--transition-smooth);
        }

        .mobile-sidebar-toggle:hover {
            background: var(--accent-hover);
            transform: scale(1.05);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 99;
        }

        @media (max-width: 1024px) {
            :root {
                --sidebar-width: 240px;
            }
            .dashboard-content-frame {
                padding: 1.75rem 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            
            .dashboard-sidebar.active {
                left: 0;
            }

            .dashboard-main-view {
                margin-left: 0;
            }

            .mobile-sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
</head>
<body>

    <div class="app-container">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="dashboard-sidebar" id="dashboardSidebar">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    <?php 
                    $role = $_SESSION['user_role'] ?? 'admin';
                    if ($role === 'jobseeker') {
                        echo htmlspecialchars(substr($_SESSION['full_name'] ?? 'U', 0, 1));
                    } elseif ($role === 'employer') {
                        echo htmlspecialchars(substr($_SESSION['company_name'] ?? 'E', 0, 1));
                    } else {
                        echo 'A';
                    }
                    ?>
                </div>
                <h3>
                    <?php 
                    if ($role === 'jobseeker') {
                        echo htmlspecialchars($_SESSION['full_name'] ?? 'User');
                    } elseif ($role === 'employer') {
                        echo htmlspecialchars($_SESSION['company_name'] ?? 'Employer');
                    } else {
                        echo 'Administrator';
                    }
                    ?>
                </h3>
                <p class="sidebar-role">
                    <?php 
                    if ($role === 'jobseeker') {
                        echo 'Freelancer';
                    } elseif ($role === 'employer') {
                        echo 'Employer';
                    } else {
                        echo 'Admin';
                    }
                    ?>
                </p>
            </div>
            
            <nav class="sidebar-nav">
                <?php if ($role === 'jobseeker'): ?>
                    <a href="/freelance_portal/jobseeker/dashboard.php" class="sidebar-link <?php echo is_active_page('dashboard.php'); ?>">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/freelance_portal/jobseeker/profile.php" class="sidebar-link <?php echo is_active_page('profile.php'); ?>">
                        <i class="fas fa-user-circle"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="/freelance_portal/jobseeker/edit_profile.php" class="sidebar-link <?php echo is_active_page('edit_profile.php'); ?>">
                        <i class="fas fa-user-gear"></i>
                        <span>Edit Profile</span>
                    </a>
                    <a href="/freelance_portal/jobseeker/applied_jobs.php" class="sidebar-link <?php echo is_active_page('applied_jobs.php'); ?>">
                        <i class="fas fa-briefcase"></i>
                        <span>Applied Jobs</span>
                    </a>
                    <a href="/freelance_portal/jobseeker/saved_jobs.php" class="sidebar-link <?php echo is_active_page('saved_jobs.php'); ?>">
                        <i class="fas fa-bookmark"></i>
                        <span>Saved Jobs</span>
                    </a>
                    <a href="/freelance_portal/jobseeker/analytics.php" class="sidebar-link <?php echo is_active_page('analytics.php'); ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
                    </a>
                    <a href="/freelance_portal/public/contact.php" class="sidebar-link <?php echo is_active_page('contact.php'); ?>">
                        <i class="fas fa-circle-question"></i>
                        <span>Support</span>
                    </a>
                    
                <?php elseif ($role === 'employer'): ?>
                    <a href="/freelance_portal/employer/dashboard.php" class="sidebar-link <?php echo is_active_page('dashboard.php'); ?>">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/freelance_portal/employer/browse_seekers.php" class="sidebar-link <?php echo is_active_page('browse_seekers.php'); ?>">
                        <i class="fas fa-user-magnifying-glass"></i>
                        <span>Find Talent</span>
                    </a>
                    <a href="/freelance_portal/employer/profile.php" class="sidebar-link <?php echo is_active_page('profile.php'); ?>">
                        <i class="fas fa-building"></i>
                        <span>Company Profile</span>
                    </a>
                    <a href="/freelance_portal/public/contact.php" class="sidebar-link <?php echo is_active_page('contact.php'); ?>">
                        <i class="fas fa-circle-question"></i>
                        <span>Support</span>
                    </a>
                    
                <?php elseif ($role === 'admin'): ?>
                    <a href="/freelance_portal/admin/dashboard.php" class="sidebar-link <?php echo is_active_page('dashboard.php'); ?>">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/freelance_portal/admin/manage_users.php" class="sidebar-link <?php echo is_active_page('manage_users.php'); ?>">
                        <i class="fas fa-users-gear"></i>
                        <span>Manage Users</span>
                    </a>
                    <a href="/freelance_portal/admin/manage_jobs.php" class="sidebar-link <?php echo is_active_page('manage_jobs.php'); ?>">
                        <i class="fas fa-briefcase"></i>
                        <span>Manage Jobs</span>
                    </a>
                    <a href="/freelance_portal/admin/upload_resource.php" class="sidebar-link <?php echo is_active_page(['upload_resource.php']); ?>">
                        <i class="fas fa-cloud-arrow-up"></i>
                        <span>Upload Resources</span>
                    </a>
                    <a href="/freelance_portal/admin/statistics.php" class="sidebar-link <?php echo is_active_page('statistics.php'); ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Statistics</span>
                    </a>
                    <a href="/freelance_portal/public/contact.php" class="sidebar-link <?php echo is_active_page('contact.php'); ?>">
                        <i class="fas fa-circle-question"></i>
                        <span>Support</span>
                    </a>
                <?php endif; ?>
            </nav>
            
            <div class="sidebar-footer">
                <a href="/freelance_portal/auth/logout.php" class="sidebar-link logout-btn">
                    <i class="fas fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <button class="mobile-sidebar-toggle" id="mobileSidebarToggle" aria-label="Toggle Navigation Sidebar Menu">
            <i class="fas fa-bars-staggered"></i>
        </button>

        <div class="dashboard-main-view">
            <main class="dashboard-content-frame">
                <?php echo $content ?? ''; ?>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.getElementById('mobileSidebarToggle');
            const sidebar = document.getElementById('dashboardSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleIcon = mobileToggle ? mobileToggle.querySelector('i') : null;

            function toggleSidebar() {
                const isActive = sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                
                if (toggleIcon) {
                    if (isActive) {
                        toggleIcon.classList.replace('fa-bars-staggered', 'fa-xmark');
                    } else {
                        toggleIcon.classList.replace('fa-xmark', 'fa-bars-staggered');
                    }
                }
            }

            if (mobileToggle && sidebar && overlay) {
                mobileToggle.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
</body>
</html>