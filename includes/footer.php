<?php
/**
 * NCB Website - Footer Component
 * Dynamically rendered from database settings.
 */
$settings = get_all_settings();

$quickLinks = [
    ['label' => 'Home', 'page' => 'home'],
    ['label' => 'About', 'page' => 'about'],
    ['label' => 'Committee', 'page' => 'committee'],
    ['label' => 'Events', 'page' => 'events'],
    ['label' => 'News', 'page' => 'news'],
    ['label' => 'Gallery', 'page' => 'gallery'],
];

$communityLinks = [
    ['label' => 'Volunteers', 'page' => 'volunteers'],
    ['label' => 'Community Board', 'page' => 'community'],
    ['label' => 'Forum', 'page' => 'complaints'],
    ['label' => 'Membership', 'page' => 'membership'],
    ['label' => 'Services', 'page' => 'services'],
];

$resourceLinks = [
    ['label' => 'Downloads', 'page' => 'downloads'],
    ['label' => 'Contact', 'page' => 'contact'],
    ['label' => 'Login', 'page' => 'login'],
    ['label' => 'Register', 'page' => 'register'],
];
?>

<!-- JOIN CTA SECTION -->
<section class="join" id="joinSection">
    <div class="container">
        <div class="join__content fade-in">
            <div class="join__badge"><?= e($settings['join_badge']) ?></div>
            <h2 class="join__title"><?= e($settings['join_title']) ?></h2>
            <p class="join__text"><?= e($settings['join_description']) ?></p>
            <div class="join__buttons">
                <a href="<?= url('membership') ?>" class="btn btn--red btn--lg">Become a Member &rarr;</a>
                <a href="<?= url('contact') ?>" class="btn btn--outline btn--lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="footer" id="footer">
    <div class="container">
        <div class="footer__grid">
            <div>
                <a href="<?= url('home') ?>" class="header__logo" style="margin-bottom:8px;">
                    <img src="assets/logo.jpg" alt="NCB Logo" class="header__logo-img" style="width:44px;height:44px;">
                    <div class="header__logo-text">
                        <span class="header__logo-name" style="color:#fff;"><?= e($settings['site_name']) ?></span>
                        <span class="header__logo-sub"><?= e($settings['site_short_name']) ?> &middot; EST. <?= e($settings['established']) ?></span>
                    </div>
                </a>
                <p class="footer__brand-desc"><?= e($settings['description']) ?></p>
                <div class="footer__social">
                    <?php if (!empty($settings['social_facebook'])): ?>
                    <a href="<?= e($settings['social_facebook']) ?>" class="footer__social-link" aria-label="Facebook" target="_blank">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_instagram'])): ?>
                    <a href="<?= e($settings['social_instagram']) ?>" class="footer__social-link" aria-label="Instagram" target="_blank">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_youtube'])): ?>
                    <a href="<?= e($settings['social_youtube']) ?>" class="footer__social-link" aria-label="YouTube" target="_blank">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19.13C5.12 19.56 12 19.56 12 19.56s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02" fill="#0d1b3e"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($settings['social_twitter'])): ?>
                    <a href="<?= e($settings['social_twitter']) ?>" class="footer__social-link" aria-label="Twitter/X" target="_blank">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <h4 class="footer__heading">Quick Links</h4>
                <ul class="footer__links">
                    <?php foreach ($quickLinks as $link): ?>
                    <li><a href="<?= url($link['page']) ?>" class="footer__link"><?= e($link['label']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h4 class="footer__heading">Community</h4>
                <ul class="footer__links">
                    <?php foreach ($communityLinks as $link): ?>
                    <li><a href="<?= url($link['page']) ?>" class="footer__link"><?= e($link['label']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h4 class="footer__heading">Resources</h4>
                <ul class="footer__links">
                    <?php foreach ($resourceLinks as $link): ?>
                    <li><a href="<?= url($link['page']) ?>" class="footer__link"><?= e($link['label']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h4 class="footer__heading">Contact Information</h4>
                <div class="footer__contact-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?= e($settings['contact_address']) ?>
                </div>
                <div class="footer__contact-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <?= e($settings['contact_phone']) ?>
                </div>
                <div class="footer__contact-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <?= e($settings['contact_email']) ?>
                </div>
                <div class="footer__contact-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?= e($settings['contact_hours']) ?>
                </div>
                <?php if (!empty($settings['emergency_phone'])): ?>
                <a href="tel:<?= e($settings['emergency_phone']) ?>" class="footer__emergency-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Emergency Support
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="footer__bottom">
            <span>&copy; <?= date('Y') ?> <?= e($settings['site_name']) ?>. All rights reserved.</span>
            <div class="footer__made-with">
                <span>Made with &hearts; by NCB Volunteers</span>
                <button class="footer__scroll-top" id="scrollTopBtn" aria-label="Scroll to top">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
                </button>
            </div>
        </div>
    </div>
</footer>

<script src="js/app.js"></script>
</body>
</html>
