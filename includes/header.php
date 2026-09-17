<?php
/**
 * NCB Website - Header Component
 * Dynamically rendered from database settings.
 */
$settings = get_all_settings();
$currentPage = current_page();
$primaryNavItems = [
    ['label' => 'Home',    'page' => 'home'],
    ['label' => 'About',   'page' => 'about'],
    ['label' => 'Events',  'page' => 'events'],
    ['label' => 'News',    'page' => 'news'],
    ['label' => 'Gallery', 'page' => 'gallery'],
];
$moreNavItems = [
    ['label' => 'Volunteers', 'page' => 'volunteers'],
    ['label' => 'Community',  'page' => 'community'],
    ['label' => 'Forum',      'page' => 'complaints'],
    ['label' => 'Contact',    'page' => 'contact'],
    ['label' => 'Login',      'page' => 'login'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($settings['site_name'] ?? 'NCB') ?> — <?= e($settings['tagline'] ?? '') ?></title>
    <meta name="description" content="<?= e($settings['description'] ?? '') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="assets/logo.jpg" type="image/jpeg">
</head>
<body>

<!-- HEADER -->
<header class="header" id="header">
    <div class="header__inner">
        <a href="<?= url('home') ?>" class="header__logo">
            <img src="assets/logo.jpg" alt="NCB Logo" class="header__logo-img">
            <div class="header__logo-text">
                <span class="header__logo-name"><?= e($settings['site_name']) ?></span>
                <!-- <span class="header__logo-sub"><?= e($settings['site_short_name']) ?> &middot; ESTABLISHED <?= e($settings['established']) ?></span> -->
            </div>
        </a>

        <nav class="nav" id="desktopNav">
            <ul class="nav__list">
                <?php foreach ($primaryNavItems as $item): ?>
                <li><a href="<?= url($item['page']) ?>" class="nav__link <?= $currentPage === $item['page'] ? 'nav__link--active' : '' ?>"><?= e($item['label']) ?></a></li>
                <?php endforeach; ?>

                <li class="nav__item nav__item--more">
                    <button type="button" class="nav__link nav__link--more <?= in_array($currentPage, array_column($moreNavItems, 'page'), true) ? 'nav__link--active' : '' ?>">
                        More
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>

                    <ul class="nav__dropdown" aria-label="More navigation">
                        <?php foreach ($moreNavItems as $item): ?>
                        <li>
                            <a href="<?= url($item['page']) ?>" class="nav__dropdown-link <?= $currentPage === $item['page'] ? 'nav__dropdown-link--active' : '' ?>"><?= e($item['label']) ?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>
        </nav>

        <?php if (is_pub_logged_in()): ?>
        <div class="header__user-menu" id="userMenu">
            <button class="header__user-btn" onclick="document.getElementById('userDropdown').classList.toggle('header__dropdown--open')">
                <span class="header__user-avatar"><?= mb_substr(e($_SESSION['pub_user_name'] ?? 'U'), 0, 1) ?></span>
                <span class="header__user-name"><?= e($_SESSION['pub_user_name'] ?? 'User') ?></span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="header__dropdown" id="userDropdown">
                <a href="<?= url('community') ?>" class="header__dropdown-item">My Posts</a>
                <a href="<?= url('complaints') ?>" class="header__dropdown-item">My Forum</a>
                <?php if (($_SESSION['pub_user_role'] ?? '') === 'volunteer'): ?>
                <a href="<?= url('volunteers') ?>" class="header__dropdown-item">Volunteer Hub</a>
                <?php endif; ?>
                <div class="header__dropdown-divider"></div>
                <a href="actions/pub-logout.php" class="header__dropdown-item header__dropdown-item--danger">Logout</a>
            </div>
        </div>
        <?php else: ?>
        <a href="<?= url('membership') ?>" class="btn btn--red header__cta">Become a Member</a>
        <?php endif; ?>

        <button class="hamburger" id="hamburger" aria-label="Open menu">
            <span class="hamburger__line"></span>
            <span class="hamburger__line"></span>
            <span class="hamburger__line"></span>
        </button>
    </div>
</header>

<!-- MOBILE MENU -->
<div class="mobile-menu__overlay" id="menuOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu__header">
        <a href="<?= url('home') ?>" class="header__logo">
            <img src="assets/logo.jpg" alt="NCB Logo" class="header__logo-img" style="width:40px;height:40px;">
            <div class="header__logo-text">
                <span class="header__logo-name"><?= e($settings['site_name']) ?></span>
                <span class="header__logo-sub"><?= e($settings['site_short_name']) ?></span>
            </div>
        </a>
        <button class="mobile-menu__close" id="menuClose" aria-label="Close menu">&times;</button>
    </div>
    <nav>
        <ul class="mobile-menu__nav-list">
            <?php foreach ($primaryNavItems as $item): ?>
            <li><a href="<?= url($item['page']) ?>" class="mobile-menu__nav-link <?= $currentPage === $item['page'] ? 'mobile-menu__nav-link--active' : '' ?>"><?= e($item['label']) ?> <span>&rsaquo;</span></a></li>
            <?php endforeach; ?>
            <?php foreach ($moreNavItems as $item): ?>
            <li><a href="<?= url($item['page']) ?>" class="mobile-menu__nav-link <?= $currentPage === $item['page'] ? 'mobile-menu__nav-link--active' : '' ?>"><?= e($item['label']) ?> <span>&rsaquo;</span></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="mobile-menu__more-label">More</div>
    <a href="<?= url('committee') ?>" class="mobile-menu__more-item">
        <div class="mobile-menu__more-title">Executive Committee</div>
        <div class="mobile-menu__more-desc">Meet our elected officers and advisors</div>
    </a>
    <a href="<?= url('volunteers') ?>" class="mobile-menu__more-item">
        <div class="mobile-menu__more-title">Volunteers</div>
        <div class="mobile-menu__more-desc">Our community heroes and how to join</div>
    </a>
    <a href="<?= url('community') ?>" class="mobile-menu__more-item">
        <div class="mobile-menu__more-title">Community Board</div>
        <div class="mobile-menu__more-desc">Share updates and connect with members</div>
    </a>
    <a href="<?= url('complaints') ?>" class="mobile-menu__more-item">
        <div class="mobile-menu__more-title">Community Forum</div>
        <div class="mobile-menu__more-desc">Raise issues and find solutions together</div>
    </a>
    <a href="<?= url('services') ?>" class="mobile-menu__more-item">
        <div class="mobile-menu__more-title">Community Services</div>
        <div class="mobile-menu__more-desc">Emergency support, translation, legal aid</div>
    </a>
    <a href="<?= url('downloads') ?>" class="mobile-menu__more-item">
        <div class="mobile-menu__more-title">Downloads</div>
        <div class="mobile-menu__more-desc">Forms, reports, and reference documents</div>
    </a>
    <a href="<?= url('membership') ?>" class="btn btn--red btn--lg mobile-menu__cta">Become a Member</a>
    <?php if (is_pub_logged_in()): ?>
    <div style="padding:12px 24px;border-top:1px solid rgba(255,255,255,.1);margin-top:8px;">
        <div style="color:rgba(255,255,255,.5);font-size:.8rem;margin-bottom:8px;">Logged in as <strong style="color:#fff;">"><?= e($_SESSION['pub_user_name'] ?? '') ?></strong></div>
        <a href="actions/pub-logout.php" class="btn btn--outline btn--sm" style="width:100%;text-align:center;border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.7);">Logout</a>
    </div>
    <?php else: ?>
    <a href="<?= url('login') ?>" class="btn btn--outline btn--lg mobile-menu__cta" style="border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.8);">Login / Register</a>
    <?php endif; ?>
</div>

<!-- FLASH MESSAGE -->
<?php $flash = get_flash(); if ($flash): ?>
<div class="toast toast--<?= e($flash['type']) ?>" id="flashToast">
    <span><?= e($flash['message']) ?></span>
    <button onclick="this.parentElement.remove()" style="background:none;border:none;color:inherit;font-size:1.2rem;cursor:pointer;">&times;</button>
</div>
<?php endif; ?>