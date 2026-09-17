<?php
/**
 * NCB Website - Volunteers Page
 */

$flash = get_flash();
$volunteers = get_volunteers(true);
$upcomingEvents = get_events(10, false);
$currentUser = current_pub_user();
$loggedIn = is_pub_logged_in();
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">VOLUNTEERS</span>
            <h1 class="page-hero__title">Our Volunteers</h1>
            <p class="page-hero__subtitle">Meet the community heroes who selflessly dedicate their time and energy to make NCB stronger, one act of service at a time.</p>
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

<!-- ACTIVE VOLUNTEERS SECTION -->
<section class="volunteers-section" id="volunteersGrid">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">OUR HEROES</span>
            <h2 class="section-title">Active Volunteers</h2>
            <p class="section-subtitle">These dedicated individuals go above and beyond for our community every day.</p>
        </div>

        <?php if (empty($volunteers)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <p>No active volunteers at the moment. Be the first to step up!</p>
        </div>
        <?php else: ?>
        <div class="volunteers__grid">
            <?php foreach ($volunteers as $v): ?>
            <div class="volunteer-card fade-in">
                <div class="volunteer-card__photo">
                    <?php if (!empty($v['photo'])): ?>
                        <img src="<?= e($v['photo']) ?>" alt="<?= e($v['name']) ?>">
                    <?php else: ?>
                        <div class="volunteer-card__photo-placeholder">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="volunteer-card__body">
                    <h3 class="volunteer-card__name"><?= e($v['name']) ?></h3>
                    <p class="volunteer-card__role">Community Volunteer</p>
                    <?php if (!empty($v['bio'])): ?>
                    <p class="volunteer-card__bio"><?= e(mb_substr(strip_tags($v['bio']), 0, 140)) ?><?php if (mb_strlen(strip_tags($v['bio'])) > 140): ?>...<?php endif; ?></p>
                    <?php elseif (!empty($v['message'])): ?>
                    <p class="volunteer-card__bio"><?= e(mb_substr(strip_tags($v['message']), 0, 140)) ?><?php if (mb_strlen(strip_tags($v['message'])) > 140): ?>...<?php endif; ?></p>
                    <?php endif; ?>
                    <div class="volunteer-card__stats">
                        <div class="volunteer-card__stat">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            <span><?= e($v['work_count']) ?> volunteer works</span>
                        </div>
                        <div class="volunteer-card__stat">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span><?= e($v['total_hours']) ?> hours</span>
                        </div>
                    </div>
                    <?php if (!empty($v['joined_date'])): ?>
                    <p class="volunteer-card__joined">Joined <?= e(format_date($v['joined_date'], 'M Y')) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- REGISTER AS VOLUNTEER -->
<section class="volunteer-register" id="volunteerRegister">
    <div class="container">
        <div class="volunteer-register__card fade-in">
            <div class="volunteer-register__header">
                <span class="section-badge">SIGN UP</span>
                <h2 class="section-title">Register as Volunteer</h2>
                <p class="volunteer-register__desc">Want to make a difference? Fill out the form below to join our volunteer team and help build a stronger community.</p>
                <?php if ($loggedIn): ?>
                <p class="volunteer-register__welcome">Welcome, <strong><?= e($currentUser['name']) ?></strong>! We're glad to have you.</p>
                <?php endif; ?>
            </div>
            <form method="POST" action="actions/volunteer-register.php" class="volunteer-register__form">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="vol_name">Full Name *</label>
                        <input type="text" id="vol_name" name="full_name" class="form-input" placeholder="Your full name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="vol_email">Email Address *</label>
                        <input type="email" id="vol_email" name="email" class="form-input" placeholder="your.email@example.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="vol_phone">Phone Number *</label>
                        <input type="tel" id="vol_phone" name="phone" class="form-input" placeholder="+82-10-XXXX-XXXX" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="vol_bio">Short Bio / Message</label>
                        <textarea id="vol_bio" name="message" class="form-textarea" rows="4" placeholder="Tell us about yourself and why you want to volunteer..."></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="form-submit btn btn--red btn--lg">Register as Volunteer &rarr;</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- VOLUNTEER FOR UPCOMING EVENTS -->
<section class="volunteer-events" id="volunteerEvents">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">UPCOMING</span>
            <h2 class="section-title">Volunteer for Upcoming Events</h2>
            <p class="section-subtitle">Choose an event below and sign up to volunteer. Your help makes every event a success.</p>
        </div>

        <?php if (empty($upcomingEvents)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <p>No upcoming events available for volunteering at this time.</p>
        </div>
        <?php else: ?>
        <div class="volunteer-events__grid">
            <?php foreach ($upcomingEvents as $evt): ?>
            <div class="volunteer-events__card fade-in">
                <div class="volunteer-events__card-date">
                    <span class="volunteer-events__card-month"><?= e(format_date($evt['date'], 'M')) ?></span>
                    <span class="volunteer-events__card-day"><?= e(format_date($evt['date'], 'd')) ?></span>
                </div>
                <div class="volunteer-events__card-info">
                    <span class="volunteer-events__card-category"><?= e($evt['category']) ?></span>
                    <h4 class="volunteer-events__card-title"><?= e($evt['title']) ?></h4>
                    <div class="volunteer-events__card-meta">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <?= e($evt['time']) ?>
                    </div>
                    <div class="volunteer-events__card-meta">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?= e($evt['location']) ?>
                    </div>
                </div>
                <div class="volunteer-events__card-action">
                    <?php if ($loggedIn): ?>
                    <a href="actions/volunteer-event.php?event_id=<?= e($evt['id']) ?>" class="btn btn--red btn--sm">Volunteer</a>
                    <?php else: ?>
                    <a href="<?= url('login') ?>" class="btn btn--outline btn--sm">Login to Volunteer</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Volunteers Grid */
.volunteers__grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.75rem;
}

/* Volunteer Card */
.volunteer-card {
    background: var(--color-white, #fff);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.volunteer-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08), 0 12px 28px rgba(0,0,0,0.06);
}
.volunteer-card__photo {
    width: 100%;
    height: 240px;
    overflow: hidden;
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
}
.volunteer-card__photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.volunteer-card__photo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #e53935;
    opacity: 0.5;
}
.volunteer-card__body {
    padding: 1.25rem 1.5rem 1.5rem;
}
.volunteer-card__name {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 0.15rem;
}
.volunteer-card__role {
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-primary, #e53935);
    margin: 0 0 0.75rem;
}
.volunteer-card__bio {
    font-size: 0.875rem;
    color: var(--color-text, #374151);
    line-height: 1.6;
    margin: 0 0 1rem;
}
.volunteer-card__stats {
    display: flex;
    gap: 1.25rem;
    padding-top: 0.85rem;
    border-top: 1.5px solid var(--color-border, #e5e7eb);
    margin-bottom: 0.65rem;
}
.volunteer-card__stat {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.825rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
}
.volunteer-card__stat svg {
    color: var(--color-primary, #e53935);
}
.volunteer-card__joined {
    font-size: 0.775rem;
    color: var(--color-text-light, #6b7280);
    margin: 0;
}

/* Volunteer Register Section */
.volunteer-register__card {
    max-width: 680px;
    margin: 0 auto;
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 2.25rem 2.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
}
.volunteer-register__desc {
    font-size: 0.925rem;
    color: var(--color-text-light, #6b7280);
    line-height: 1.6;
    margin: 0.5rem 0 1.5rem;
}
.volunteer-register__welcome {
    font-size: 0.9rem;
    color: var(--color-text, #374151);
    margin: 0 0 1.25rem;
    padding: 0.65rem 1rem;
    background: var(--color-bg, #f9fafb);
    border-radius: 10px;
    border-left: 3px solid var(--color-primary, #e53935);
}
.volunteer-register__form .form-group {
    margin-bottom: 1.15rem;
}
.volunteer-register__form .form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
    margin-bottom: 0.4rem;
}
.volunteer-register__form .form-input,
.volunteer-register__form .form-textarea {
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
.volunteer-register__form .form-input:focus,
.volunteer-register__form .form-textarea:focus {
    border-color: var(--color-primary, #e53935);
    box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.08);
}
.volunteer-register__form .form-input::placeholder,
.volunteer-register__form .form-textarea::placeholder {
    color: var(--color-text-light, #9ca3af);
}
.volunteer-register__form .form-textarea {
    resize: vertical;
    min-height: 100px;
}
.volunteer-register__form .form-submit {
    width: 100%;
    margin-top: 0.5rem;
}

/* Volunteer Events Grid */
.volunteer-events__grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-width: 780px;
    margin: 0 auto;
}
.volunteer-events__card {
    background: var(--color-white, #fff);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    gap: 1.25rem;
    transition: transform 0.2s ease;
}
.volunteer-events__card:hover {
    transform: translateY(-2px);
}
.volunteer-events__card-date {
    flex-shrink: 0;
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.volunteer-events__card-month {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    opacity: 0.85;
}
.volunteer-events__card-day {
    font-size: 1.3rem;
    font-weight: 800;
    line-height: 1.2;
}
.volunteer-events__card-info {
    flex: 1;
    min-width: 0;
}
.volunteer-events__card-category {
    display: inline-block;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--color-primary, #e53935);
    background: var(--color-primary-light, #fef2f2);
    padding: 0.15rem 0.55rem;
    border-radius: 50px;
    margin-bottom: 0.3rem;
}
.volunteer-events__card-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 0.35rem;
}
.volunteer-events__card-meta {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    color: var(--color-text-light, #6b7280);
    margin-bottom: 0.15rem;
}
.volunteer-events__card-meta svg {
    flex-shrink: 0;
    color: var(--color-text-light, #9ca3af);
}
.volunteer-events__card-action {
    flex-shrink: 0;
}

@media (max-width: 640px) {
    .volunteers__grid {
        grid-template-columns: 1fr;
    }
    .volunteer-register__card {
        padding: 1.5rem;
    }
    .volunteer-events__card {
        flex-direction: column;
        align-items: flex-start;
    }
    .volunteer-events__card-action {
        width: 100%;
    }
    .volunteer-events__card-action .btn {
        width: 100%;
        text-align: center;
    }
}
</style>
