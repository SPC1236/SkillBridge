<?php
$page_title = "Contact Us";
require_once '../includes/config.php';
require_once '../includes/header.php';
?>

<section class="dashboard-header">
    <div class="container">
        <div class="dashboard-welcome">
            <h1>Contact Us 📞</h1>
            <p>Get in touch with our team</p>
        </div>
    </div>
</section>

<section class="container">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
        <div class="card">
            <h2 style="margin-bottom: 1.5rem; color: var(--dark-blue);">Send us a Message</h2>
            
            <form action="#" method="POST">
                <div class="form-group">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" placeholder="What is this regarding?" required>
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="6" placeholder="Tell us how we can help you..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>

        <div>
            <div class="card" style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--dark-blue);">📧 Email Us</h3>
                <p style="margin-bottom: 1rem; color: var(--dark-gray);">
                    Have questions? Send us an email and we'll get back to you as soon as possible.
                </p>
                <p>
                    <strong>Support:</strong> support@freelanceportal.com<br>
                    <strong>General:</strong> hello@freelanceportal.com
                </p>
            </div>

            <div class="card" style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--dark-blue);">🕒 Response Time</h3>
                <p style="color: var(--dark-gray);">
                    We typically respond to all inquiries within 24 hours during business days. 
                    For urgent matters, please indicate this in your message subject.
                </p>
            </div>

            <div class="card">
                <h3 style="margin-bottom: 1rem; color: var(--dark-blue);">💼 Business Hours</h3>
                <p style="color: var(--dark-gray); margin-bottom: 0.5rem;">
                    <strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM
                </p>
                <p style="color: var(--dark-gray); margin-bottom: 0.5rem;">
                    <strong>Saturday:</strong> 10:00 AM - 4:00 PM
                </p>
                <p style="color: var(--dark-gray);">
                    <strong>Sunday:</strong> Closed
                </p>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="card">
        <h2 style="margin-bottom: 1.5rem; color: var(--dark-blue);">Frequently Asked Questions</h2>
        
        <div style="display: grid; gap: 1rem;">
            <div style="padding: 1rem; background: var(--light-blue); border-radius: 0.5rem;">
                <h4 style="margin-bottom: 0.5rem;">How do I create an account as a freelancer?</h4>
                <p style="color: var(--dark-gray); margin: 0;">
                    Click on "Join as Freelancer" from the homepage or navigation menu. Fill out the registration form with your details, skills, and professional information.
                </p>
            </div>
            
            <div style="padding: 1rem; background: var(--light-blue); border-radius: 0.5rem;">
                <h4 style="margin-bottom: 0.5rem;">Can employers contact me directly?</h4>
                <p style="color: var(--dark-gray); margin: 0;">
                    Yes! Registered employers can view your profile and contact you directly via the email and phone number you provide in your profile.
                </p>
            </div>
            
            <div style="padding: 1rem; background: var(--light-blue); border-radius: 0.5rem;">
                <h4 style="margin-bottom: 0.5rem;">Is there a fee to use the platform?</h4>
                <p style="color: var(--dark-gray); margin: 0;">
                    Currently, FreelancePortal is completely free for both job seekers and employers. We're focused on building our community!
                </p>
            </div>
            
            <div style="padding: 1rem; background: var(--light-blue); border-radius: 0.5rem;">
                <h4 style="margin-bottom: 0.5rem;">How do I update my profile information?</h4>
                <p style="color: var(--dark-gray); margin: 0;">
                    After logging in, go to your dashboard and click "Edit Profile". You can update your skills, bio, portfolio link, and other information at any time.
                </p>
            </div>
        </div>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>