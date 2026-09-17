<?php
/**
 * NCB Website - Community Forum / Complaints Page
 */

$flash = get_flash();
$loggedIn = is_pub_logged_in();
$currentUser = current_pub_user();
$currentUserId = $currentUser ? (int)$currentUser['id'] : 0;

// Determine view mode
$action = isset($_GET['action']) ? $_GET['action'] : '';
$complaintId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$viewMode = ($action === 'view' && $complaintId > 0);

if ($viewMode) {
    $complaint = get_complaint_by_id($complaintId);
    $replies = $complaint ? get_complaint_replies($complaintId) : [];
} else {
    $complaints = get_complaints();
}

// Status color map
$statusColors = [
    'open'       => 'background:#dbeafe;color:#1d4ed8;border-color:#93c5fd',
    'in_progress' => 'background:#fef3c7;color:#b45309;border-color:#fcd34d',
    'resolved'   => 'background:#d1fae5;color:#065f46;border-color:#6ee7b7',
    'closed'     => 'background:#f3f4f6;color:#374151;border-color:#d1d5db',
];
$statusLabels = [
    'open' => 'Open',
    'in_progress' => 'In Progress',
    'resolved' => 'Resolved',
    'closed' => 'Closed',
];
$categoryColors = [
    'Community' => 'background:#f0fdf4;color:#166534;border-color:#86efac',
    'Services'  => 'background:#eff6ff;color:#1e40af;border-color:#93c5fd',
    'Event'     => 'background:#fef3c7;color:#92400e;border-color:#fcd34d',
    'Safety'    => 'background:#fef2f2;color:#991b1b;border-color:#fca5a5',
    'Other'     => 'background:#f3f4f6;color:#374151;border-color:#d1d5db',
];
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">FORUM</span>
            <h1 class="page-hero__title">Community Forum</h1>
            <p class="page-hero__subtitle">Raise concerns, suggest improvements, and work together to solve community problems.</p>
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

<!-- VIEW MODE: Single Complaint Detail -->
<?php if ($viewMode): ?>
<?php if (!$complaint): ?>
<section class="complaint-section">
    <div class="container">
        <div class="complaint-section__wrapper fade-in">
            <div class="complaint-section__not-found">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <h3>Complaint Not Found</h3>
                <p>The complaint you're looking for doesn't exist or has been removed.</p>
                <a href="<?= url('complaints') ?>" class="btn btn--outline" style="margin-top:1rem;">&larr; Back to Forum</a>
            </div>
        </div>
    </div>
