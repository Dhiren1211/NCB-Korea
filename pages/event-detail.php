<?php
/**
 * NCB Website - Event Detail Page
 */

// Retrieve event — try slug first (from GET), then id, then GLOBALS
$event = null;
if (isset($_GET['slug'])) {
    $event = get_event_by_slug($_GET['slug']);
} elseif (isset($_GET['id'])) {
    $event = get_event_by_id((int)$_GET['id']);
} elseif (isset($GLOBALS['event'])) {
    $event = $GLOBALS['event'];
}

// Fetch related data
$highlights    = $event ? get_tags($event['highlights']) : [];
$regCount      = $event ? get_event_registration_count($event['id']) : 0;
$registeredEmail = $_SESSION['registered_email'] ?? '';
$alreadyRegistered = ($event && $registeredEmail) ? is_user_registered($event['id'], $registeredEmail) : false;

// Related events — other upcoming events, exclude current
$relatedEvents = [];
if ($event) {
    $relatedEvents = array_filter(get_events(), function($e) use ($event) {
        return (int)$e['id'] !== (int)$event['id'];
    });
    $relatedEvents = array_values($relatedEvents);
    $relatedEvents = array_slice($relatedEvents, 0, 3);
}

// Flash message
$flash = get_flash();
?>

<!-- ERROR STATE -->
<?php if (!$event): ?>
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">EVENT NOT FOUND</span>
            <h1 class="page-hero__title">Event Not Found</h1>
            <p class="page-hero__subtitle">The event you are looking for does not exist or may have been removed.</p>
            <a href="<?= url('events') ?>" class="btn btn--red btn--lg" style="margin-top:1.5rem;">Browse All Events &rarr;</a>
        </div>
    </div>
</section>
<?php return; endif; ?>

<!-- FLASH MESSAGE -->
<?php if ($flash): ?>
<div class="container" style="margin-top:2rem;">
    <div class="alert alert--<?= e($flash['type']) ?> fade-in"><?= e($flash['message']) ?></div>
</div>
<?php endif; ?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge"><?= e($event['category']) ?></span>
            <h1 class="page-hero__title"><?= e($event['title']) ?></h1>
            <p class="page-hero__subtitle"><?= e(format_date($event['date'], 'F d, Y')) ?> &middot; <?= e($event['time']) ?></p>
        </div>
    </div>
</section>

<!-- EVENT IMAGE + DATE BADGE -->
<section class="event-detail" id="eventDetail">
    <div class="container">
        <div class="event-detail__image fade-in">
            <img src="<?= e($event['image']) ?>" alt="<?= e($event['title']) ?>">
            <div class="event-detail__date-badge">
                <span class="event-detail__date-month"><?= e(format_date($event['date'], 'M')) ?></span>
                <span class="event-detail__date-day"><?= e(format_date($event['date'], 'd')) ?></span>
                <span class="event-detail__date-year"><?= e(format_date($event['date'], 'Y')) ?></span>
            </div>
        </div>
    </div>
</section>

