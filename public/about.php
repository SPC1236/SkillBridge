<?php
$page_title = "About Us";
require_once '../includes/config.php';
require_once '../includes/header.php';
?>

<section class="dashboard-header">
    <div class="container">
        <div class="dashboard-welcome">
            <h1>About Skill<span>Bridge</span></h1>
            <p>Connecting talent with opportunity since 2024</p>
        </div>
    </div>
</section>

<section class="container">
    <!-- Mission Section -->
    <div class="card" style="margin-bottom: 3rem;">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="color: var(--dark-blue); margin-bottom: 1rem;">Our Mission</h2>
            <p style="font-size: 1.2rem; color: var(--dark-gray); max-width: 800px; margin: 0 auto;">
                To create a seamless platform where talented professionals and forward-thinking companies 
                can connect, collaborate, and achieve remarkable results together.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
            <div style="text-align: center;">
                <div style="background: var(--light-blue); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem;">🎯</div>
                <h3 style="margin-bottom: 1rem; color: var(--dark-blue);">Our Vision</h3>
                <p style="color: var(--dark-gray);">
                    To become the most trusted platform for freelance connections, 
                    empowering individuals to build meaningful careers and businesses 
                    to find exceptional talent.
                </p>
            </div>

            <div style="text-align: center;">
                <div style="background: var(--light-blue); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem;">💡</div>
                <h3 style="margin-bottom: 1rem; color: var(--dark-blue);">Our Values</h3>
                <p style="color: var(--dark-gray);">
                    Integrity, transparency, and excellence drive everything we do. 
                    We believe in creating win-win situations for both freelancers 
                    and employers.
                </p>
            </div>

            <div style="text-align: center;">
                <div style="background: var(--light-blue); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 2rem;">🚀</div>
                <h3 style="margin-bottom: 1rem; color: var(--dark-blue);">Our Promise</h3>
                <p style="color: var(--dark-gray);">
                    To continuously innovate and improve our platform, ensuring 
                    the best experience for our community of professionals and businesses.
                </p>
            </div>
        </div>
    </div>

    <!-- Story Section -->
    <div class="card" style="margin-bottom: 3rem;">
        <h2 style="text-align: center; color: var(--dark-blue); margin-bottom: 2rem;">Our Story</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
            <div>
                <h3 style="color: var(--dark-blue); margin-bottom: 1rem;">From Idea to Reality</h3>
                <p style="color: var(--dark-gray); margin-bottom: 1.5rem; line-height: 1.6;">
                    FreeLancePortal was born from a simple observation: talented professionals often struggle 
                    to find meaningful work, while businesses spend excessive time and resources searching 
                    for the right talent.
                </p>
                <p style="color: var(--dark-gray); margin-bottom: 1.5rem; line-height: 1.6;">
                    Founded in 2024, our platform bridges this gap by creating a streamlined, transparent, 
                    and efficient marketplace where connections happen naturally and projects come to life.
                </p>
                <p style="color: var(--dark-gray); line-height: 1.6;">
                    Today, we're proud to have built a community of thousands of professionals and 
                    businesses who trust us to facilitate their success stories.
                </p>
            </div>
            <div style="text-align: center;">
                <div style="background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue)); color: white; padding: 3rem 2rem; border-radius: 1rem;">
                    <div style="font-size: 3rem; margin-bottom: 1rem;">📈</div>
                    <h3 style="margin-bottom: 0.5rem;">Growing Together</h3>
                    <p style="opacity: 0.9;">
                        Every connection made on our platform represents a step forward 
                        in building the future of work.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="card" style="margin-bottom: 3rem;">
        <h2 style="text-align: center; color: var(--dark-blue); margin-bottom: 2rem;">Why Choose SkillBridge?</h2>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="text-align: center;">
                <div style="background: var(--light-blue); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">🔒</div>
                <h4 style="margin-bottom: 0.5rem; color: var(--dark-blue);">Secure Platform</h4>
                <p style="color: var(--dark-gray); font-size: 0.9rem;">
                    Your data and privacy are protected with enterprise-level security measures.
                </p>
            </div>

            <div style="text-align: center;">
                <div style="background: var(--light-blue); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">⚡</div>
                <h4 style="margin-bottom: 0.5rem; color: var(--dark-blue);">Fast Connections</h4>
                <p style="color: var(--dark-gray); font-size: 0.9rem;">
                    Find the right match quickly with our advanced search and matching algorithms.
                </p>
            </div>

            <div style="text-align: center;">
                <div style="background: var(--light-blue); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">💼</div>
                <h4 style="margin-bottom: 0.5rem; color: var(--dark-blue);">Professional Network</h4>
                <p style="color: var(--dark-gray); font-size: 0.9rem;">
                    Connect with verified professionals and reputable companies in your industry.
                </p>
            </div>

            <div style="text-align: center;">
                <div style="background: var(--light-blue); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">🆓</div>
                <h4 style="margin-bottom: 0.5rem; color: var(--dark-blue);">Completely Free</h4>
                <p style="color: var(--dark-gray); font-size: 0.9rem;">
                    No hidden fees or commissions. We believe in accessible opportunities for all.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div style="background: linear-gradient(135deg, var(--black), var(--dark-blue)); color: white; padding: 3rem; border-radius: 1rem; text-align: center; margin-bottom: 3rem;">
        <h2 style="margin-bottom: 2rem;">Our Impact in Numbers</h2>
        <div class="dashboard-grid">
            <div>
                <div class="stat-number" style="color: white;">1,000+</div>
                <div class="stat-label">Professionals</div>
            </div>
            <div>
                <div class="stat-number" style="color: white;">500+</div>
                <div class="stat-label">Companies</div>
            </div>
            <div>
                <div class="stat-number" style="color: white;">95%</div>
                <div class="stat-label">Success Rate</div>
            </div>
            <div>
                <div class="stat-number" style="color: white;">24/7</div>
                <div class="stat-label">Support</div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="card" style="text-align: center;">
        <h2 style="color: var(--dark-blue); margin-bottom: 1rem;">Ready to Get Started?</h2>
        <p style="color: var(--dark-gray); margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
            Join our growing community today and discover how SkillBridge can transform 
            the way you work or hire talent.
        </p>
        <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
            <a href="<?php echo SITE_URL; ?>/auth/register_jobseeker.php" class="btn btn-primary">
                Start Freelancing
            </a>
            <a href="<?php echo SITE_URL; ?>/auth/register_employer.php" class="btn btn-outline">
                Hire Talent
            </a>
            <a href="<?php echo SITE_URL; ?>/public/contact.php" class="btn btn-secondary">
                Contact Us
            </a>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>