</section>
<?php else: ?>
<section class="complaint-section">
    <div class="container">
        <div class="complaint-section__wrapper">

            <!-- Back Link -->
            <a href="<?= url('complaints') ?>" class="complaint-section__back fade-in" style="display:inline-flex;align-items:center;gap:0.4rem;font-size:0.875rem;font-weight:600;color:var(--color-text-light,#6b7280);text-decoration:none;margin-bottom:1.5rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Forum
            </a>

            <!-- Complaint Detail -->
            <div class="complaint-detail fade-in">
                <div class="complaint-detail__header">
                    <div class="complaint-detail__badges">
                        <span class="complaint-detail__badge" style="<?= e($categoryColors[$complaint['category']] ?? '') ?>"><?= e($complaint['category']) ?></span>
                        <?php
                        $sKey = $complaint['status'];
                        $sStyle = $statusColors[$sKey] ?? $statusColors['open'];
                        $sLabel = $statusLabels[$sKey] ?? ucfirst($sKey);
                        ?>
                        <span class="complaint-detail__badge complaint-detail__badge--status" style="<?= e($sStyle) ?>"><?= e($sLabel) ?></span>
                    </div>
                    <h2 class="complaint-detail__title"><?= e($complaint['title']) ?></h2>
                    <div class="complaint-detail__meta">
                        <span>Posted by <strong><?= e($complaint['full_name']) ?></strong></span>
                        <span>&middot;</span>
                        <span><?= e(format_date($complaint['created_at'], 'M d, Y \a\t g:i A')) ?></span>
                    </div>
                </div>
                <div class="complaint-detail__body">
                    <p><?= nl2br(e($complaint['description'])) ?></p>
                </div>
            </div>

            <!-- Replies -->
            <div class="complaint-replies fade-in">
                <h3 class="complaint-replies__title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Replies (<?= count($replies) ?>)
                </h3>

                <?php if (empty($replies)): ?>
                <div class="complaint-replies__empty">
                    <p>No replies yet. Be the first to respond!</p>
                </div>
                <?php else: ?>
                <div class="complaint-replies__list">
                    <?php foreach ($replies as $reply): ?>
                    <div class="complaint-reply">
                        <div class="complaint-reply__avatar">
                            <?php if (!empty($reply['avatar'])): ?>
                                <img src="<?= e($reply['avatar']) ?>" alt="<?= e($reply['full_name']) ?>">
                            <?php else: ?>
                                <div class="complaint-reply__avatar-placeholder"><?= e(mb_substr($reply['full_name'], 0, 1)) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="complaint-reply__body">
                            <div class="complaint-reply__header">
                                <span class="complaint-reply__name"><?= e($reply['full_name']) ?></span>
                                <span class="complaint-reply__date"><?= e(format_date($reply['created_at'], 'M d, Y \a\t g:i A')) ?></span>
                            </div>
                            <p class="complaint-reply__message"><?= nl2br(e($reply['message'])) ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Reply Form -->
                <?php if ($loggedIn): ?>
                <form method="POST" action="actions/complaint-reply.php" class="complaint-reply-form fade-in">
                    <input type="hidden" name="complaint_id" value="<?= e($complaintId) ?>">
                    <textarea name="message" class="form-textarea" rows="4" placeholder="Write your reply..." required style="width:100%;padding:0.75rem 1rem;font-size:0.9rem;font-family:inherit;color:var(--color-dark,#111827);background:var(--color-bg,#f9fafb);border:1.5px solid var(--color-border,#e5e7eb);border-radius:12px;outline:none;resize:vertical;transition:border-color 0.2s ease,box-shadow 0.2s ease;"></textarea>
                    <div style="text-align:right;margin-top:0.75rem;">
                        <button type="submit" class="btn btn--red">Post Reply</button>
                    </div>
                </form>
                <?php else: ?>
                <div class="complaint-replies__login-hint">
                    <a href="<?= url('login') ?>" class="btn btn--outline btn--sm">Login to Reply</a>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
<?php endif; ?>