<!-- EVENT DETAILS GRID -->
<section class="event-detail__info" id="eventInfo">
    <div class="container">
        <div class="event-detail__grid fade-in">
            <!-- Description -->
            <div class="event-detail__description">
                <span class="section-label">ABOUT THIS EVENT</span>
                <h2 class="section-title">Event Overview</h2>
                <p><?= nl2br(e($event['description'])) ?></p>

                <?php if (!empty($highlights)): ?>
                <div style="margin-top:1.5rem;">
                    <h4 style="margin-bottom:0.75rem;color:var(--color-dark);">Event Highlights</h4>
                    <ul class="highlight-list">
                        <?php foreach ($highlights as $h): ?>
                        <li class="highlight-list__item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <?= e($h) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar: Details + Registration -->
            <div class="event-detail__sidebar">
                <!-- Details Card -->
                <div class="event-detail__card">
                    <h3 class="event-detail__card-title">Event Details</h3>
                    <div class="event-detail__detail-grid">
                        <div class="event-detail__detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            <div>
                                <strong>Date</strong>
                                <span><?= e(format_date($event['date'], 'F d, Y')) ?></span>
                            </div>
                        </div>
                        <div class="event-detail__detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <div>
                                <strong>Time</strong>
                                <span><?= e($event['time']) ?></span>
                            </div>
                        </div>
                        <div class="event-detail__detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div>
                                <strong>Location</strong>
                                <span><?= e($event['location']) ?></span>
                            </div>
                        </div>
                        <?php if (!empty($event['organizer'])): ?>
                        <div class="event-detail__detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <div>
                                <strong>Organizer</strong>
                                <span><?= e($event['organizer']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="event-detail__detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            <div>
                                <strong>Fee</strong>
                                <span><?= e($event['fee']) ?></span>
                            </div>
                        </div>
                        <?php if (!empty($event['capacity'])): ?>
                        <div class="event-detail__detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <div>
                                <strong>Capacity</strong>
                                <span><?= e($regCount) ?> / <?= e($event['capacity']) ?> registered</span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($event['contact_person'])): ?>
                        <div class="event-detail__detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <div>
                                <strong>Contact Person</strong>
                                <span><?= e($event['contact_person']) ?></span>
                                <?php if (!empty($event['contact_phone'])): ?>
                                <span><?= e($event['contact_phone']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($event['registration_deadline'])): ?>
                        <div class="event-detail__detail-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12"/><line x1="12" y1="16" x2="12" y2="16"/></svg>
                            <div>
                                <strong>Registration Deadline</strong>
                                <span><?= e(format_date($event['registration_deadline'], 'F d, Y')) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Registration Section -->
                <div class="event-detail__card">
                    <h3 class="event-detail__card-title">Register for This Event</h3>

                    <?php if ($alreadyRegistered): ?>
                    <div class="event-detail__registered" style="background:var(--color-green-light, #e6f9ee);padding:1.25rem;border-radius:12px;text-align:center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-green, #16a34a)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <p style="margin-top:0.5rem;font-weight:600;color:var(--color-green, #16a34a);">You are already registered for this event!</p>
                        <p style="margin-top:0.25rem;font-size:0.875rem;color:var(--color-text-light, #6b7280);">We look forward to seeing you there.</p>
                    </div>
                    <?php elseif ($event['capacity'] > 0 && $regCount >= $event['capacity']): ?>
                    <div class="event-detail__full" style="background:var(--color-red-light, #fef2f2);padding:1.25rem;border-radius:12px;text-align:center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-red, #dc2626)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <p style="margin-top:0.5rem;font-weight:600;color:var(--color-red, #dc2626);">Registration is Full</p>
                        <p style="margin-top:0.25rem;font-size:0.875rem;color:var(--color-text-light, #6b7280);">This event has reached its capacity of <?= e($event['capacity']) ?> participants.</p>
                    </div>
                    <?php else: ?>
                    <form method="POST" action="actions/event-register.php" class="event-detail__form">
                        <input type="hidden" name="event_id" value="<?= e($event['id']) ?>">
                        <input type="hidden" name="redirect" value="<?= e(url('event-detail', ['slug' => $event['slug']])) ?>">

                        <div class="form-group">
                            <label class="form-label" for="full_name">Full Name <span style="color:var(--color-red);">*</span></label>
                            <input type="text" id="full_name" name="full_name" class="form-input" placeholder="Enter your full name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email Address <span style="color:var(--color-red);">*</span></label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number <span style="color:var(--color-red);">*</span></label>
                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+82-10-XXXX-XXXX" required>
                        </div>

                        <div class="form-group">
                            <label class="form-checkbox">
                                <input type="checkbox" name="is_ncb_member" value="1">
                                <span>I am an NCB member</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="additional_notes">Additional Notes</label>
                            <textarea id="additional_notes" name="additional_notes" class="form-textarea" rows="3" placeholder="Any dietary requirements, special needs, or questions..."></textarea>
                        </div>

                        <button type="submit" class="form-submit btn btn--blue btn--lg" style="width:100%;">Register Now &rarr;</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- RELATED EVENTS -->
<?php if (!empty($relatedEvents)): ?>
<section class="related-events" id="relatedEvents">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-label">MORE EVENTS</span>
            <h2 class="section-title">Related Events</h2>
            <p class="section-subtitle">Check out other upcoming events from our community.</p>
        </div>
        <div class="events__grid">
            <?php foreach ($relatedEvents as $re): ?>
            <div class="event-card fade-in">
                <div class="event-card__image">
                    <img src="<?= e($re['image']) ?>" alt="<?= e($re['title']) ?>" loading="lazy">
                    <span class="event-card__category"><?= e($re['category']) ?></span>
                </div>
                <div class="event-card__body">
                    <div class="event-card__date">
                        <span class="event-card__date-month"><?= e(format_date($re['date'], 'M')) ?></span>
                        <span class="event-card__date-day"><?= e(format_date($re['date'], 'd')) ?></span>
                    </div>
                    <div class="event-card__info">
                        <h3 class="event-card__title"><?= e($re['title']) ?></h3>
                        <div class="event-card__meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?= e($re['time']) ?>
                        </div>
                        <div class="event-card__meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?= e($re['location']) ?>
                        </div>
                        <div class="event-card__meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <?= e(get_event_registration_count($re['id'])) ?> registered
                        </div>
                    </div>
                    <a href="<?= url('event-detail', ['slug' => $re['slug']]) ?>" class="event-card__link btn btn--red btn--sm">Details &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
