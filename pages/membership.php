<?php
/**
 * NCB Website - Membership Application Page
 */

// Get flash message
$flash = get_flash();

// Get membership stats
$statMembers = get_setting('stat_members', '450');
$statYears   = get_setting('stat_years', '12');
$statEvents  = get_setting('stat_events', '120');

// Benefits list (hardcoded)
$benefits = [
    'Free access to all NCB events and cultural celebrations',
    'Emergency support and crisis assistance network',
    'Legal aid and visa consultation referrals',
    'Korean language workshop discounts (up to 40%)',
    'Scholarship program eligibility for students',
    'Job notifications and employment referrals',
    'Translation assistance for official documents',
    'Community networking and social connections',
    'Voting rights in annual committee elections',
    'Monthly newsletter with community updates',
];

// FAQ items (hardcoded)
$faqs = [
    [
        'q' => 'Who can become an NCB member?',
        'a' => 'Membership is open to all Nepalese citizens living in Busan and surrounding areas, regardless of visa type, occupation, or length of stay in Korea.'
    ],
    [
        'q' => 'Is there a membership fee?',
        'a' => 'NCB membership is completely free. We believe that financial constraints should never be a barrier to being part of our community.'
    ],
    [
        'q' => 'How long does the application take?',
        'a' => 'Applications are typically reviewed within 3-5 business days. You will receive a confirmation email once your membership is approved.'
    ],
    [
        'q' => 'Can I update my information later?',
        'a' => 'Yes, you can contact our membership team at any time to update your contact details, address, or other information.'
    ],
    [
        'q' => 'What happens after I apply?',
        'a' => 'After approval, you will be added to our member database, receive our monthly newsletter, and get invited to all exclusive member events and activities.'
    ],
];
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">MEMBERSHIP</span>
            <h1 class="page-hero__title">Join Our Community</h1>
            <p class="page-hero__subtitle">Become a member of NCB and be part of a thriving Nepalese community in Busan. Membership is free and open to all.</p>
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

<!-- MEMBERSHIP SECTION -->
<section class="membership-section" id="membershipSection">
    <div class="container">
        <div class="membership-section__grid">

            <!-- LEFT COLUMN: APPLICATION FORM -->
            <div class="membership-section__form-col fade-in">
                <div class="membership-section__form-card">
                    <span class="section-badge">APPLY NOW</span>
                    <h2 class="section-title">Membership Application</h2>
                    <p class="membership-section__form-desc">Fill out the form below to apply for NCB membership. All fields marked with * are required.</p>

                    <form method="POST" action="actions/membership.php" class="membership-form">
                        <div class="form-group">
                            <label class="form-label" for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" class="form-input" placeholder="Enter your full name" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-input" placeholder="your.email@example.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+82-10-XXXX-XXXX" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="date_of_birth">Date of Birth</label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-input">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="address_in_nepal">Address in Nepal</label>
                            <input type="text" id="address_in_nepal" name="address_in_nepal" class="form-input" placeholder="City, District, Province">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="address_in_korea">Address in Korea</label>
                            <input type="text" id="address_in_korea" name="address_in_korea" class="form-input" placeholder="Your current address in Korea">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="visa_type">Visa Type</label>
                            <select id="visa_type" name="visa_type" class="form-select">
                                <option value="">-- Select Visa Type --</option>
                                <option value="D-2 Student">D-2 Student</option>
                                <option value="D-4 Language">D-4 Language</option>
                                <option value="E-2 Teaching">E-2 Teaching</option>
                                <option value="E-7 Engineering">E-7 Engineering</option>
                                <option value="E-9 EPS">E-9 EPS</option>
                                <option value="F-2 Residence">F-2 Residence</option>
                                <option value="F-5 Permanent">F-5 Permanent</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="occupation">Occupation</label>
                            <input type="text" id="occupation" name="occupation" class="form-input" placeholder="Your current occupation">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="university_company">University / Company</label>
                            <input type="text" id="university_company" name="university_company" class="form-input" placeholder="Name of university or employer">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="reason_for_joining">Reason for Joining *</label>
                            <textarea id="reason_for_joining" name="reason_for_joining" class="form-textarea" rows="10" placeholder="Tell us why you would like to join the Nepalese Community in Busan..." required></textarea>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="form-submit btn btn--red btn--lg">Submit Application &rarr;</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN: BENEFITS, STATS, FAQ -->
            <div class="membership-section__info-col">

                <!-- MEMBERSHIP STATS -->
                <div class="membership-section__stats fade-in">
                    <div class="membership-section__stats-grid">
                        <div class="membership-section__stat-item">
                            <div class="membership-section__stat-number"><?= e($statMembers) ?>+</div>
                            <div class="membership-section__stat-label">Active Members</div>
                        </div>
                        <div class="membership-section__stat-item">
                            <div class="membership-section__stat-number"><?= e($statEvents) ?>+</div>
                            <div class="membership-section__stat-label">Events Per Year</div>
                        </div>
                        <div class="membership-section__stat-item">
                            <div class="membership-section__stat-number"><?= e($statYears) ?></div>
                            <div class="membership-section__stat-label">Years Serving</div>
                        </div>
                    </div>
                </div>

                <!-- BENEFITS -->
                <div class="membership-section__benefits fade-in">
                    <span class="section-badge">WHY JOIN</span>
                    <h3 class="section-title">Membership Benefits</h3>
                    <ul class="membership-section__benefits-list">
                        <?php foreach ($benefits as $benefit): ?>
                        <li class="membership-section__benefit-item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span><?= e($benefit) ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- FAQ -->
                <div class="membership-section__faq fade-in">
                    <span class="section-badge">QUESTIONS</span>
                    <h3 class="section-title">Frequently Asked Questions</h3>
                    <div class="membership-section__faq-list">
                        <?php foreach ($faqs as $i => $faq): ?>
                        <div class="membership-section__faq-item">
                            <button class="membership-section__faq-question" type="button" onclick="this.parentElement.classList.toggle('membership-section__faq-item--open')">
                                <span><?= e($faq['q']) ?></span>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="membership-section__faq-answer">
                                <p><?= e($faq['a']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
/* Membership Section Grid */
.membership-section__grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 2.5rem;
    align-items: start;
}
@media (max-width: 1024px) {
    .membership-section__grid {
        grid-template-columns: 1fr;
    }
}