<!-- LIST MODE: All Complaints -->
<?php else: ?>
<section class="complaint-section">
    <div class="container">
        <div class="complaint-section__wrapper">

            <!-- New Complaint Form (logged in only) -->
            <?php if ($loggedIn): ?>
            <div class="complaint-form-card fade-in">
                <div class="complaint-form-card__header">
                    <h2 class="complaint-form-card__title">Raise a New Issue</h2>
                    <p class="complaint-form-card__desc">Describe your concern or suggestion. Our community team will review and respond.</p>
                </div>
                <form method="POST" action="actions/complaint-create.php" class="complaint-form-card__form">
                    <div class="form-group">
                        <label class="form-label" for="comp_title">Title *</label>
                        <input type="text" id="comp_title" name="title" class="form-input" placeholder="Brief summary of your concern" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="comp_category">Category *</label>
                        <select id="comp_category" name="category" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <option value="Community">Community</option>
                            <option value="Services">Services</option>
                            <option value="Event">Event</option>
                            <option value="Safety">Safety</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="comp_desc">Description *</label>
                        <textarea id="comp_desc" name="description" class="form-textarea" rows="5" placeholder="Provide details about your concern or suggestion..." required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--red">Submit Issue &rarr;</button>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <div class="complaint-section__login-prompt fade-in">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <div>
                    <p style="margin:0 0 0.75rem;font-size:0.925rem;color:var(--color-text,#374151);">Please <a href="<?= url('login') ?>" style="color:var(--color-primary,#e53935);font-weight:600;">login</a> or <a href="<?= url('register') ?>" style="color:var(--color-primary,#e53935);font-weight:600;">register</a> to raise issues and participate in the forum.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Complaints List -->
            <div class="complaint-list fade-in">
                <h3 class="complaint-list__title">All Issues</h3>

                <?php if (empty($complaints)): ?>
                <div class="complaint-list__empty">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <p>No issues have been raised yet. Be the first to share a concern!</p>
                </div>
                <?php else: ?>
                <div class="complaint-list__items">
                    <?php foreach ($complaints as $c): ?>
                    <?php
                    $cStatusKey = $c['status'];
                    $cStatusStyle = $statusColors[$cStatusKey] ?? $statusColors['open'];
                    $cStatusLabel = $statusLabels[$cStatusKey] ?? ucfirst($cStatusKey);
                    $cCatStyle = $categoryColors[$c['category']] ?? '';
                    ?>
                    <a href="<?= url('complaints', ['action' => 'view', 'id' => $c['id']]) ?>" class="complaint-list__item">
                        <div class="complaint-list__item-badges">
                            <span class="complaint-list__item-badge" style="<?= e($cCatStyle) ?>"><?= e($c['category']) ?></span>
                            <span class="complaint-list__item-badge" style="<?= e($cStatusStyle) ?>"><?= e($cStatusLabel) ?></span>
                        </div>
                        <h4 class="complaint-list__item-title"><?= e($c['title']) ?></h4>
                        <p class="complaint-list__item-desc"><?= e(mb_substr(strip_tags($c['description']), 0, 150)) ?><?php if (mb_strlen(strip_tags($c['description'])) > 150): ?>...<?php endif; ?></p>
                        <div class="complaint-list__item-meta">
                            <span>
                                <?php if (!empty($c['avatar'])): ?>
                                <img src="<?= e($c['avatar']) ?>" alt="" style="width:20px;height:20px;border-radius:50%;object-fit:cover;vertical-align:middle;margin-right:0.3rem;">
                                <?php endif; ?>
                                <?= e($c['full_name']) ?>
                            </span>
                            <span>&middot;</span>
                            <span><?= e(format_date($c['created_at'], 'M d, Y')) ?></span>
                            <span style="margin-left:auto;display:inline-flex;align-items:center;gap:0.3rem;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                <?= (int)$c['reply_count'] ?> replies
                            </span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
<?php endif; ?>

<style>
/* Complaints Wrapper */
.complaint-section__wrapper {
    max-width: 740px;
    margin: 0 auto;
    padding: 2rem 1rem 3rem;
}

