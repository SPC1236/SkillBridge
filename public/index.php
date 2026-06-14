<?php
$page_title = "Home - Find Freelance Talent or Work";
require_once '../includes/config.php';
require_once '../includes/header.php';
?>

<!-- Hero Section -->
<section class="hero fade-in">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Find <span style="color: var(--accent-primary);">Top Talent</span><br>Or Start Your Freelance Journey</h1>
                <p>Connect with skilled professionals worldwide or find high-paying freelance opportunities. Join thousands of successful freelancers and employers on the leading freelance platform.</p>
                <div class="hero-buttons">
                    <a href="<?php echo SITE_URL; ?>/auth/register_employer.php" class="btn btn-primary btn-large">
                        <i class="fas fa-search"></i> Find Talent
                    </a>
                    <a href="<?php echo SITE_URL; ?>/auth/register_jobseeker.php" class="btn btn-secondary btn-large">
                        <i class="fas fa-rocket"></i> Find Work
                    </a>
                </div>
                
                <!-- Trust indicators -->
                <div style="display: flex; gap: 2rem; margin-top: 2rem; flex-wrap: wrap;">
                    <div>
                        <span style="font-size: 1.5rem; font-weight: bold; color: var(--accent-primary);">10K+</span>
                        <p style="font-size: 0.875rem; margin-top: 0.25rem; color: var(--text-muted);">Active Freelancers</p>
                    </div>
                    <div>
                        <span style="font-size: 1.5rem; font-weight: bold; color: var(--accent-primary);">5K+</span>
                        <p style="font-size: 0.875rem; margin-top: 0.25rem; color: var(--text-muted);">Happy Employers</p>
                    </div>
                    <div>
                        <span style="font-size: 1.5rem; font-weight: bold; color: var(--accent-primary);">98%</span>
                        <p style="font-size: 0.875rem; margin-top: 0.25rem; color: var(--text-muted);">Success Rate</p>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%); border-radius: var(--radius-xl); padding: 2rem; text-align: center;">
                    <i class="fas fa-users" style="font-size: 8rem; color: var(--accent-primary); opacity: 0.8;"></i>
                    <p style="margin-top: 1rem; color: var(--text-muted);">Join 15,000+ professionals</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Categories Section -->
<section class="categories" style="padding: 4rem 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2>Popular Categories</h2>
            <p style="color: var(--text-muted);">Find the perfect talent for your project</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <?php
            $categories = [
                ['name' => 'Web Development', 'icon' => 'fa-code', 'count' => '2,500+'],
                ['name' => 'Mobile Apps', 'icon' => 'fa-mobile-alt', 'count' => '1,800+'],
                ['name' => 'UI/UX Design', 'icon' => 'fa-paintbrush', 'count' => '1,200+'],
                ['name' => 'Writing', 'icon' => 'fa-pen-fancy', 'count' => '1,500+'],
                ['name' => 'Marketing', 'icon' => 'fa-chart-line', 'count' => '1,300+'],
                ['name' => 'Data Science', 'icon' => 'fa-database', 'count' => '900+']
            ];
            foreach($categories as $category): ?>
                <div class="card" style="text-align: center; cursor: pointer; transition: all var(--transition-base);">
                    <i class="fas <?php echo $category['icon']; ?>" style="font-size: 2.5rem; color: var(--accent-primary); margin-bottom: 1rem;"></i>
                    <h3 style="font-size: 1.125rem; margin-bottom: 0.5rem;"><?php echo $category['name']; ?></h3>
                    <p style="font-size: 0.875rem; color: var(--text-muted);"><?php echo $category['count']; ?> freelancers</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Freelancers Section -->