/* Form Card */
.membership-section__form-card {
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 2rem 2.25rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
}
.membership-section__form-desc {
    margin-top: 0.5rem;
    font-size: 0.925rem;
    color: var(--color-text-light, #6b7280);
    line-height: 1.6;
}
.membership-section__form-col .section-title {
    margin-bottom: 0.25rem;
}

/* Form Styles */
.membership-form .form-group {
    margin-bottom: 1.25rem;
}
.membership-form .form-label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
    margin-bottom: 0.4rem;
}
.membership-form .form-input,
.membership-form .form-textarea,
.membership-form .form-select {
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
.membership-form .form-input:focus,
.membership-form .form-textarea:focus,
.membership-form .form-select:focus {
    border-color: var(--color-primary, #dc2626);
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
}
.membership-form .form-input::placeholder,
.membership-form .form-textarea::placeholder {
    color: var(--color-text-light, #9ca3af);
}
.membership-form .form-textarea {
    resize: vertical;
    min-height: 120px;
}
.membership-form .form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' viewBox='0 0 12 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1.5L6 6.5L11 1.5' stroke='%236b7280' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
}
.membership-form .form-submit {
    width: 100%;
    margin-top: 0.5rem;
}

/* Stats */
.membership-section__stats {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    border-radius: 20px;
    padding: 1.75rem;
    color: #fff;
    margin-bottom: 1.5rem;
}
.membership-section__stats-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    text-align: center;
}
.membership-section__stat-number {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.2;
}
.membership-section__stat-label {
    font-size: 0.8rem;
    opacity: 0.85;
    margin-top: 0.25rem;
    font-weight: 500;
}

/* Benefits */
.membership-section__benefits {
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 1.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    margin-bottom: 1.5rem;
}
.membership-section__benefits .section-title {
    margin-top: 0.5rem;
    margin-bottom: 1rem;
    font-size: 1.25rem;
}
.membership-section__benefits-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 0.65rem;
}
.membership-section__benefit-item {
    display: flex;
    align-items: flex-start;
    gap: 0.6rem;
    font-size: 0.9rem;
    color: var(--color-text, #374151);
    line-height: 1.5;
}
.membership-section__benefit-item svg {
    flex-shrink: 0;
    margin-top: 2px;
    color: var(--color-primary, #dc2626);
}

/* FAQ */
.membership-section__faq {
    background: var(--color-white, #fff);
    border-radius: 20px;
    padding: 1.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
}
.membership-section__faq .section-title {
    margin-top: 0.5rem;
    margin-bottom: 1rem;
    font-size: 1.25rem;
}
.membership-section__faq-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.membership-section__faq-item {
    border: 1.5px solid var(--color-border, #e5e7eb);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.2s ease;
}
.membership-section__faq-item--open {
    border-color: var(--color-primary, #dc2626);
}
.membership-section__faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 0.85rem 1rem;
    background: none;
    border: none;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--color-dark, #111827);
    cursor: pointer;
    text-align: left;
    font-family: inherit;
}
.membership-section__faq-question svg {
    flex-shrink: 0;
    color: var(--color-text-light, #6b7280);
    transition: transform 0.25s ease;
}
.membership-section__faq-item--open .membership-section__faq-question svg {
    transform: rotate(180deg);
    color: var(--color-primary, #dc2626);
}
.membership-section__faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
}
.membership-section__faq-item--open .membership-section__faq-answer {
    max-height: 300px;
    padding: 0 1rem 0.85rem;
}
.membership-section__faq-answer p {
    font-size: 0.875rem;
    color: var(--color-text, #374151);
    line-height: 1.65;
    margin: 0;
}
</style>
