<?php
/**
 * NCB Website - Contact Page
 */

// Get flash message
$flash = get_flash();

// Get contact settings
$contactAddress  = get_setting('contact_address', 'NCB Community Center, 27 Jungang-daero, Dong-gu, Busan, South Korea 48221');
$contactPhone    = get_setting('contact_phone', '+82-10-1234-5678');
$contactEmail    = get_setting('contact_email', 'info@ncb.kr');
$contactHours    = get_setting('contact_hours', 'Mon – Sat, 10:00 AM – 6:00 PM KST');
$emergencyPhone  = get_setting('emergency_phone', '+82-10-9876-5432');

// Social media links
$socialFacebook  = get_setting('social_facebook', '');
$socialInstagram = get_setting('social_instagram', '');
$socialYoutube   = get_setting('social_youtube', '');
$socialTwitter   = get_setting('social_twitter', '');
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">GET IN TOUCH</span>
            <h1 class="page-hero__title">Contact Us</h1>
            <p class="page-hero__subtitle">Have a question or need assistance? Reach out to us and we'll get back to you as soon as possible.</p>
        </div>
    </div>
</section>

<!-- FLASH MESSAGE -->
<?php if ($flash): ?>
<div class="container" style="margin-top:1.5rem;">
    <div class="toast toast--<?= e($flash['type']) ?> fade-in" id="pageFlash">
        <span><?= e($flash['message']) ?></span>
    </div>
</div>
<?php endif; ?>

<!-- CONTACT SECTION -->
<section class="contact-section" id="contactSection">
    <div class="container">
        <div class="contact-section__grid">

            <!-- LEFT COLUMN: CONTACT FORM -->
            <div class="contact-section__form-col fade-in">
                <div class="contact-section__form-card">
                    <span class="section-badge">SEND A MESSAGE</span>
                    <h2 class="section-title">We'd Love to Hear From You</h2>
                    <p class="contact-section__form-desc">Fill out the form below and our team will respond within 24-48 hours.</p>

                    <form method="POST" action="actions/contact.php" class="contact-form">
                        <div class="form-group">
                            <label class="form-label" for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" class="form-input" placeholder="Enter your full name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="your.email@example.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+82-10-XXXX-XXXX">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" class="form-input" placeholder="What is this regarding?" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="message">Message *</label>
                            <textarea id="message" name="message" class="form-textarea" rows="8" placeholder="Write your message here..." required></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="form-submit btn btn--red btn--lg">Send Message &rarr;</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: INFO CARDS -->
            <div class="contact-section__info-col">

                <!-- ADDRESS CARD -->
                <div class="contact-section__info-card fade-in">
                    <div class="contact-section__info-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <h4 class="contact-section__info-title">Our Address</h4>
                    <p class="contact-section__info-text"><?= e($contactAddress) ?></p>
                </div>

                <!-- PHONE CARD -->
                <div class="contact-section__info-card fade-in">
                    <div class="contact-section__info-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    </div>
                    <h4 class="contact-section__info-title">Phone</h4>
                    <p class="contact-section__info-text">
                        <a href="tel:<?= e($contactPhone) ?>" class="contact-section__info-link"><?= e($contactPhone) ?></a>
                    </p>
                </div>

                <!-- EMAIL CARD -->
                <div class="contact-section__info-card fade-in">
                    <div class="contact-section__info-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <h4 class="contact-section__info-title">Email</h4>
                    <p class="contact-section__info-text">
                        <a href="mailto:<?= e($contactEmail) ?>" class="contact-section__info-link"><?= e($contactEmail) ?></a>
                    </p>
                </div>

                <!-- HOURS CARD -->
                <div class="contact-section__info-card fade-in">
                    <div class="contact-section__info-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <h4 class="contact-section__info-title">Office Hours</h4>
                    <p class="contact-section__info-text"><?= e($contactHours) ?></p>
                </div>

                <!-- EMERGENCY CONTACT CARD -->
                <div class="contact-section__emergency-card fade-in">
                    <div class="contact-section__emergency-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div class="contact-section__emergency-body">
                        <h4 class="contact-section__emergency-title">Emergency Hotline</h4>
                        <p class="contact-section__emergency-desc">For medical emergencies, accidents, or urgent situations, call our 24/7 emergency line.</p>
                        <a href="tel:<?= e($emergencyPhone) ?>" class="contact-section__emergency-phone">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <?= e($emergencyPhone) ?>
                        </a>
                    </div>
                </div>

                <!-- MAP PLACEHOLDER -->
                <div class="contact-section__map fade-in">
                    <div class="contact-section__map-placeholder">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span>Map</span>
                    </div>
                </div>

                <!-- SOCIAL MEDIA LINKS -->
                <?php if ($socialFacebook || $socialInstagram || $socialYoutube || $socialTwitter): ?>
                <div class="contact-section__social fade-in">
                    <h4 class="contact-section__social-title">Follow Us</h4>
                    <div class="contact-section__social-links">
                        <?php if ($socialFacebook): ?>
                        <a href="<?= e($socialFacebook) ?>" target="_blank" rel="noopener noreferrer" class="contact-section__social-link" aria-label="Facebook">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            <span>Facebook</span>
                        </a>
                        <?php endif; ?>
                        <?php if ($socialInstagram): ?>
                        <a href="<?= e($socialInstagram) ?>" target="_blank" rel="noopener noreferrer" class="contact-section__social-link" aria-label="Instagram">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                            <span>Instagram</span>
                        </a>
                        <?php endif; ?>
                        <?php if ($socialYoutube): ?>
                        <a href="<?= e($socialYoutube) ?>" target="_blank" rel="noopener noreferrer" class="contact-section__social-link" aria-label="YouTube">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.13C5.12 19.56 12 19.56 12 19.56s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#fff"/></svg>
                            <span>YouTube</span>
                        </a>
                        <?php endif; ?>
                        <?php if ($socialTwitter): ?>
                        <a href="<?= e($socialTwitter) ?>" target="_blank" rel="noopener noreferrer" class="contact-section__social-link" aria-label="X (Twitter)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            <span>X (Twitter)</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>

