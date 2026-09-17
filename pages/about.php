<?php
/**
 * NCB Website - About Page
 */
$committeeMembers = get_committee();
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">ABOUT NCB</span>
            <h1 class="page-hero__title"><?= e(get_setting('about_title')) ?></h1>
            <p class="page-hero__subtitle">Learn about our mission, history, and the team behind the Nepalese Community in Busan.</p>
        </div>
    </div>
</section>

<!-- ABOUT INTRO -->
<section class="about-intro" id="aboutIntro">
    <div class="container">
        <div class="about-intro__grid">
            <div class="about-intro__image fade-in">
                <img src="assets/images/demo.jpg" alt="NCB Community">
            </div>
            <div class="about-intro__content fade-in">
                <span class="section-badge">OUR STORY</span>
                <h2 class="section-title">Who We Are</h2>
                <p class="about-intro__text"><?= e(get_setting('about_description')) ?></p>
                <p class="about-intro__text"><?= e(get_setting('about_history')) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- MISSION VISION -->
<section class="mission-vision" id="missionVision">
    <div class="container">
        <div class="mission-vision__grid">
            <div class="mission-vision__card mission-vision__card--mission fade-in">
                <div class="mission-vision__icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                </div>
                <h3 class="mission-vision__card-title">Our Mission</h3>
                <p class="mission-vision__card-text"><?= e(get_setting('about_mission')) ?></p>
            </div>
            <div class="mission-vision__card mission-vision__card--vision fade-in">
                <div class="mission-vision__icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <h3 class="mission-vision__card-title">Our Vision</h3>
                <p class="mission-vision__card-text"><?= e(get_setting('about_vision')) ?></p>
            </div>
            <div class="mission-vision__card mission-vision__card--objectives fade-in">
                <div class="mission-vision__icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h3 class="mission-vision__card-title">Our Objectives</h3>
                <p class="mission-vision__card-text"><?= e(get_setting('about_objectives')) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- VALUES SECTION -->
<section class="values" id="valuesSection">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">WHAT DRIVES US</span>
            <h2 class="section-title">Our Core Values</h2>
        </div>
        <div class="values__grid">
            <div class="values__item fade-in">
                <div class="values__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h4 class="values__title">Unity</h4>
                <p class="values__text">Bringing all Nepalese in Busan together regardless of background, profession, or how long they have lived here.</p>
            </div>
            <div class="values__item fade-in">
                <div class="values__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <h4 class="values__title">Cultural Pride</h4>
                <p class="values__text">Preserving and celebrating our rich Nepali heritage while embracing the multicultural environment of Busan.</p>
            </div>
            <div class="values__item fade-in">
                <div class="values__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <h4 class="values__title">Compassion</h4>
                <p class="values__text">Supporting community members in times of need with empathy, urgency, and without judgment.</p>
            </div>
            <div class="values__item fade-in">
                <div class="values__icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h4 class="values__title">Integrity</h4>
                <p class="values__text">Operating with transparency, accountability, and the highest ethical standards in all our activities.</p>
            </div>
        </div>
    </div>
</section>

<!-- FULL COMMITTEE SECTION -->
<section class="committee-full" id="committeeFull">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">OUR TEAM</span>
            <h2 class="section-title">Executive Committee</h2>
            <p class="section-subtitle">The elected volunteers who dedicate their time and energy to serving our community.</p>
        </div>
        <?php if (empty($committeeMembers)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <p>Committee information coming soon.</p>
        </div>
        <?php else: ?>
        <div class="committee-full__grid">
            <?php foreach ($committeeMembers as $member): ?>
            <div class="committee-card fade-in">
                <div class="committee-card__image">
                    <img src="<?= e($member['image']) ?>" alt="<?= e($member['name']) ?>">
                </div>
                <div class="committee-card__body">
                    <h3 class="committee-card__name"><?= e($member['name']) ?></h3>
                    <span class="committee-card__role"><?= e($member['role']) ?></span>
                    <?php if (!empty($member['since_year'])): ?>
                    <span class="committee-card__since">Since <?= e($member['since_year']) ?></span>
                    <?php endif; ?>
                    <p class="committee-card__bio"><?= e($member['bio']) ?></p>
                    <div class="committee-card__contact">
                        <?php if (!empty($member['email'])): ?>
                        <a href="mailto:<?= e($member['email']) ?>" class="committee-card__contact-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            Email
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($member['phone'])): ?>
                        <a href="tel:<?= e($member['phone']) ?>" class="committee-card__contact-link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            Call
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>