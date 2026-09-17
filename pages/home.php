<?php
/**
 * NCB Website - Home Page
 */
$featuredEvent = get_events(1, true);
$featuredEvent = $featuredEvent ? $featuredEvent[0] : null;
$upcomingEvents = get_events(3, false);
$featuredNews = get_news(1, true);
$featuredNews = $featuredNews ? $featuredNews[0] : null;
$sideNews = get_news(3, false);
$galleryItems = get_gallery();
$galleryPreview = array_slice($galleryItems, 0, 8);
$committeeMembers = get_committee();
$committeePreview = array_slice($committeeMembers, 0, 4);
?>

<!-- HERO SECTION -->
<section class="hero" id="heroSection">
    <div class="container">
        <div class="hero__content fade-in">
            <div class="hero__text">
                <span class="hero__badge">NCB · EST. 2019</span>
                <h1 class="hero__title">A home away<br>from <span>home.</span></h1>
                <p class="hero__description">Together we support, celebrate, and grow a home for Nepalese across Busan.</p>
                <div class="hero__buttons">
                    <a href="<?= url('membership') ?>" class="btn btn--red btn--lg">Become a Member &rarr;</a>
                    <a href="<?= url('about') ?>" class="btn btn--outline btn--lg">Explore NCB &rarr;</a>
                </div>
            </div>

            <div class="hero__meta">
                <div class="hero__stat">
                    <div class="hero__stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div class="hero__stat-content">
                        <strong>500+</strong>
                        <span>Members</span>
                    </div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div class="hero__stat-content">
                        <strong>20+</strong>
                        <span>Events</span>
                    </div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <div class="hero__stat-content">
                        <strong>Busan, Korea</strong>
                        <span>Our Home</span>
                    </div>
                </div>
                <div class="hero__stat">
                    <div class="hero__stat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.39 6.61L21 12l-6.61 3.39L12 22l-2.39-6.61L3 12l6.61-3.39L12 2z"/></svg>
                    </div>
                    <div class="hero__stat-content">
                        <strong>Nepalese Community</strong>
                        <span>Stronger Together</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a href="#aboutPreview" class="hero__scroll" aria-label="Scroll to explore">
        <span class="hero__scroll-icon">↓</span>
        <span>Scroll to explore</span>
    </a>
</section>

<!-- ABOUT PREVIEW SECTION -->
<section class="about-preview" id="aboutPreview">
    <div class="container">
        <div class="about-preview__grid">
            <div class="about-preview__content fade-in">
                <span class="section-badge">WHO WE ARE</span>
                <h2 class="section-title"><?= e(get_setting('about_title')) ?></h2>
                <p class="about-preview__text"><?= e(get_setting('about_description')) ?></p>
                <div class="about-preview__pillars">
                    <div class="about-preview__pillar">
                        <div class="about-preview__pillar-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        </div>
                        <div>
                            <h4 class="about-preview__pillar-title">Our Mission</h4>
                            <p class="about-preview__pillar-text"><?= e(get_setting('about_mission')) ?></p>
                        </div>
                    </div>
                    <div class="about-preview__pillar">
                        <div class="about-preview__pillar-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </div>
                        <div>
                            <h4 class="about-preview__pillar-title">Our Vision</h4>
                            <p class="about-preview__pillar-text"><?= e(get_setting('about_vision')) ?></p>
                        </div>
                    </div>
                </div>
                <a href="<?= url('about') ?>" class="btn btn--outline">Read Full Story &rarr;</a>
            </div>
            <div class="about-preview__visual fade-in">
                <div class="about-preview__image">
                    <img src="assets/images/demo.jpg" alt="NCB Community">
                </div>
                <div class="about-preview__objectives">
                    <h4 class="about-preview__objectives-title">Our Objectives</h4>
                    <p class="about-preview__objectives-text"><?= e(get_setting('about_objectives')) ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICES SECTION -->
