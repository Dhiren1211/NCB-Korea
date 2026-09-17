<?php
/**
 * NCB Website - Gallery Page
 */

// Get categories and active filter
$categories = get_gallery_categories();
$activeCategory = isset($_GET['category']) ? strtoupper($_GET['category']) : 'ALL';
if (!in_array($activeCategory, $categories)) {
    $activeCategory = 'ALL';
}

// Get gallery items for the active category
$galleryItems = get_gallery($activeCategory);
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">OUR MOMENTS</span>
            <h1 class="page-hero__title">Photo Gallery</h1>
            <p class="page-hero__subtitle">Relive the memories from our community events, festivals, and activities.</p>
        </div>
    </div>
</section>

<!-- GALLERY FILTER + GRID -->
<section class="gallery" id="gallerySection">
    <div class="container">
        <!-- Category Filter Tabs -->
        <div class="gallery-filter fade-in">
            <?php foreach ($categories as $cat): ?>
            <a href="<?= url('gallery', ['category' => $cat]) ?>" class="gallery-filter__btn <?= $activeCategory === $cat ? 'gallery-filter__btn--active' : '' ?>"><?= e($cat === 'ALL' ? 'All Photos' : $cat) ?></a>
            <?php endforeach; ?>
        </div>

        <!-- Empty State -->
        <?php if (empty($galleryItems)): ?>
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            <p>No gallery photos found for this category. Check back soon!</p>
        </div>
        <?php else: ?>

        <!-- Masonry-style Gallery Grid -->
        <div class="gallery-grid">
            <?php foreach ($galleryItems as $item): ?>
            <a href="javascript:void(0)"
               class="gallery-grid__item gallery-grid__item--<?= e($item['size'] ?? 'medium') ?> fade-in"
               data-lightbox-img="<?= e(image_url($item['image'])) ?>"
               data-lightbox-title="<?= e($item['title']) ?>"
               onclick="openLightbox(this)">
                <img src="<?= e(image_url($item['image'])) ?>" alt="<?= e($item['title']) ?>" loading="lazy">
                <div class="gallery-grid__overlay">
                    <span class="gallery-grid__overlay-title"><?= e($item['title']) ?></span>
                    <span class="gallery-grid__overlay-category"><?= e($item['category']) ?></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- LIGHTBOX MODAL -->
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)" style="display:none;">
    <button class="lightbox__close" onclick="closeLightbox(event)" aria-label="Close lightbox">&times;</button>
    <div class="lightbox__content">
        <img id="lightbox__img" src="" alt="" class="lightbox__image">
        <div class="lightbox__caption" id="lightbox__caption"></div>
    </div>
</div>

<style>
/* Gallery Filter */
.gallery-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
    margin-bottom: 2.5rem;
}
.gallery-filter__btn {
    display: inline-block;
    padding: 0.6rem 1.4rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--color-text, #374151);
    background: var(--color-white, #fff);
    border: 1px solid var(--color-border, #e5e7eb);
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
}
.gallery-filter__btn:hover {
    background: var(--color-primary-light, #fef2f2);
    border-color: var(--color-primary, #dc2626);
    color: var(--color-primary, #dc2626);
}
.gallery-filter__btn--active {
    background: var(--color-primary, #dc2626);
    border-color: var(--color-primary, #dc2626);
    color: var(--color-white, #fff);
}
.gallery-filter__btn--active:hover {
    background: var(--color-primary-dark, #b91c1c);
    color: var(--color-white, #fff);
}

/* Masonry Gallery Grid */
.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
    grid-auto-rows: 220px;
}
@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
        grid-auto-rows: 180px;
    }
}
@media (max-width: 480px) {
    .gallery-grid {
        grid-template-columns: 1fr;
        grid-auto-rows: 240px;
    }
}
.gallery-grid__item {
    position: relative;
    display: block;
    border-radius: 16px;
    overflow: hidden;
    cursor: pointer;
    text-decoration: none;
}
.gallery-grid__item--large {
    grid-column: span 2;
    grid-row: span 2;
}
.gallery-grid__item--medium {
    grid-row: span 1;
}
.gallery-grid__item--small {
    grid-row: span 1;
}
@media (max-width: 480px) {
    .gallery-grid__item--large {
        grid-column: span 1;
        grid-row: span 1;
    }
}
.gallery-grid__item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.gallery-grid__item:hover img {
    transform: scale(1.08);
}

/* Gallery Hover Overlay */
.gallery-grid__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 60%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 1.25rem;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}
.gallery-grid__item:hover .gallery-grid__overlay {
    opacity: 1;
}
.gallery-grid__overlay-title {
    color: #fff;
    font-weight: 600;
    font-size: 1rem;
    line-height: 1.3;
}
.gallery-grid__overlay-category {
    color: rgba(255,255,255,0.75);
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

/* Lightbox */
.lightbox {
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: rgba(0,0,0,0.92);
    display: flex;
    align-items: center;
    justify-content: center;
    animation: lightboxFadeIn 0.25s ease;
}
@keyframes lightboxFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
.lightbox__close {
    position: absolute;
    top: 1.25rem;
    right: 1.5rem;
    background: rgba(255,255,255,0.15);
    border: none;
    color: #fff;
    font-size: 2rem;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease;
    z-index: 10001;
    line-height: 1;
}
.lightbox__close:hover {
    background: rgba(255,255,255,0.3);
}
.lightbox__content {
    max-width: 90vw;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.lightbox__image {
    max-width: 90vw;
    max-height: 80vh;
    border-radius: 12px;
    object-fit: contain;
}
.lightbox__caption {
    color: rgba(255,255,255,0.85);
    font-size: 1rem;
    font-weight: 500;
    margin-top: 1rem;
    text-align: center;
}
</style>

<script>
function openLightbox(el) {
    var img = el.getAttribute('data-lightbox-img');
    var title = el.getAttribute('data-lightbox-title');
    document.getElementById('lightbox__img').src = img;
    document.getElementById('lightbox__img').alt = title || '';
    document.getElementById('lightbox__caption').textContent = title || '';
    document.getElementById('lightbox').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLightbox(e) {
    if (e && e.target && (e.target.id === 'lightbox' || e.target.classList.contains('lightbox__close'))) {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = '';
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = '';
    }
});
</script>