<section style="padding: 4rem 0; background: var(--bg-secondary);">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2>Featured Freelancers</h2>
            <p style="color: var(--text-muted);">Top-rated professionals ready to work</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            <?php
            // This is a static example - replace with your actual database query
            $featured_freelancers = [
                ['name' => 'Sarah Johnson', 'role' => 'Full Stack Developer', 'rating' => 4.9, 'hourly_rate' => '$75/hr', 'skills' => ['React', 'Node.js', 'Python']],
                ['name' => 'Michael Chen', 'role' => 'UI/UX Designer', 'rating' => 4.8, 'hourly_rate' => '$65/hr', 'skills' => ['Figma', 'Adobe XD', 'Sketch']],
                ['name' => 'David Williams', 'role' => 'Digital Marketer', 'rating' => 4.9, 'hourly_rate' => '$55/hr', 'skills' => ['SEO', 'Google Ads', 'Analytics']],
            ];
            foreach($featured_freelancers as $freelancer): ?>
                <div class="talent-card">
                    <div class="talent-header">
                        <div class="talent-avatar">
                            <?php echo substr($freelancer['name'], 0, 1); ?>
                        </div>
                        <div class="talent-info">
                            <h3 style="margin-bottom: 0.25rem;"><?php echo $freelancer['name']; ?></h3>
                            <p style="font-size: 0.875rem; color: var(--text-muted);"><?php echo $freelancer['role']; ?></p>
                        </div>
                    </div>
                    <div class="talent-skills">
                        <?php foreach($freelancer['skills'] as $skill): ?>
                            <span class="skill-tag"><?php echo $skill; ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="talent-rating">
                        <i class="fas fa-star"></i>
                        <span><?php echo $freelancer['rating']; ?></span>
                        <span style="color: var(--text-muted); margin-left: auto;"><?php echo $freelancer['hourly_rate']; ?></span>
                    </div>
                    <a href="<?php echo SITE_URL; ?>/auth/login.php?role=employer" class="btn btn-primary" style="width: 100%; text-align: center;">Hire Now</a>
                </div>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 2rem;">
            <a href="<?php echo SITE_URL; ?>/employer/browse_seekers.php" class="btn btn-secondary">View All Freelancers <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section style="padding: 4rem 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2>How It Works</h2>
            <p style="color: var(--text-muted);">Simple steps to get started</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-user-plus" style="font-size: 2rem; color: var(--accent-primary);"></i>
                </div>
                <h3>1. Create Account</h3>
                <p style="color: var(--text-muted);">Sign up as a freelancer or employer in minutes</p>
            </div>
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-search" style="font-size: 2rem; color: var(--accent-primary);"></i>
                </div>
                <h3>2. Find Matches</h3>
                <p style="color: var(--text-muted);">Browse profiles or post jobs to find the perfect fit</p>
            </div>
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-handshake" style="font-size: 2rem; color: var(--accent-primary);"></i>
                </div>
                <h3>3. Start Working</h3>
                <p style="color: var(--text-muted);">Connect and collaborate on amazing projects</p>
            </div>
            <div class="card" style="text-align: center;">
                <div style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                    <i class="fas fa-chart-line" style="font-size: 2rem; color: var(--accent-primary);"></i>
                </div>
                <h3>4. Grow Career</h3>
                <p style="color: var(--text-muted);">Build reputation and get more opportunities</p>
            </div>
        </div>
    </div>
</section>

