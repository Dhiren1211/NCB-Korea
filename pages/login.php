<?php
/**
 * NCB Website - Public User Login Page
 */

// Redirect if already logged in
if (is_pub_logged_in()) {
    redirect(url('community'));
}

$flash = get_flash();
?>

<!-- LOGIN CARD -->
<section class="login-section">
    <div class="login-card fade-in">
        <!-- Logo -->
        <div class="login-card__logo">
            <img src="assets/logo.jpg" alt="NCB Logo">
        </div>

        <!-- Title -->
        <h1 class="login-card__title">Welcome Back</h1>
        <p class="login-card__subtitle">Sign in to your NCB community account</p>

        <!-- Flash Message -->
        <?php if ($flash): ?>
        <div class="toast toast--<?= e($flash['type']) ?>" style="margin-bottom:1.25rem;">
            <span><?= e($flash['message']) ?></span>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" action="actions/pub-login.php" class="login-card__form">
            <div class="form-group">
                <label class="form-label" for="login_email">Email Address</label>
                <input type="email" id="login_email" name="email" class="form-input" placeholder="your.email@example.com" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="login_password">Password</label>
                <input type="password" id="login_password" name="password" class="form-input" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <button type="submit" class="form-submit btn btn--red btn--lg" style="width:100%;">Login</button>
            </div>
        </form>

        <!-- Links -->
        <div class="login-card__links">
            <p>Don't have an account? <a href="<?= url('register') ?>">Register here</a></p>
            <a href="<?= url('home') ?>" class="login-card__back-link">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Back to Home
            </a>
        </div>
    </div>
</section>

<style>
/* Login Section */
.login-section {
    display: flex;
    justify-content: center;
    padding: 120px 1rem 3rem;
    min-height: 80vh;
}

/* Login Card */
.login-card {
    width: 100%;
    max-width: 440px;
    background: var(--color-white, #fff);
    border-radius: 24px;
    padding: 2.5rem 2.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.06);
    text-align: center;
}
.login-card__logo {
    margin-bottom: 1.5rem;
}
.login-card__logo img {
    height: 64px;
    width: auto;
    object-fit: contain;
}
.login-card__title {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--color-dark, #111827);
    margin: 0 0 0.35rem;
}
.login-card__subtitle {
    font-size: 0.9rem;
    color: var(--color-text-light, #6b7280);
    margin: 0 0 2rem;
}

/* Form */
.login-card__form {
    text-align: left;
}
.login-card__form .form-group {
    margin-bottom: 1.25rem;
}
.login-card__form .form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
    margin-bottom: 0.4rem;
}
.login-card__form .form-input {
    width: 100%;
    padding: 0.8rem 1rem;
    font-size: 0.9rem;
    font-family: inherit;
    color: var(--color-dark, #111827);
    background: var(--color-bg, #f9fafb);
    border: 1.5px solid var(--color-border, #e5e7eb);
    border-radius: 12px;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.login-card__form .form-input:focus {
    border-color: var(--color-primary, #e53935);
    box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.08);
}
.login-card__form .form-input::placeholder {
    color: var(--color-text-light, #9ca3af);
}
.login-card__form .form-submit {
    width: 100%;
    margin-top: 0.5rem;
}

/* Links */
.login-card__links {
    margin-top: 1.75rem;
    padding-top: 1.5rem;
    border-top: 1.5px solid var(--color-border, #e5e7eb);
}
.login-card__links p {
    font-size: 0.875rem;
    color: var(--color-text-light, #6b7280);
    margin: 0 0 1rem;
}
.login-card__links a {
    color: var(--color-primary, #e53935);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
}
.login-card__links a:hover {
    text-decoration: underline;
}
.login-card__back-link {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.825rem;
    color: var(--color-text-light, #6b7280) !important;
    font-weight: 500 !important;
}
.login-card__back-link:hover {
    color: var(--color-dark, #111827) !important;
    text-decoration: none !important;
}

@media (max-width: 480px) {
    .login-section {
        padding: 80px 0.75rem 2rem;
    }
    .login-card {
        padding: 2rem 1.5rem;
        border-radius: 20px;
    }
}
</style>