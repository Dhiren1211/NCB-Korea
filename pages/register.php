<?php
/**
 * NCB Website - Public User Registration Page
 */

// Redirect if already logged in
if (is_pub_logged_in()) {
    redirect(url('community'));
}

$flash = get_flash();
?>

<!-- REGISTER CARD -->
<section class="register-section">
    <div class="register-card fade-in">
        <!-- Title -->
        <h1 class="register-card__title">Join NCB Community</h1>
        <p class="register-card__subtitle">Create your account to connect with the Nepalese community in Busan.</p>

        <!-- Flash Message -->
        <?php if ($flash): ?>
        <div class="toast toast--<?= e($flash['type']) ?>" style="margin-bottom:1.25rem;">
            <span><?= e($flash['message']) ?></span>
        </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form method="POST" action="actions/pub-register.php" class="register-card__form">
            <!-- Row 1: Full Name + Username -->
            <div class="register-card__row">
                <div class="form-group">
                    <label class="form-label" for="reg_name">Full Name *</label>
                    <input type="text" id="reg_name" name="full_name" class="form-input" placeholder="Your full name" required autofocus>
                </div>
                <div class="form-group">
                    <label class="form-label" for="reg_username">Username *</label>
                    <input type="text" id="reg_username" name="username" class="form-input" placeholder="Choose a username" required minlength="3" maxlength="30" pattern="[a-zA-Z0-9_]+" title="Only letters, numbers, and underscores">
                </div>
            </div>

            <!-- Row 2: Email + Phone -->
            <div class="register-card__row">
                <div class="form-group">
                    <label class="form-label" for="reg_email">Email Address *</label>
                    <input type="email" id="reg_email" name="email" class="form-input" placeholder="your.email@example.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="reg_phone">Phone Number</label>
                    <input type="tel" id="reg_phone" name="phone" class="form-input" placeholder="+82-10-XXXX-XXXX">
                </div>
            </div>

            <!-- Row 3: Password + Confirm -->
            <div class="register-card__row">
                <div class="form-group">
                    <label class="form-label" for="reg_password">Password *</label>
                    <input type="password" id="reg_password" name="password" class="form-input" placeholder="Min 6 characters" required minlength="6">
                </div>
                <div class="form-group">
                    <label class="form-label" for="reg_confirm">Confirm Password *</label>
                    <input type="password" id="reg_confirm" name="confirm_password" class="form-input" placeholder="Re-enter password" required minlength="6">
                </div>
            </div>

            <!-- Role Selection -->
            <div class="form-group">
                <label class="form-label">Role</label>
                <div class="register-card__role-options">
                    <label class="register-card__role-option">
                        <input type="radio" name="role" value="member" checked>
                        <span class="register-card__role-radio"></span>
                        <div class="register-card__role-info">
                            <span class="register-card__role-name">Member</span>
                            <span class="register-card__role-desc">Join and participate in the community</span>
                        </div>
                    </label>
                    <label class="register-card__role-option">
                        <input type="radio" name="role" value="volunteer">
                        <span class="register-card__role-radio"></span>
                        <div class="register-card__role-info">
                            <span class="register-card__role-name">Volunteer</span>
                            <span class="register-card__role-desc">Actively help organize and run events</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Terms Checkbox -->
            <div class="form-group">
                <label class="register-card__terms">
                    <input type="checkbox" name="agree_terms" required>
                    <span class="register-card__terms-check"></span>
                    <span>I agree to the <a href="#" target="_blank">community guidelines</a> and <a href="#" target="_blank">terms of service</a></span>
                </label>
            </div>

            <!-- Submit -->
            <div class="form-group">
                <button type="submit" class="form-submit btn btn--red btn--lg" style="width:100%;">Create Account &rarr;</button>
            </div>
        </form>

        <!-- Links -->
        <div class="register-card__links">
            <p>Already have an account? <a href="<?= url('login') ?>">Login here</a></p>
        </div>
    </div>
</section>

<style>
/* Register Section */
.register-section {
    display: flex;
    justify-content: center;
    padding: 100px 1rem 3rem;
    min-height: 80vh;
}