<section class="services" id="services">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">WHAT WE DO</span>
            <h2 class="section-title">Community Services</h2>
            <p class="section-subtitle">NCB provides essential services to help Nepalese residents thrive in Busan.</p>
        </div>
        <div class="services__grid">
            <div class="services__card fade-in">
                <div class="services__card-icon services__card-icon--support">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                </div>
                <h3 class="services__card-title">Community Support</h3>
                <ul class="services__card-list">
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Emergency assistance & crisis response</li>
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> New arrival orientation & guidance</li>
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Legal aid & visa consultation referrals</li>
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Health & medical emergency support</li>
                </ul>
                <a href="<?= url('services') ?>" class="services__card-link">Learn more &rarr;</a>
            </div>
            <div class="services__card fade-in">
                <div class="services__card-icon services__card-icon--culture">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
                <h3 class="services__card-title">Cultural Promotion</h3>
                <ul class="services__card-list">
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Festival celebrations (Dashain, Tihar, etc.)</li>
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Cultural dance & music programs</li>
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Nepali language classes for children</li>
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Cultural exchange with Korean community</li>
                </ul>
                <a href="<?= url('services') ?>" class="services__card-link">Learn more &rarr;</a>
            </div>
            <div class="services__card fade-in">
                <div class="services__card-icon services__card-icon--info">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                </div>
                <h3 class="services__card-title">Information Sharing</h3>
                <ul class="services__card-list">
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Job opportunities & employment updates</li>
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Scholarship & education information</li>
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Korean language workshop resources</li>
                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Immigration policy updates & guidance</li>
                </ul>
                <a href="<?= url('services') ?>" class="services__card-link">Learn more &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- IMPACT STATISTICS SECTION -->
<section class="stats" id="statsSection">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">OUR IMPACT</span>
            <h2 class="section-title">Numbers That Matter</h2>
        </div>
        <div class="stats__grid">
            <div class="stats__item fade-in">
                <div class="stats__number" data-target="<?= e(get_setting('stat_members', '450')) ?>">0</div>
                <div class="stats__label">Community Members</div>
            </div>
            <div class="stats__item fade-in">
                <div class="stats__number" data-target="<?= e(get_setting('stat_events', '120')) ?>">0</div>
                <div class="stats__label">Events Organized</div>
            </div>
            <div class="stats__item fade-in">
                <div class="stats__number" data-target="<?= e(get_setting('stat_volunteers', '60')) ?>">0</div>
                <div class="stats__label">Active Volunteers</div>
            </div>
            <div class="stats__item fade-in">
                <div class="stats__number" data-target="<?= e(get_setting('stat_years', '12')) ?>">0</div>
                <div class="stats__label">Years of Service</div>
            </div>
        </div>
    </div>
</section>

