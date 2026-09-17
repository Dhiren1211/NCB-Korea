<?php
/**
 * NCB Website - Downloads Page
 */

// Get all active downloads
$downloads = get_downloads();

// Helper: get file type color class
function get_file_type_class($fileType) {
    $type = strtoupper($fileType);
    switch ($type) {
        case 'PDF':  return 'downloads-item__badge--pdf';
        case 'DOC':
        case 'DOCX': return 'downloads-item__badge--doc';
        case 'XLS':
        case 'XLSX': return 'downloads-item__badge--xls';
        case 'PPT':
        case 'PPTX': return 'downloads-item__badge--ppt';
        case 'ZIP':
        case 'RAR':  return 'downloads-item__badge--zip';
        case 'JPG':
        case 'PNG':
        case 'JPEG': return 'downloads-item__badge--img';
        default:     return 'downloads-item__badge--default';
    }
}

// Helper: get file type SVG icon
function get_file_type_icon($fileType) {
    $type = strtoupper($fileType);
    switch ($type) {
        case 'PDF':
            return '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>';
        case 'DOC':
        case 'DOCX':
            return '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>';
        case 'XLS':
        case 'XLSX':
            return '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><rect x="8" y="12" width="8" height="6" rx="1"/><line x1="11" y1="15" x2="11" y2="15.01"/><line x1="8" y1="10" x2="10" y2="10"/></svg>';
        default:
            return '<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>';
    }
}
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">RESOURCES</span>
            <h1 class="page-hero__title">Downloads</h1>
            <p class="page-hero__subtitle">Access forms, reports, guides, and other useful documents provided by NCB.</p>
        </div>
    </div>
</section>

<!-- DOWNLOADS LIST -->
<section class="downloads-section" id="downloadsSection">
    <div class="container">
        <div class="section-header fade-in">
            <span class="section-badge">DOCUMENTS</span>
            <h2 class="section-title">Available Downloads</h2>
            <p class="section-subtitle">Browse and download important documents, forms, and resources for NCB members.</p>
        </div>

        <?php if (empty($downloads)): ?>
        <!-- EMPTY STATE -->
        <div class="empty-state fade-in">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            <p>No downloads available at the moment. Please check back later.</p>
        </div>

        <?php else: ?>
        <!-- DOWNLOADS TABLE -->
        <div class="downloads-table fade-in">
            <!-- Table Header -->
            <div class="downloads-table__header">
                <div class="downloads-table__col downloads-table__col--file">File</div>
                <div class="downloads-table__col downloads-table__col--desc">Description</div>
                <div class="downloads-table__col downloads-table__col--type">Type</div>
                <div class="downloads-table__col downloads-table__col--size">Size</div>
                <div class="downloads-table__col downloads-table__col--count">Downloads</div>
                <div class="downloads-table__col downloads-table__col--action"></div>
            </div>

            <!-- Table Rows -->
            <?php foreach ($downloads as $item): ?>
            <div class="downloads-item">
                <div class="downloads-table__col downloads-table__col--file">
                    <div class="downloads-item__file">
                        <div class="downloads-item__icon <?= get_file_type_class($item['file_type']) ?>">
                            <?= get_file_type_icon($item['file_type']) ?>
                        </div>
                        <div class="downloads-item__name">
                            <h4 class="downloads-item__title"><?= e($item['title']) ?></h4>
                        </div>
                    </div>
                </div>
                <div class="downloads-table__col downloads-table__col--desc">
                    <p class="downloads-item__desc"><?= e($item['description']) ?></p>
                </div>
                <div class="downloads-table__col downloads-table__col--type">
                    <span class="downloads-item__badge <?= get_file_type_class($item['file_type']) ?>">
                        <?= e(strtoupper($item['file_type'])) ?>
                    </span>
                </div>
                <div class="downloads-table__col downloads-table__col--size">
                    <span class="downloads-item__size"><?= e($item['file_size']) ?></span>
                </div>
                <div class="downloads-table__col downloads-table__col--count">
                    <div class="downloads-item__count">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        <?= e(number_format((int)$item['download_count'])) ?>
                    </div>
                </div>
                <div class="downloads-table__col downloads-table__col--action">
                    <a href="<?= e($item['file_path']) ?>" class="downloads-item__download-btn btn btn--red btn--sm" download>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Mobile Card View (hidden on desktop) -->
        <div class="downloads-cards fade-in">
            <?php foreach ($downloads as $item): ?>
            <div class="downloads-card">
                <div class="downloads-card__top">
                    <div class="downloads-card__icon <?= get_file_type_class($item['file_type']) ?>">
                        <?= get_file_type_icon($item['file_type']) ?>
                    </div>
                    <div class="downloads-card__info">
                        <h4 class="downloads-card__title"><?= e($item['title']) ?></h4>
                        <p class="downloads-card__desc"><?= e($item['description']) ?></p>
                    </div>
                </div>
                <div class="downloads-card__meta">
                    <span class="downloads-item__badge <?= get_file_type_class($item['file_type']) ?>"><?= e(strtoupper($item['file_type'])) ?></span>
                    <span class="downloads-card__size"><?= e($item['file_size']) ?></span>
                    <span class="downloads-card__count">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        <?= e(number_format((int)$item['download_count'])) ?> downloads
                    </span>
                </div>
                <a href="<?= e($item['file_path']) ?>" class="downloads-card__download btn btn--red btn--sm" download>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