/* Register Card */
.register-card {
    width: 100%;
    max-width: 500px;
    background: var(--color-white, #fff);
    border-radius: 24px;
    padding: 2.5rem 2.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.06);
    text-align: center;
}
.register-card__title {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--color-dark, #111827);
    margin: 0 0 0.35rem;
}
.register-card__subtitle {
    font-size: 0.9rem;
    color: var(--color-text-light, #6b7280);
    margin: 0 0 2rem;
    line-height: 1.5;
}

/* Form */
.register-card__form {
    text-align: left;
}
.register-card__form .form-group {
    margin-bottom: 1.15rem;
}
.register-card__form .form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
    margin-bottom: 0.4rem;
}
.register-card__form .form-input {
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
.register-card__form .form-input:focus {
    border-color: var(--color-primary, #e53935);
    box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.08);
}
.register-card__form .form-input::placeholder {
    color: var(--color-text-light, #9ca3af);
}
.register-card__form .form-submit {
    width: 100%;
    margin-top: 0.5rem;
}

/* Two-column Row */
.register-card__row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

/* Role Options */
.register-card__role-options {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}
.register-card__role-option {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    padding: 0.85rem 0.9rem;
    background: var(--color-bg, #f9fafb);
    border: 1.5px solid var(--color-border, #e5e7eb);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.register-card__role-option:hover {
    border-color: var(--color-text-light, #9ca3af);
}
.register-card__role-option:has(input:checked) {
    border-color: var(--color-primary, #e53935);
    background: var(--color-primary-light, #fef2f2);
}
.register-card__role-option input[type="radio"] {
    display: none;
}
.register-card__role-radio {
    flex-shrink: 0;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 2px solid var(--color-border, #d1d5db);
    position: relative;
    margin-top: 2px;
    transition: border-color 0.2s ease;
}
.register-card__role-option:has(input:checked) .register-card__role-radio {
    border-color: var(--color-primary, #e53935);
}
.register-card__role-option:has(input:checked) .register-card__role-radio::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: var(--color-primary, #e53935);
}
.register-card__role-info {
    display: flex;
    flex-direction: column;
    gap: 0.1rem;
}
.register-card__role-name {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
}
.register-card__role-desc {
    font-size: 0.75rem;
    color: var(--color-text-light, #6b7280);
    line-height: 1.35;
}

/* Terms Checkbox */
.register-card__terms {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    cursor: pointer;
    font-size: 0.85rem;
    color: var(--color-text, #374151);
    line-height: 1.45;
}
.register-card__terms input[type="checkbox"] {
    display: none;
}
.register-card__terms-check {
    flex-shrink: 0;
    width: 18px;
    height: 18px;
    border-radius: 5px;
    border: 1.5px solid var(--color-border, #d1d5db);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1px;
    transition: all 0.2s ease;
}
.register-card__terms:has(input:checked) .register-card__terms-check {
    background: var(--color-primary, #e53935);
    border-color: var(--color-primary, #e53935);
}
.register-card__terms:has(input:checked) .register-card__terms-check::after {
    content: '';
    display: block;
    width: 5px;
    height: 9px;
    border: solid #fff;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg) translateY(-1px);
}
.register-card__terms a {
    color: var(--color-primary, #e53935);
    font-weight: 600;
    text-decoration: none;
}
.register-card__terms a:hover {
    text-decoration: underline;
}

/* Links */
.register-card__links {
    margin-top: 1.75rem;
    padding-top: 1.5rem;
    border-top: 1.5px solid var(--color-border, #e5e7eb);
}
.register-card__links p {
    font-size: 0.875rem;
    color: var(--color-text-light, #6b7280);
    margin: 0;
}
.register-card__links a {
    color: var(--color-primary, #e53935);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s ease;
}
.register-card__links a:hover {
    text-decoration: underline;
}

@media (max-width: 640px) {
    .register-section {
        padding: 60px 0.75rem 2rem;
    }
    .register-card {
        padding: 2rem 1.5rem;
        border-radius: 20px;
    }
    .register-card__row {
        grid-template-columns: 1fr;
        gap: 0;
    }
    .register-card__role-options {
        grid-template-columns: 1fr;
    }
}
</style>