<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome 6 (Free Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main Redesigned CSS -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/auth.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/dashboard.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/jobseeker_profile.css?v=<?php echo time(); ?>">
    
    <style>
        /* Ensure redesign takes precedence */
        body {
            background: var(--bg-primary) !important;
        }
        
        /* Hide any conflicting old styles */
        .container {
            max-width: 1280px !important;
        }
    </style>
</head>
<body>
    <!-- Modern Header Navigation -->
    <header class="header">
        <nav class="navbar">
            <a href="<?php echo SITE_URL; ?>/" class="logo">
                Skill<span>Bridge</span>SL
            </a>
            
            <!-- Mobile Menu Toggle Button -->
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
            
            <ul class="nav-links" id="navLinks">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- Logged-in User Navigation -->
                    <?php if($_SESSION['user_role'] == 'jobseeker'): ?>
                        <li><a href="<?php echo SITE_URL; ?>/jobseeker/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/jobseeker/profile.php"><i class="fas fa-user"></i> My Profile</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/public/contact.php"><i class="fas fa-headset"></i> Contact Support</a></li>
                    <?php elseif($_SESSION['user_role'] == 'employer'): ?>
                        <li><a href="<?php echo SITE_URL; ?>/employer/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/employer/browse_seekers.php"><i class="fas fa-search"></i> Find Talent</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/employer/search.php"><i class="fas fa-filter"></i> Advanced Search</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/public/contact.php"><i class="fas fa-envelope"></i> Contact Support</a></li>
                    <?php elseif($_SESSION['user_role'] == 'admin'): ?>
                        <li><a href="<?php echo SITE_URL; ?>/admin/dashboard.php"><i class="fas fa-chart-pie"></i> Dashboard</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/admin/manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/admin/statistics.php"><i class="fas fa-chart-bar"></i> Statistics</a></li>
                    <?php endif; ?>
                    <li class="user-greeting">
                        <span class="greeting-badge">
                            <i class="fas fa-user-circle"></i>
                            <?php 
                            if($_SESSION['user_role'] == 'jobseeker') {
                                echo "Welcome, " . htmlspecialchars($_SESSION['full_name']);
                            } elseif($_SESSION['user_role'] == 'employer') {
                                echo htmlspecialchars($_SESSION['company_name']);
                            } elseif($_SESSION['user_role'] == 'admin') {
                                echo "Admin Panel";
                            }
                            ?>
                        </span>
                    </li>
                    <li><a href="<?php echo SITE_URL; ?>/auth/logout.php" class="btn btn-outline"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <!-- Public Navigation -->
                    <li><a href="<?php echo SITE_URL; ?>/"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/public/about.php"><i class="fas fa-info-circle"></i> About</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/public/contact.php"><i class="fas fa-envelope"></i> Contact</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/auth/register_jobseeker.php" class="btn btn-primary"><i class="fas fa-rocket"></i> Join the community</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/auth/register_employer.php" class="btn btn-secondary"><i class="fas fa-briefcase"></i> Hire Talent</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>