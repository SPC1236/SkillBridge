    </main>
    
    <!-- Modern Footer -->
    <footer class="footer">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="margin-bottom: 1rem;"><?php echo SITE_NAME; ?></h3>
                    <p style="color: var(--text-muted);">Connecting Talent with Opportunity</p>
                    <div style="margin-top: 1rem;">
                        <i class="fab fa-twitter" style="color: var(--text-muted); margin-right: 1rem; cursor: pointer;"></i>
                        <i class="fab fa-linkedin" style="color: var(--text-muted); margin-right: 1rem; cursor: pointer;"></i>
                        <i class="fab fa-github" style="color: var(--text-muted); cursor: pointer;"></i>
                    </div>
                </div>
                <div>
                    <h4 style="margin-bottom: 1rem;">For Job Seekers</h4>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 0.5rem;"><a href="<?php echo SITE_URL; ?>/auth/register_jobseeker.php" style="color: var(--text-muted); text-decoration: none;">Join the community</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-muted); text-decoration: none;">Find Work</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-muted); text-decoration: none;">Success Stories</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="margin-bottom: 1rem;">For Employers</h4>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 0.5rem;"><a href="<?php echo SITE_URL; ?>/auth/register_employer.php" style="color: var(--text-muted); text-decoration: none;">Post a Job</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-muted); text-decoration: none;">Find Talent</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-muted); text-decoration: none;">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="margin-bottom: 1rem;">Support</h4>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 0.5rem;"><a href="<?php echo SITE_URL; ?>/public/contact.php" style="color: var(--text-muted); text-decoration: none;">Contact Us</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-muted); text-decoration: none;">FAQ</a></li>
                        <li style="margin-bottom: 0.5rem;"><a href="#" style="color: var(--text-muted); text-decoration: none;">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div style="border-top: 1px solid var(--border-light); padding-top: 2rem; text-align: center;">
                <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
                <p style="margin-top: 0.5rem;">Building the future of work, together.</p>
            </div>
        </div>
    </footer>
    
    <!-- Mobile Menu JavaScript -->
    <script>
        // Mobile menu toggle functionality
        const menuToggle = document.getElementById('menuToggle');
        const navLinks = document.getElementById('navLinks');
        
        if (menuToggle && navLinks) {
            menuToggle.addEventListener('click', function() {
                navLinks.classList.toggle('active');
                const icon = menuToggle.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-bars');
                    icon.classList.toggle('fa-times');
                }
            });
        }
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            });
        });
        
        // Add fade-in animation to elements
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.card, .stat-card, .talent-card');
            animatedElements.forEach((el, index) => {
                setTimeout(() => {
                    el.classList.add('fade-in-up');
                }, index * 100);
            });
        });
    </script>
    
    <!-- Keep original JS files for functionality -->
    <script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>
    <script src="<?php echo SITE_URL; ?>/assets/js/validation.js"></script>
</body>
</html>