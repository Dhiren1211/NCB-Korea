<?php
/**
 * NCB Website - Committee Page
 */

// Get committee members (already sorted by sort_order ASC)
$committeeMembers = get_committee();
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">OUR LEADERSHIP</span>
            <h1 class="page-hero__title">Executive Committee</h1>
            <p class="page-hero__subtitle">Meet the dedicated volunteers who lead and serve the Nepalese Community in Busan.</p>
        </div>
    </div>
</section>

<!-- COMMITTEE GRID -->
<section class="committee" id="committeeSection">
    <div class="container">
        <?php if (empty($committeeMembers)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <p>Committee information is coming soon. Please check back later.</p>
        </div>
        <?php else: ?>
        <div class="committee-grid">
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
                        <a href="mailto:<?= e($member['email']) ?>" class="committee-card__contact-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <span><?= e($member['email']) ?></span>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($member['phone'])): ?>
                        <a href="tel:<?= e($member['phone']) ?>" class="committee-card__contact-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span><?= e($member['phone']) ?></span>
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

<style>
/* Committee Grid */
.committee-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}
@media (max-width: 1024px) {
    .committee-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 480px) {
    .committee-grid {
        grid-template-columns: 1fr;
    }
}

/* Committee Card */
.committee-card {
    background: var(--color-white, #fff);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    text-align: center;
}
.committee-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}
.committee-card__image {
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    background: var(--color-bg, #f9fafb);
}
.committee-card__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.committee-card:hover .committee-card__image img {
    transform: scale(1.05);
}
.committee-card__body {
    padding: 1.25rem 1.5rem 1.5rem;
}
.committee-card__name {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0;
}
.committee-card__role {
    display: inline-block;
    margin-top: 0.5rem;
    padding: 0.25rem 0.85rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-primary, #dc2626);
    background: var(--color-primary-light, #fef2f2);
    border-radius: 50px;
}
.committee-card__since {
    display: block;
    margin-top: 0.35rem;
    font-size: 0.8rem;
    color: var(--color-text-light, #6b7280);
}
.committee-card__bio {
    margin: 0.75rem 0 1rem;
    font-size: 0.875rem;
    line-height: 1.6;
    color: var(--color-text, #374151);
}
.committee-card__contact {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    border-top: 1px solid var(--color-border, #e5e7eb);
    padding-top: 0.75rem;
}
.committee-card__contact-item {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    color: var(--color-text-light, #6b7280);
    text-decoration: none;
    transition: color 0.2s ease;
    justify-content: center;
    word-break: break-all;
}
.committee-card__contact-item:hover {
    color: var(--color-primary, #dc2626);
}
.committee-card__contact-item svg {
    flex-shrink: 0;
}
</style>