<!-- Platform Statistics Section -->
<section style="padding: 4rem 0; background: var(--bg-secondary);">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem; text-align: center;">
            <div>
                <div style="font-size: 3rem; font-weight: bold; color: var(--accent-primary);">15K+</div>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">Registered Users</p>
            </div>
            <div>
                <div style="font-size: 3rem; font-weight: bold; color: var(--accent-primary);">8K+</div>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">Jobs Completed</p>
            </div>
            <div>
                <div style="font-size: 3rem; font-weight: bold; color: var(--accent-primary);">4.9</div>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">Average Rating</p>
            </div>
            <div>
                <div style="font-size: 3rem; font-weight: bold; color: var(--accent-primary);">120+</div>
                <p style="color: var(--text-muted); margin-top: 0.5rem;">Countries</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section style="padding: 4rem 0;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2>What Our Users Say</h2>
            <p style="color: var(--text-muted);">Success stories from our community</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div class="card">
                <i class="fas fa-quote-left" style="font-size: 2rem; color: var(--accent-primary); margin-bottom: 1rem; opacity: 0.5;"></i>
                <p style="margin-bottom: 1rem;">"Found amazing freelancers for my startup. The platform made it so easy to connect with top talent."</p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; background: var(--accent-primary); border-radius: 50%;"></div>
                    <div>
                        <strong>John Smith</strong>
                        <p style="font-size: 0.875rem; color: var(--text-muted);">CEO, TechStart</p>
                    </div>
                </div>
            </div>
            <div class="card">
                <i class="fas fa-quote-left" style="font-size: 2rem; color: var(--accent-primary); margin-bottom: 1rem; opacity: 0.5;"></i>
                <p style="margin-bottom: 1rem;">"As a freelancer, this platform helped me grow my career. I've worked with amazing clients from around the world."</p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; background: var(--accent-primary); border-radius: 50%;"></div>
                    <div>
                        <strong>Emily Chen</strong>
                        <p style="font-size: 0.875rem; color: var(--text-muted);">Full Stack Developer</p>
                    </div>
                </div>
            </div>
            <div class="card">
                <i class="fas fa-quote-left" style="font-size: 2rem; color: var(--accent-primary); margin-bottom: 1rem; opacity: 0.5;"></i>
                <p style="margin-bottom: 1rem;">"The best freelance platform I've used. Professional, secure, and great support team."</p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; background: var(--accent-primary); border-radius: 50%;"></div>
                    <div>
                        <strong>Michael Brown</strong>
                        <p style="font-size: 0.875rem; color: var(--text-muted);">Digital Marketing Expert</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section style="padding: 4rem 0; background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);">
    <div class="container">
        <div class="card" style="text-align: center; background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%); border: 2px solid var(--accent-primary);">
            <h2 style="margin-bottom: 1rem;">Ready to Get Started?</h2>
            <p style="margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">Join thousands of professionals already using SkillBridge</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo SITE_URL; ?>/auth/register_jobseeker.php" class="btn btn-primary btn-large">Join as Freelancer</a>
                <a href="<?php echo SITE_URL; ?>/auth/register_employer.php" class="btn btn-secondary btn-large">Hire Talent</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section (Original) -->
<section style="padding: 4rem 0;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2.5rem;">Why Choose SkillBridge?</h2>
        <div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div class="card">
                <div style="text-align: center;">
                    <div style="background: rgba(16, 185, 129, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem;">
                        <i class="fas fa-briefcase" style="color: var(--accent-primary);"></i>
                    </div>
                    <h3 style="margin-bottom: 1rem;">For Job Seekers</h3>
                    <ul style="text-align: left; list-style: none;">
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Create professional profiles</li>
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Showcase your skills & portfolio</li>
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Get discovered by employers</li>
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Find freelance opportunities</li>
                    </ul>
                </div>
            </div>
            
            <div class="card">
                <div style="text-align: center;">
                    <div style="background: rgba(16, 185, 129, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem;">
                        <i class="fas fa-building" style="color: var(--accent-primary);"></i>
                    </div>
                    <h3 style="margin-bottom: 1rem;">For Employers</h3>
                    <ul style="text-align: left; list-style: none;">
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Access qualified freelancers</li>
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Filter by skills & expertise</li>
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Direct contact options</li>
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Find the perfect match</li>
                    </ul>
                </div>
            </div>
            
            <div class="card">
                <div style="text-align: center;">
                    <div style="background: rgba(16, 185, 129, 0.1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem;">
                        <i class="fas fa-shield-alt" style="color: var(--accent-primary);"></i>
                    </div>
                    <h3 style="margin-bottom: 1rem;">Fast & Secure</h3>
                    <ul style="text-align: left; list-style: none;">
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Easy registration process</li>
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Secure data protection</li>
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Professional interface</li>
                        <li style="padding: 0.5rem 0;"><i class="fas fa-check" style="color: var(--accent-primary); margin-right: 0.5rem;"></i> Admin monitoring</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>