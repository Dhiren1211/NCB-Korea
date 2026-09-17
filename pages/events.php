<?php
/**
 * NCB Website - Events Page
 */

// Handle category filter
$activeCategory = isset($_GET['category']) ? strtoupper($_GET['category']) : '';

// Get all upcoming events (with optional category filter)
$allUpcoming = get_events(null, false);
if ($activeCategory && $activeCategory !== 'ALL') {
    $allUpcoming = array_filter($allUpcoming, function($e) use ($activeCategory) {
        return strtoupper($e['category']) === $activeCategory;
    });
    $allUpcoming = array_values($allUpcoming);
}

// Get past/completed events
$db = getDB();
$pastSql = "SELECT * FROM events WHERE status IN ('completed', 'ongoing') ORDER BY date DESC";
if ($activeCategory && $activeCategory !== 'ALL') {
    $pastSql .= " AND category = '" . getDB()->quote($activeCategory) . "'";
}
$pastEvents = $db->query($pastSql)->fetchAll();

// Featured event
$featuredEvent = get_events(1, true);
$featuredEvent = $featuredEvent ? $featuredEvent[0] : null;

$categories = ['ALL', 'CULTURAL', 'WORKSHOP', 'VOLUNTEER', 'SPORTS', 'SOCIAL', 'MEETING'];
$categoryLabels = [
    'ALL' => 'All Events',
    'CULTURAL' => 'Cultural',
    'WORKSHOP' => 'Workshop',
    'VOLUNTEER' => 'Volunteer',
    'SPORTS' => 'Sports',
    'SOCIAL' => 'Social',
    'MEETING' => 'Meeting',
];
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">EVENTS</span>
            <h1 class="page-hero__title">Community Events</h1>
            <p class="page-hero__subtitle">Discover upcoming events, festivals, workshops, and activities organized by NCB.</p>
        </div>
    </div>
</section>

<!-- FEATURED EVENT -->
<?php if ($featuredEvent): ?>
<section class="featured-event" id="featuredEvent">
    <div class="container">
        <div class="featured-event__card fade-in">
            <div class="featured-event__image">
                <img src="<?= e($featuredEvent['image']) ?>" alt="<?= e($featuredEvent['title']) ?>">
                <div class="featured-event__date-badge">
                    <span class="featured-event__date-month"><?= e(format_date($featuredEvent['date'], 'M')) ?></span>
                    <span class="featured-event__date-day"><?= e(format_date($featuredEvent['date'], 'd')) ?></span>
                </div>
                <span class="featured-event__label">FEATURED EVENT</span>
            </div>
            <div class="featured-event__body">
                <span class="featured-event__category"><?= e($featuredEvent['category']) ?></span>
                <h2 class="featured-event__title"><?= e($featuredEvent['title']) ?></h2>
                <p class="featured-event__description"><?= e(mb_substr(strip_tags($featuredEvent['description']), 0, 250)) ?>...</p>
                <?php $highlights = get_tags($featuredEvent['highlights']); ?>
                <?php if (!empty($highlights)): ?>
                <ul class="featured-event__highlights">
                    <?php foreach (array_slice($highlights, 0, 4) as $h): ?>
                    <li>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        <?= e($h) ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <div class="featured-event__meta">
                    <div class="featured-event__meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <?= e(format_date($featuredEvent['date'], 'F d, Y')) ?> &middot; <?= e($featuredEvent['time']) ?>
                    </div>
                    <div class="featured-event__meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?= e($featuredEvent['location']) ?>
                    </div>
                    <div class="featured-event__meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <?= e(get_event_registration_count($featuredEvent['id'])) ?> registered
                    </div>
                    <div class="featured-event__meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        <?= e($featuredEvent['fee']) ?>
                    </div>
                </div>
                <a href="<?= url('event-detail', ['slug' => $featuredEvent['slug']]) ?>" class="btn btn--red btn--lg">View Details & Register &rarr;</a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- UPCOMING EVENTS -->
<section class="events-listing" id="upcomingEventsList">
    <div class="container">
        <div class="events-listing__header fade-in">
            <div>
                <span class="section-badge">UPCOMING</span>
                <h2 class="section-title">Upcoming Events</h2>
            </div>
            <!-- Category Filter -->
            <div class="events-listing__filters">
                <?php foreach ($categories as $cat): ?>
                <a href="<?= url('events', ['category' => $cat]) ?>" class="events-listing__filter-btn <?= $activeCategory === $cat ? 'events-listing__filter-btn--active' : '' ?>"><?= e($categoryLabels[$cat]) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($allUpcoming)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <p>No upcoming events found. Check back soon!</p>
        </div>
        <?php else: ?>
        <div class="events__grid">
            <?php foreach ($allUpcoming as $event): ?>
            <div class="event-card fade-in">
                <div class="event-card__image">
                    <img src="<?= e($event['image']) ?>" alt="<?= e($event['title']) ?>">
                    <span class="event-card__category"><?= e($event['category']) ?></span>
                </div>
                <div class="event-card__body">
                    <div class="event-card__date">
                        <span class="event-card__date-month"><?= e(format_date($event['date'], 'M')) ?></span>
                        <span class="event-card__date-day"><?= e(format_date($event['date'], 'd')) ?></span>
                    </div>
                    <div class="event-card__info">
                        <h3 class="event-card__title"><?= e($event['title']) ?></h3>
                        <div class="event-card__meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <?= e($event['time']) ?>
                        </div>
                        <div class="event-card__meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?= e($event['location']) ?>
                        </div>
                        <div class="event-card__meta">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <?= e(get_event_registration_count($event['id'])) ?> registered
                        </div>
                    </div>
                    <a href="<?= url('event-detail', ['slug' => $event['slug']]) ?>" class="event-card__link btn btn--red btn--sm">Details &rarr;</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- PAST EVENTS -->
<?php if (!empty($pastEvents)): ?>
<section class="past-events" id="pastEvents">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">ARCHIVE</span>
            <h2 class="section-title">Past Events</h2>
            <p class="section-subtitle">A look back at the events we've organized.</p>
        </div>
        <div class="past-events__grid">
            <?php foreach ($pastEvents as $event): ?>
            <div class="past-events__item fade-in">
                <div class="past-events__item-date">
                    <span class="past-events__item-month"><?= e(format_date($event['date'], 'M')) ?></span>
                    <span class="past-events__item-day"><?= e(format_date($event['date'], 'd')) ?></span>
                    <span class="past-events__item-year"><?= e(format_date($event['date'], 'Y')) ?></span>
                </div>
                <div class="past-events__item-info">
                    <span class="past-events__item-category"><?= e($event['category']) ?></span>
                    <h4 class="past-events__item-title"><?= e($event['title']) ?></h4>
                    <p class="past-events__item-location"><?= e($event['location']) ?></p>
                </div>
                <a href="<?= url('event-detail', ['slug' => $event['slug']]) ?>" class="past-events__item-link btn btn--outline btn--sm">View &rarr;</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>