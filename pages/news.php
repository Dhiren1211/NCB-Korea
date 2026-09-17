<?php
/**
 * NCB Website - News Page
 */

// Handle tag filter
$activeTag = isset($_GET['tag']) ? strtoupper($_GET['tag']) : '';

// Get featured news
$featuredNews = get_news(1, true);
$featuredNews = $featuredNews ? $featuredNews[0] : null;

// Get all news (excluding the featured one from the grid)
$allNews = get_news(null, false);

// Apply tag filter if set
if ($activeTag) {
    $allNews = array_filter($allNews, function($n) use ($activeTag) {
        $tags = get_tags($n['tags']);
        return in_array($activeTag, array_map('strtoupper', $tags));
    });
    $allNews = array_values($allNews);
}

// Collect all unique tags for filter buttons
$allTags = [];
foreach (get_news(null, false) as $n) {
    foreach (get_tags($n['tags']) as $t) {
        $allTags[strtoupper($t)] = $t;
    }
}
$allTags = array_unique($allTags);
sort($allTags);
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">NEWS & UPDATES</span>
            <h1 class="page-hero__title">Latest News</h1>
            <p class="page-hero__subtitle">Stay informed about NCB activities, partnerships, and community updates.</p>
        </div>
    </div>
</section>

<!-- FEATURED NEWS -->
<?php if ($featuredNews && !$activeTag): ?>
<section class="news-featured" id="newsFeatured">
    <div class="container">
        <div class="news-featured__card fade-in">
            <div class="news-featured__image">
                <img src="<?= e($featuredNews['image']) ?>" alt="<?= e($featuredNews['title']) ?>">
                <span class="news-featured__label">FEATURED</span>
            </div>
            <div class="news-featured__body">
                <?php $tags = get_tags($featuredNews['tags']); ?>
                <div class="news-featured__tags">
                    <?php foreach ($tags as $tag): ?>
                    <a href="<?= url('news', ['tag' => $tag]) ?>" class="tag"><?= e($tag) ?></a>
                    <?php endforeach; ?>
                </div>
                <h2 class="news-featured__title"><?= e($featuredNews['title']) ?></h2>
                <p class="news-featured__excerpt"><?= e($featuredNews['excerpt'] ?? mb_substr(strip_tags($featuredNews['content']), 0, 200)) ?></p>
                <div class="news-featured__meta">
                    <div class="news-featured__meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <?= e($featuredNews['author']) ?>
                    </div>
                    <div class="news-featured__meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <?= e(format_date($featuredNews['published_at'], 'F d, Y')) ?>
                    </div>
                </div>
                <a href="<?= url('news-detail', ['slug' => $featuredNews['slug']]) ?>" class="btn btn--red btn--lg">Read Full Article &rarr;</a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ALL NEWS -->
<section class="news-grid" id="newsGrid">
    <div class="container">
        <div class="news-grid__header fade-in">
            <div>
                <span class="section-badge">ALL ARTICLES</span>
                <h2 class="section-title"><?= $activeTag ? 'Filtered: ' . e($activeTag) : 'All News Articles' ?></h2>
            </div>
            <?php if (!empty($allTags)): ?>
            <div class="news-grid__filters">
                <a href="<?= url('news') ?>" class="news-grid__filter-btn <?= !$activeTag ? 'news-grid__filter-btn--active' : '' ?>">All</a>
                <?php foreach ($allTags as $tag): ?>
                <a href="<?= url('news', ['tag' => $tag]) ?>" class="news-grid__filter-btn <?= $activeTag === strtoupper($tag) ? 'news-grid__filter-btn--active' : '' ?>"><?= e($tag) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if (empty($allNews)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
            <p>No news articles found. Check back soon!</p>
        </div>
        <?php else: ?>
        <div class="news-grid__items">
            <?php foreach ($allNews as $article): ?>
            <?php
            // Skip the featured article if no tag filter is active (it's already shown above)
            if (!$activeTag && $featuredNews && (int)$article['id'] === (int)$featuredNews['id']) continue;
            ?>
            <a href="<?= url('news-detail', ['slug' => $article['slug']]) ?>" class="news-card fade-in">
                <div class="news-card__image">
                    <img src="<?= e($article['image']) ?>" alt="<?= e($article['title']) ?>" loading="lazy">
                </div>
                <div class="news-card__body">
                    <?php $articleTags = get_tags($article['tags']); ?>
                    <?php if (!empty($articleTags)): ?>
                    <div class="news-card__tags">
                        <?php foreach (array_slice($articleTags, 0, 2) as $t): ?>
                        <span class="tag"><?= e($t) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <h3 class="news-card__title"><?= e($article['title']) ?></h3>
                    <p class="news-card__excerpt"><?= e(mb_substr(strip_tags($article['excerpt'] ?? $article['content']), 0, 140)) ?>...</p>
                    <div class="news-card__meta">
                        <span class="news-card__author"><?= e($article['author']) ?></span>
                        <span class="news-card__date"><?= e(format_date($article['published_at'], 'M d, Y')) ?></span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>