<!-- UPCOMING EVENTS SECTION -->
<section class="upcoming-events" id="upcomingEvents">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">WHAT'S NEXT</span>
            <h2 class="section-title">Upcoming Events</h2>
            <p class="section-subtitle">Join us at our upcoming events and be part of the community.</p>
        </div>
        <?php if (empty($upcomingEvents)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <p>No upcoming events at the moment. Check back soon!</p>
        </div>
        <?php else: ?>
        <div class="events__grid">
            <?php foreach ($upcomingEvents as $event): ?>
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
        <div class="section-footer fade-in">
            <a href="<?= url('events') ?>" class="btn btn--outline">View All Events &rarr;</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- LATEST NEWS SECTION -->
<section class="latest-news" id="latestNews">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">STAY INFORMED</span>
            <h2 class="section-title">Latest News & Updates</h2>
        </div>
        <?php if (!$featuredNews && empty($sideNews)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <p>No news articles at the moment.</p>
        </div>
        <?php else: ?>
        <div class="latest-news__grid">
            <?php if ($featuredNews): ?>
            <div class="latest-news__featured fade-in">
                <div class="latest-news__featured-image">
                    <img src="<?= e($featuredNews['image']) ?>" alt="<?= e($featuredNews['title']) ?>">
                </div>
                <div class="latest-news__featured-body">
                    <?php $tags = get_tags($featuredNews['tags']); ?>
                    <?php foreach ($tags as $tag): ?>
                    <span class="tag"><?= e($tag) ?></span>
                    <?php endforeach; ?>
                    <h3 class="latest-news__featured-title"><?= e($featuredNews['title']) ?></h3>
                    <p class="latest-news__featured-excerpt"><?= e($featuredNews['excerpt'] ?? mb_substr(strip_tags($featuredNews['content']), 0, 160) . '...') ?></p>
                    <div class="latest-news__featured-meta">
                        <span><?= e($featuredNews['author']) ?></span>
                        <span>&middot;</span>
                        <span><?= e(format_date($featuredNews['published_at'], 'M d, Y')) ?></span>
                    </div>
                    <a href="<?= url('news-detail', ['slug' => $featuredNews['slug']]) ?>" class="btn btn--red btn--sm">Read Full Article &rarr;</a>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($sideNews)): ?>
            <div class="latest-news__sidebar">
                <?php foreach ($sideNews as $article): ?>
                <a href="<?= url('news-detail', ['slug' => $article['slug']]) ?>" class="latest-news__side-item fade-in">
                    <div class="latest-news__side-image">
                        <img src="<?= e($article['image']) ?>" alt="<?= e($article['title']) ?>">
                    </div>
                    <div class="latest-news__side-body">
                        <span class="latest-news__side-date"><?= e(format_date($article['published_at'], 'M d, Y')) ?></span>
                        <h4 class="latest-news__side-title"><?= e($article['title']) ?></h4>
                        <p class="latest-news__side-excerpt"><?= e(mb_substr(strip_tags($article['excerpt'] ?? $article['content']), 0, 100)) ?>...</p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <div class="section-footer fade-in">
            <a href="<?= url('news') ?>" class="btn btn--outline">View All News &rarr;</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- GALLERY PREVIEW SECTION -->
<section class="gallery-preview" id="galleryPreview">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">OUR MOMENTS</span>
            <h2 class="section-title">Photo Gallery</h2>
            <p class="section-subtitle">A glimpse into our community events and celebrations.</p>
        </div>
        <?php if (empty($galleryPreview)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <p>No gallery photos yet. Stay tuned!</p>
        </div>
        <?php else: ?>
        <div class="gallery-preview__grid">
            <?php foreach ($galleryPreview as $i => $item): ?>
            <div class="gallery-preview__item gallery-preview__item--<?= e($item['size']) ?> fade-in">
                <img src="<?= e($item['image']) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
                <div class="gallery-preview__overlay">
                    <span class="gallery-preview__category"><?= e($item['category']) ?></span>
                    <span class="gallery-preview__title"><?= e($item['title']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="section-footer fade-in">
            <a href="<?= url('gallery') ?>" class="btn btn--outline">View Full Gallery &rarr;</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- COMMITTEE PREVIEW SECTION -->
<section class="committee-preview" id="committeePreview">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">OUR LEADERSHIP</span>
            <h2 class="section-title">Executive Committee</h2>
            <p class="section-subtitle">Meet the dedicated volunteers leading our community forward.</p>
        </div>
        <?php if (empty($committeePreview)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <p>Committee information coming soon.</p>
        </div>
        <?php else: ?>
        <div class="committee-preview__grid">
            <?php foreach ($committeePreview as $member): ?>
            <div class="committee-card fade-in">
                <div class="committee-card__image">
                    <img src="<?= e($member['image']) ?>" alt="<?= e($member['name']) ?>">
                </div>
                <div class="committee-card__body">
                    <h3 class="committee-card__name"><?= e($member['name']) ?></h3>
                    <span class="committee-card__role"><?= e($member['role']) ?></span>
                    <p class="committee-card__bio"><?= e(mb_substr(strip_tags($member['bio'] ?? ''), 0, 120)) ?>...</p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="section-footer fade-in">
            <a href="<?= url('committee') ?>" class="btn btn--outline">View Full Committee &rarr;</a>
        </div>
        <?php endif; ?>
    </div>
</section>