/* Downloads Section */
.downloads-section {
    padding: 3rem 0 5rem;
}

/* Table Layout (Desktop) */
.downloads-table {
    background: var(--color-white, #fff);
    border-radius: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    overflow: hidden;
}
.downloads-table__header {
    display: grid;
    grid-template-columns: 2fr 2.5fr 80px 80px 100px 120px;
    align-items: center;
    padding: 1rem 1.5rem;
    background: var(--color-bg, #f9fafb);
    border-bottom: 1.5px solid var(--color-border, #e5e7eb);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--color-text-light, #6b7280);
}

/* Download Item Row */
.downloads-item {
    display: grid;
    grid-template-columns: 2fr 2.5fr 80px 80px 100px 120px;
    align-items: center;
    padding: 1.15rem 1.5rem;
    border-bottom: 1px solid var(--color-border, #e5e7eb);
    transition: background 0.15s ease;
}
.downloads-item:last-child {
    border-bottom: none;
}
.downloads-item:hover {
    background: var(--color-bg, #f9fafb);
}

/* File Cell */
.downloads-item__file {
    display: flex;
    align-items: center;
    gap: 0.85rem;
}
.downloads-item__icon {
    flex-shrink: 0;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-bg, #f9fafb);
    color: var(--color-text, #374151);
}
.downloads-item__icon--pdf,
.downloads-item__badge--pdf {
    background: rgba(220, 38, 38, 0.08);
    color: #dc2626;
}
.downloads-item__icon--doc,
.downloads-item__badge--doc {
    background: rgba(59, 130, 246, 0.08);
    color: #3b82f6;
}
.downloads-item__icon--xls,
.downloads-item__badge--xls {
    background: rgba(34, 197, 94, 0.08);
    color: #22c55e;
}
.downloads-item__icon--ppt,
.downloads-item__badge--ppt {
    background: rgba(245, 158, 11, 0.08);
    color: #f59e0b;
}
.downloads-item__icon--zip,
.downloads-item__badge--zip {
    background: rgba(139, 92, 246, 0.08);
    color: #8b5cf6;
}
.downloads-item__icon--img,
.downloads-item__badge--img {
    background: rgba(236, 72, 153, 0.08);
    color: #ec4899;
}

.downloads-item__title {
    font-size: 0.925rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
    margin: 0;
}

/* Description Cell */
.downloads-item__desc {
    font-size: 0.85rem;
    color: var(--color-text, #374151);
    margin: 0;
    line-height: 1.5;
}

/* Badge */
.downloads-item__badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border-radius: 6px;
    background: var(--color-bg, #f9fafb);
    color: var(--color-text-light, #6b7280);
}

/* Size & Count */
.downloads-item__size {
    font-size: 0.85rem;
    color: var(--color-text-light, #6b7280);
    font-weight: 500;
}
.downloads-item__count {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.85rem;
    color: var(--color-text-light, #6b7280);
    font-weight: 500;
}

/* Download Button */
.downloads-item__download-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    white-space: nowrap;
}

/* Mobile Cards (hidden on desktop) */
.downloads-cards {
    display: none;
    flex-direction: column;
    gap: 1rem;
}

/* Card Item */
.downloads-card {
    background: var(--color-white, #fff);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
}
.downloads-card__top {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 0.75rem;
}
.downloads-card__icon {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-bg, #f9fafb);
    color: var(--color-text, #374151);
}
.downloads-card__info {
    flex: 1;
}
.downloads-card__title {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
    margin: 0 0 0.25rem;
}
.downloads-card__desc {
    font-size: 0.85rem;
    color: var(--color-text, #374151);
    margin: 0;
    line-height: 1.5;
}
.downloads-card__meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    font-size: 0.8rem;
    color: var(--color-text-light, #6b7280);
    flex-wrap: wrap;
}
.downloads-card__size {
    font-weight: 500;
}
.downloads-card__count {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-weight: 500;
}
.downloads-card__download {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

/* Responsive: Hide table, show cards on small screens */
@media (max-width: 900px) {
    .downloads-table {
        display: none;
    }
    .downloads-cards {
        display: flex;
    }
}
</style>
