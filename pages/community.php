<?php
/**
 * NCB Website - Community Board Page (Twitter-like Feed)
 */

$flash = get_flash();
$loggedIn = is_pub_logged_in();
$currentUser = current_pub_user();
$posts = get_posts(30);

// Build current user ID for like/delete checks
$currentUserId = $currentUser ? (int)$currentUser['id'] : 0;
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">COMMUNITY</span>
            <h1 class="page-hero__title">Community Board</h1>
            <p class="page-hero__subtitle">Connect, share, and engage with fellow NCB members. Your voice matters.</p>
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

<!-- COMMUNITY FEED -->
<section class="community-feed" id="communityFeed">
    <div class="container">
        <div class="community-feed__wrapper">

            <!-- LOGIN PROMPT (when not logged in) -->
            <?php if (!$loggedIn): ?>
            <div class="community-feed__login-prompt fade-in">
                <div class="community-feed__login-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <h3 class="community-feed__login-title">Join the Conversation</h3>
                <p class="community-feed__login-text">Please login or register to post and interact with the community.</p>
                <div class="community-feed__login-actions">
                    <a href="<?= url('login') ?>" class="btn btn--red">Login</a>
                    <a href="<?= url('register') ?>" class="btn btn--outline">Register</a>
                </div>
            </div>
            <?php else: ?>

            <!-- POST COMPOSER -->
            <div class="community-feed__composer fade-in">
                <div class="community-feed__composer-avatar">
                    <?php if (!empty($currentUser['avatar'])): ?>
                        <img src="<?= e($currentUser['avatar']) ?>" alt="<?= e($currentUser['name']) ?>">
                    <?php else: ?>
                        <div class="community-feed__avatar-placeholder">
                            <?= e(mb_substr($currentUser['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <form method="POST" action="actions/post-create.php" class="community-feed__composer-form" enctype="multipart/form-data">
                    <textarea name="content" class="community-feed__composer-textarea" placeholder="What's on your mind, <?= e($currentUser['name']) ?>?" required></textarea>
                    <div class="community-feed__composer-footer">
                        <label class="community-feed__composer-attach" for="post_image">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            <span>Photo</span>
                            <input type="file" id="post_image" name="image" accept="image/*" style="display:none">
                        </label>
                        <button type="submit" class="btn btn--red btn--sm">Post</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- POSTS FEED -->
            <?php if (empty($posts)): ?>
            <div class="community-feed__empty fade-in">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <h3>No Posts Yet</h3>
                <p>Be the first to share something with the community!</p>
            </div>
            <?php else: ?>
            <div class="community-feed__posts">
                <?php foreach ($posts as $post): ?>
                <?php
                    $isOwner = ($currentUserId && (int)$post['user_id'] === $currentUserId);
                    $hasLiked = $currentUserId ? user_liked_post($post['id'], $currentUserId) : false;
                    $timeAgo = format_date($post['created_at'], 'M d, Y');
                ?>
                <article class="feed-post fade-in">
                    <div class="feed-post__header">
                        <div class="feed-post__avatar">
                            <?php if (!empty($post['avatar'])): ?>
                                <img src="<?= e($post['avatar']) ?>" alt="<?= e($post['full_name']) ?>">
                            <?php else: ?>
                                <div class="community-feed__avatar-placeholder">
                                    <?= e(mb_substr($post['full_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="feed-post__user-info">
                            <span class="feed-post__name"><?= e($post['full_name']) ?></span>
                            <span class="feed-post__username">@<?= e($post['username']) ?></span>
                            <span class="feed-post__dot">&middot;</span>
                            <span class="feed-post__time"><?= e($timeAgo) ?></span>
                        </div>
                    </div>
                    <div class="feed-post__content">
                        <p><?= nl2br(e($post['content'])) ?></p>
                    </div>
                    <?php if (!empty($post['image'])): ?>
                    <div class="feed-post__image">
                        <a href="<?= e($post['image']) ?>" target="_blank" rel="noopener noreferrer">
                            <img src="<?= e($post['image']) ?>" alt="Post image">
                        </a>
                    </div>
                    <?php endif; ?>
                    <div class="feed-post__actions">
                        <form method="POST" action="actions/post-like.php" class="feed-post__like-form">
                            <input type="hidden" name="post_id" value="<?= e($post['id']) ?>">
                            <button type="submit" class="feed-post__action-btn <?= $hasLiked ? 'feed-post__action-btn--liked' : '' ?>">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="<?= $hasLiked ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                <span><?= (int)$post['likes_count'] > 0 ? e($post['likes_count']) : '' ?></span>
                            </button>
                        </form>
                        <?php if ($isOwner): ?>
                        <form method="POST" action="actions/post-delete.php" class="feed-post__delete-form" onsubmit="return confirm('Are you sure you want to delete this post?');">
                            <input type="hidden" name="post_id" value="<?= e($post['id']) ?>">
                            <button type="submit" class="feed-post__action-btn feed-post__action-btn--delete">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                <span>Delete</span>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<style>
/* Feed Wrapper - centered max-width */
.community-feed__wrapper {
    max-width: 680px;
    margin: 0 auto;
    padding: 2rem 1rem 3rem;
}

/* Login Prompt */
.community-feed__login-prompt {
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 2.5rem 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    text-align: center;
}
.community-feed__login-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: var(--color-bg, #f9fafb);
    color: var(--color-text-light, #9ca3af);
    margin-bottom: 1.25rem;
}
.community-feed__login-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 0.5rem;
}
.community-feed__login-text {
    font-size: 0.925rem;
    color: var(--color-text-light, #6b7280);
    margin: 0 0 1.5rem;
    line-height: 1.5;
}
.community-feed__login-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
}

/* Avatar Placeholder */
.community-feed__avatar-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
    color: #fff;
    font-weight: 700;
    font-size: 0.85rem;
}

/* Composer */
.community-feed__composer {
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    margin-bottom: 1.25rem;
    display: flex;
    gap: 1rem;
}
.community-feed__composer-avatar {
    flex-shrink: 0;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
}
.community-feed__composer-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.community-feed__composer-form {
    flex: 1;
    display: flex;
    flex-direction: column;
}
.community-feed__composer-textarea {
    width: 100%;
    min-height: 80px;
    padding: 0.65rem 0;
    border: none;
    outline: none;
    font-size: 0.95rem;
    font-family: inherit;
    color: var(--color-dark, #111827);
    resize: none;
    background: transparent;
    line-height: 1.5;
}
.community-feed__composer-textarea::placeholder {
    color: var(--color-text-light, #9ca3af);
}
.community-feed__composer-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 0.75rem;
    border-top: 1.5px solid var(--color-border, #e5e7eb);
    margin-top: 0.5rem;
}
.community-feed__composer-attach {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.4rem 0.75rem;
    font-size: 0.825rem;
    font-weight: 500;
    color: var(--color-primary, #e53935);
    border-radius: 50px;
    cursor: pointer;
    transition: background 0.2s ease;
}
.community-feed__composer-attach:hover {
    background: var(--color-primary-light, #fef2f2);
}

/* Empty State */
.community-feed__empty {
    text-align: center;
    padding: 3rem 1.5rem;
    color: var(--color-text-light, #9ca3af);
}
.community-feed__empty svg {
    margin-bottom: 1rem;
    opacity: 0.5;
}
.community-feed__empty h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 0.35rem;
}
.community-feed__empty p {
    font-size: 0.9rem;
    margin: 0;
}

/* Posts Container */
.community-feed__posts {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Single Post */
.feed-post {
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 1.25rem 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
}
.feed-post__header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.85rem;
}
.feed-post__avatar {
    flex-shrink: 0;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    overflow: hidden;
}
.feed-post__avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.feed-post__user-info {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 0.25rem;
    line-height: 1.3;
}
.feed-post__name {
    font-size: 0.925rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
}
.feed-post__username {
    font-size: 0.825rem;
    color: var(--color-text-light, #6b7280);
}
.feed-post__dot {
    font-size: 0.825rem;
    color: var(--color-text-light, #9ca3af);
}
.feed-post__time {
    font-size: 0.825rem;
    color: var(--color-text-light, #6b7280);
}
.feed-post__content {
    margin-bottom: 0.85rem;
}
.feed-post__content p {
    font-size: 0.925rem;
    color: var(--color-dark, #111827);
    line-height: 1.65;
    margin: 0;
    white-space: pre-wrap;
    word-break: break-word;
}
.feed-post__image {
    margin-bottom: 0.85rem;
    border-radius: 14px;
    overflow: hidden;
}
.feed-post__image img {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    display: block;
}
.feed-post__actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1.5px solid var(--color-border, #e5e7eb);
}
.feed-post__like-form {
    margin: 0;
}
.feed-post__delete-form {
    margin-left: auto;
}
.feed-post__action-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.65rem;
    font-size: 0.825rem;
    font-weight: 500;
    font-family: inherit;
    color: var(--color-text-light, #6b7280);
    background: none;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.feed-post__action-btn:hover {
    background: var(--color-bg, #f9fafb);
}
.feed-post__action-btn--liked {
    color: var(--color-primary, #e53935);
}
.feed-post__action-btn--liked:hover {
    background: var(--color-primary-light, #fef2f2);
}
.feed-post__action-btn--delete {
    color: var(--color-text-light, #9ca3af);
}
.feed-post__action-btn--delete:hover {
    color: #dc2626;
    background: #fef2f2;
}

@media (max-width: 640px) {
    .community-feed__wrapper {
        padding: 1.25rem 0.75rem 2rem;
    }
    .feed-post {
        padding: 1rem;
        border-radius: 16px;
    }
    .community-feed__composer {
        padding: 1rem;
        border-radius: 16px;
    }
}
</style>