/* Login Prompt */
.complaint-section__login-prompt {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--color-bg, #f9fafb);
    border: 1.5px solid var(--color-border, #e5e7eb);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    color: var(--color-text-light, #9ca3af);
}

/* Not Found */
.complaint-section__not-found {
    text-align: center;
    padding: 3rem 1.5rem;
    color: var(--color-text-light, #9ca3af);
}
.complaint-section__not-found svg {
    margin-bottom: 1rem;
    opacity: 0.5;
}
.complaint-section__not-found h3 {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 0.35rem;
}
.complaint-section__not-found p {
    font-size: 0.9rem;
    margin: 0;
}

/* Complaint Form Card */
.complaint-form-card {
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    margin-bottom: 2rem;
}
.complaint-form-card__title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 0.25rem;
}
.complaint-form-card__desc {
    font-size: 0.875rem;
    color: var(--color-text-light, #6b7280);
    margin: 0 0 1.25rem;
}
.complaint-form-card__form .form-group {
    margin-bottom: 1.15rem;
}
.complaint-form-card__form .form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
    margin-bottom: 0.4rem;
}
.complaint-form-card__form .form-input,
.complaint-form-card__form .form-textarea,
.complaint-form-card__form .form-select {
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
.complaint-form-card__form .form-input:focus,
.complaint-form-card__form .form-textarea:focus,
.complaint-form-card__form .form-select:focus {
    border-color: var(--color-primary, #e53935);
    box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.08);
}
.complaint-form-card__form .form-input::placeholder,
.complaint-form-card__form .form-textarea::placeholder {
    color: var(--color-text-light, #9ca3af);
}
.complaint-form-card__form .form-textarea {
    resize: vertical;
    min-height: 100px;
}
.complaint-form-card__form .form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%236b7280' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
}

/* Complaint List */
.complaint-list__title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 1.25rem;
}
.complaint-list__empty {
    text-align: center;
    padding: 2.5rem 1.5rem;
    color: var(--color-text-light, #9ca3af);
}
.complaint-list__empty svg {
    margin-bottom: 0.75rem;
    opacity: 0.5;
}
.complaint-list__empty p {
    font-size: 0.9rem;
    margin: 0;
}
.complaint-list__items {
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
}
.complaint-list__item {
    display: block;
    background: var(--color-white, #fff);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.complaint-list__item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08), 0 12px 28px rgba(0,0,0,0.06);
}
.complaint-list__item-badges {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.65rem;
}
.complaint-list__item-badge {
    display: inline-block;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 0.2rem 0.6rem;
    border-radius: 50px;
    border: 1.5px solid;
}
.complaint-list__item-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 0.35rem;
}
.complaint-list__item-desc {
    font-size: 0.875rem;
    color: var(--color-text, #374151);
    line-height: 1.55;
    margin: 0 0 0.75rem;
}
.complaint-list__item-meta {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    color: var(--color-text-light, #6b7280);
}

/* Complaint Detail */
.complaint-detail {
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 1.75rem 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    margin-bottom: 1.5rem;
}
.complaint-detail__header {
    margin-bottom: 1.25rem;
}
.complaint-detail__badges {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
}
.complaint-detail__badge {
    display: inline-block;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 0.2rem 0.6rem;
    border-radius: 50px;
    border: 1.5px solid;
}
.complaint-detail__title {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--color-dark, #111827);
    margin: 0 0 0.65rem;
    line-height: 1.3;
}
.complaint-detail__meta {
    font-size: 0.85rem;
    color: var(--color-text-light, #6b7280);
}
.complaint-detail__meta strong {
    color: var(--color-dark, #111827);
}
.complaint-detail__body {
    padding-top: 1.25rem;
    border-top: 1.5px solid var(--color-border, #e5e7eb);
}
.complaint-detail__body p {
    font-size: 0.925rem;
    color: var(--color-text, #374151);
    line-height: 1.7;
    margin: 0;
}

/* Replies */
.complaint-replies__title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 1.25rem;
}
.complaint-replies__title svg {
    color: var(--color-text-light, #6b7280);
}
.complaint-replies__empty {
    background: var(--color-bg, #f9fafb);
    border-radius: 14px;
    padding: 2rem;
    text-align: center;
    color: var(--color-text-light, #6b7280);
    font-size: 0.9rem;
}
.complaint-replies__login-hint {
    text-align: center;
    padding: 1.5rem;
}
.complaint-replies__list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.complaint-reply {
    display: flex;
    gap: 0.85rem;
    background: var(--color-bg, #f9fafb);
    border-radius: 16px;
    padding: 1.15rem 1.25rem;
}
.complaint-reply__avatar {
    flex-shrink: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
}
.complaint-reply__avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.complaint-reply__avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
    color: #fff;
    font-weight: 700;
    font-size: 0.8rem;
}
.complaint-reply__body {
    flex: 1;
    min-width: 0;
}
.complaint-reply__header {
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    margin-bottom: 0.35rem;
}
.complaint-reply__name {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
}
.complaint-reply__date {
    font-size: 0.78rem;
    color: var(--color-text-light, #9ca3af);
}
.complaint-reply__message {
    font-size: 0.875rem;
    color: var(--color-text, #374151);
    line-height: 1.6;
    margin: 0;
}

@media (max-width: 640px) {
    .complaint-section__wrapper {
        padding: 1.25rem 0.75rem 2rem;
    }
    .complaint-form-card,
    .complaint-detail {
        padding: 1.25rem;
        border-radius: 16px;
    }
    .complaint-list__item {
        padding: 1rem 1.15rem;
    }
}
</style>