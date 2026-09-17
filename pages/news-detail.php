<?php
/**
 * NCB Website - News Detail Page
 */

// Retrieve article by slug
$newsArticle = null;
if (isset($_GET['slug'])) {
    $newsArticle = get_news_by_slug($_GET['slug']);
}

// Parse tags
$newsArticleTags = $newsArticle ? get_tags($newsArticle['tags']) : [];

// Related articles — other published news, limit 3, exclude current
$relatedArticles = [];
if ($newsArticle) {
    $allNews = get_news(null, false);
    $relatedArticles = array_filter($allNews, function($n) use ($newsArticle) {
        return (int)$n['id'] !== (int)$newsArticle['id'];
    });
    $relatedArticles = array_values($relatedArticles);
    $relatedArticles = array_slice($relatedArticles, 0, 3);
}

// Downloads for sidebar card
$downloads = get_downloads();
$downloadsPreview = array_slice($downloads, 0, 3);
?>

<!-- ERROR STATE -->
<?php if (!$newsArticle): ?>
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">ARTICLE NOT FOUND</span>
            <h1 class="page-hero__title">Article Not Found</h1>
            <p class="page-hero__subtitle">The article you are looking for does not exist or may have been removed.</p>
            <a href="<?= url('news') ?>" class="btn btn--red btn--lg" style="margin-top:1.5rem;">Browse All News &rarr;</a>
        </div>
    </div>
</section>
<?php return; endif; ?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <?php if (!empty($newsArticleTags)): ?>
            <div class="page-hero__tags">
                <?php foreach ($newsArticleTags as $tag): ?>
                <span class="page-hero__badge"><?= e($tag) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <h1 class="page-hero__title"><?= e($newsArticle['title']) ?></h1>
            <p class="page-hero__subtitle"><?= e(format_date($newsArticle['published_at'], 'F d, Y')) ?></p>
        </div>
    </div>
</section>

<!-- ARTICLE CONTENT -->
<section class="news-detail" id="newsDetail">
    <div class="container">
        <div class="news-detail__layout">
            <!-- Main Article -->
            <article class="news-detail__article fade-in">
                <!-- Featured Image -->
                <div class="news-detail__image">
                    <img src="<?= e($newsArticle['image']) ?>" alt="<?= e($newsArticle['title']) ?>">
                </div>

                <!-- Article Meta -->
                <div class="news-detail__meta">
                    <div class="news-detail__meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span><?= e($newsArticle['author']) ?></span>
                    </div>
                    <div class="news-detail__meta-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        <span><?= e(format_date($newsArticle['published_at'], 'F d, Y')) ?></span>
                    </div>
                </div>

                <!-- Full Content -->
                <div class="news-detail__content">
                    <?= nl2br(e($newsArticle['content'])) ?>
                </div>

                <!-- Tags -->
                <?php if (!empty($newsArticleTags)): ?>
                <div class="news-detail__tags">
                    <span class="section-label">TAGGED IN</span>
                    <div class="news-detail__tags-list">
                        <?php foreach ($newsArticleTags as $tag): ?>
                        <a href="<?= url('news', ['tag' => $tag]) ?>" class="tag"><?= e($tag) ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Back Link -->
                <div class="news-detail__back" style="margin-top:2rem;">
                    <a href="<?= url('news') ?>" class="btn btn--outline">&larr; Back to All News</a>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="news-sidebar" id="newsSidebar">
                <!-- Related Articles -->
                <?php if (!empty($relatedArticles)): ?>
                <div class="news-sidebar__section fade-in">
                    <h3 class="news-sidebar__title">Related Articles</h3>
                    <div class="news-sidebar__items">
                        <?php foreach ($relatedArticles as $related): ?>
                        <a href="<?= url('news-detail', ['slug' => $related['slug']]) ?>" class="news-sidebar__item">
                            <div class="news-sidebar__item-image">
                                <img src="<?= e($related['image']) ?>" alt="<?= e($related['title']) ?>" loading="lazy">
                            </div>
                            <div class="news-sidebar__item-body">
                                <span class="news-sidebar__item-date"><?= e(format_date($related['published_at'], 'M d, Y')) ?></span>
                                <h4 class="news-sidebar__item-title"><?= e($related['title']) ?></h4>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Downloads Sidebar Card -->
                <?php if (!empty($downloads)): ?>
                <div class="news-sidebar__section fade-in">
                    <h3 class="news-sidebar__title">Downloads</h3>
                    <div class="news-sidebar__downloads">
                        <?php foreach ($downloadsPreview as $dl): ?>
                        <a href="actions/download.php?id=<?= e($dl['id']) ?>" class="news-sidebar__download-item">
                            <div class="news-sidebar__download-icon">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
                            </div>
                            <div class="news-sidebar__download-info">
                                <span class="news-sidebar__download-title"><?= e($dl['title']) ?></span>
                                <span class="news-sidebar__download-meta"><?= e($dl['file_type']) ?> &middot; <?= e($dl['file_size']) ?></span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                        <?php if (count($downloads) > 3): ?>
                        <a href="<?= url('downloads') ?>" class="btn btn--outline btn--sm" style="width:100%;margin-top:0.75rem;">View All Downloads &rarr;</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>