<style>
/* Contact Section Grid */
.contact-section__grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 2.5rem;
    align-items: start;
}
@media (max-width: 1024px) {
    .contact-section__grid {
        grid-template-columns: 1fr;
    }
}

/* Form Card */
.contact-section__form-card {
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 2rem 2.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
}
.contact-section__form-desc {
    margin-top: 0.5rem;
    font-size: 0.925rem;
    color: var(--color-text-light, #6b7280);
    line-height: 1.6;
}
.contact-section__form-col .section-title {
    margin-bottom: 0.25rem;
}

/* Form Styles */
.contact-form .form-group {
    margin-bottom: 1.25rem;
}
.contact-form .form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
    margin-bottom: 0.4rem;
}
.contact-form .form-input,
.contact-form .form-textarea {
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
    font-family: inherit;
    color: var(--color-dark, #111827);
    background: var(--color-bg, #f9fafb);
    border: 1.5px solid var(--color-border, #e5e7eb);
    border-radius: 12px;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.contact-form .form-input:focus,
.contact-form .form-textarea:focus {
    border-color: var(--color-primary, #dc2626);
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
}
.contact-form .form-input::placeholder,
.contact-form .form-textarea::placeholder {
    color: var(--color-text-light, #9ca3af);
}
.contact-form .form-textarea {
    resize: vertical;
    min-height: 120px;
}
.contact-form .form-submit {
    width: 100%;
    margin-top: 0.5rem;
}

/* Info Cards */
.contact-section__info-col {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}
.contact-section__info-card {
    background: var(--color-white, #fff);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    transition: transform 0.2s ease;
}
.contact-section__info-card:hover {
    transform: translateY(-2px);
}
.contact-section__info-icon {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: var(--color-primary-light, #fef2f2);
    color: var(--color-primary, #dc2626);
    display: flex;
    align-items: center;
    justify-content: center;
}
.contact-section__info-title {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-text-light, #6b7280);
    margin: 0 0 0.25rem;
}
.contact-section__info-text {
    font-size: 0.925rem;
    color: var(--color-dark, #111827);
    margin: 0;
    line-height: 1.5;
}
.contact-section__info-link {
    color: var(--color-primary, #dc2626);
    text-decoration: none;
    font-weight: 600;
}
.contact-section__info-link:hover {
    text-decoration: underline;
}

/* Emergency Card */
.contact-section__emergency-card {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    border-radius: 16px;
    padding: 1.5rem;
    color: #fff;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}
.contact-section__emergency-icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    animation: emergency-pulse 2s ease-in-out infinite;
}
@keyframes emergency-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.3); }
    50% { box-shadow: 0 0 0 8px rgba(255,255,255,0); }
}
.contact-section__emergency-body {
    flex: 1;
}
.contact-section__emergency-title {
    font-size: 1rem;
    font-weight: 700;
    margin: 0 0 0.35rem;
}
.contact-section__emergency-desc {
    font-size: 0.85rem;
    opacity: 0.9;
    margin: 0 0 0.75rem;
    line-height: 1.5;
}
.contact-section__emergency-phone {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
    font-weight: 700;
    color: #fff;
    text-decoration: none;
    background: rgba(255,255,255,0.15);
    padding: 0.5rem 1.25rem;
    border-radius: 50px;
    transition: background 0.2s ease;
}
.contact-section__emergency-phone:hover {
    background: rgba(255,255,255,0.25);
}

/* Map Placeholder */
.contact-section__map {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
}
.contact-section__map-placeholder {
    width: 100%;
    height: 200px;
    background: var(--color-bg, #f3f4f6);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: var(--color-text-light, #9ca3af);
    font-size: 0.9rem;
    font-weight: 600;
}

/* Social Links */
.contact-section__social {
    background: var(--color-white, #fff);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
}
.contact-section__social-title {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-text-light, #6b7280);
    margin: 0 0 0.75rem;
}
.contact-section__social-links {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}
.contact-section__social-link {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--color-dark, #111827);
    background: var(--color-bg, #f9fafb);
    border: 1.5px solid var(--color-border, #e5e7eb);
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.contact-section__social-link:hover {
    border-color: var(--color-primary, #dc2626);
    color: var(--color-primary, #dc2626);
    background: var(--color-primary-light, #fef2f2);
}
</style>
