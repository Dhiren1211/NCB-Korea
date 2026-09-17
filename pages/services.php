<?php
/**
 * NCB Website - Services Detail Page
 */

// Hardcoded service data
$services = [
    [
        'id'      => 'community-support',
        'icon'    => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>',
        'iconClass' => 'services-detail__header-icon--support',
        'title'   => 'Community Support',
        'description' => 'We provide comprehensive support to help Nepalese residents navigate life in Busan. From the moment you arrive to your everyday challenges, NCB is here to ensure no one faces difficulties alone.',
        'features' => [
            [
                'title'       => 'Airport Pickup',
                'description' => 'New arrivals can request airport pickup from Gimhae International Airport. Our volunteers will greet you and help you get settled in Busan.'
            ],
            [
                'title'       => 'Emergency Assistance',
                'description' => '24/7 emergency hotline for medical emergencies, accidents, legal crises, and other urgent situations affecting our community members.'
            ],
            [
                'title'       => 'Hospital Visits',
                'description' => 'Volunteers visit hospitalized community members, provide translation support during medical consultations, and assist with hospital procedures.'
            ],
            [
                'title'       => 'Translation Help',
                'description' => 'Volunteer translators available for Nepali-Korean-English translation of official documents, medical reports, and important communications.'
            ],
            [
                'title'       => 'Temporary Housing',
                'description' => 'Short-term housing assistance for new arrivals and community members facing temporary displacement, coordinated through our volunteer network.'
            ],
            [
                'title'       => 'Legal Aid Referral',
                'description' => 'Connections to legal professionals and free consultation clinics for immigration issues, employment disputes, and other legal matters.'
            ],
        ],
    ],
    [
        'id'      => 'cultural-promotion',
        'icon'    => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
        'iconClass' => 'services-detail__header-icon--culture',
        'title'   => 'Cultural Promotion',
        'description' => 'Preserving and sharing our rich Nepali cultural heritage is at the heart of NCB. Through festivals, classes, and exchange programs, we keep our traditions alive while building bridges with the broader community.',
        'features' => [
            [
                'title'       => 'Dashain',
                'description' => 'Our flagship annual celebration featuring cultural programs, traditional music, Deusi-Bhailo performances, authentic Nepali food, and community gatherings.'
            ],
            [
                'title'       => 'Tihar',
                'description' => 'The Festival of Lights celebrated with candle lighting, colorful mandalas, Laxmi Puja, Deusi-Bhailo songs, sel roti, and rangoli art competitions.'
            ],
            [
                'title'       => 'Nepali New Year',
                'description' => 'Annual celebration of Nepal\'s New Year (Naya Barsha) with cultural performances, food festivals, and community festivities.'
            ],
            [
                'title'       => 'Dance & Music',
                'description' => 'Regular cultural programs featuring traditional Nepali dance (Deuda, Dhime Baja), modern Nepali music, and performances by local artists.'
            ],
            [
                'title'       => 'Children\'s Cultural Class',
                'description' => 'Weekly classes teaching Nepali language, dance, music, and cultural traditions to second-generation Nepali children growing up in Korea.'
            ],
            [
                'title'       => 'Cultural Exchange',
                'description' => 'Joint events with Korean and other international communities to promote multicultural understanding and share Nepali heritage with Busan residents.'
            ],
        ],
    ],
    [
        'id'      => 'information-sharing',
        'icon'    => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
        'iconClass' => 'services-detail__header-icon--info',
        'title'   => 'Information Sharing',
        'description' => 'Staying informed is crucial for success in Korea. NCB serves as a central hub for important updates, resources, and knowledge sharing that helps our community members make informed decisions.',
        'features' => [
            [
                'title'       => 'Visa Updates',
                'description' => 'Regular updates on Korean immigration policies, visa regulation changes, renewal procedures, and permanent residency pathway information.'
            ],
            [
                'title'       => 'Job Notifications',
                'description' => 'Job postings and employment opportunities shared through our community channels, including Korean language requirements and workplace guidance.'
            ],
            [
                'title'       => 'Scholarship Info',
                'description' => 'Information about scholarships for Nepalese students, including NCB\'s own scholarship program and external funding opportunities.'
            ],
            [
                'title'       => 'Korean Language Classes',
                'description' => 'Coordinated Korean language workshops at beginner, intermediate, and advanced levels, including partnerships with university language centers.'
            ],
            [
                'title'       => 'Q&A Sessions',
                'description' => 'Regular question-and-answer sessions with immigration lawyers, university advisors, employment consultants, and other subject matter experts.'
            ],
            [
                'title'       => 'Newsletter',
                'description' => 'Monthly digital newsletter featuring community news, upcoming events, useful tips, success stories, and important announcements.'
            ],
        ],
    ],
];
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <div class="page-hero__content fade-in">
            <span class="page-hero__badge">WHAT WE DO</span>
            <h1 class="page-hero__title">Community Services</h1>
            <p class="page-hero__subtitle">NCB provides essential support services to help Nepalese residents thrive in Busan and South Korea.</p>
        </div>
    </div>
</section>

<!-- SERVICES DETAIL -->
<?php foreach ($services as $service): ?>
<section class="services-detail" id="<?= e($service['id']) ?>">
    <div class="container">
        <!-- Service Header -->
        <div class="services-detail__header fade-in">
            <div class="services-detail__header-icon <?= e($service['iconClass']) ?>">
                <?= $service['icon'] ?>
            </div>
            <div class="services-detail__header-text">
                <span class="section-badge"><?= e(strtoupper($service['title'])) ?></span>
                <h2 class="section-title"><?= e($service['title']) ?></h2>
                <p class="services-detail__description"><?= e($service['description']) ?></p>
            </div>
        </div>

        <!-- Feature Cards Grid -->
        <div class="services-detail__grid">
            <?php foreach ($service['features'] as $feature): ?>
            <div class="services-detail__card fade-in">
                <div class="services-detail__card-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                </div>
                <h4 class="services-detail__card-title"><?= e($feature['title']) ?></h4>
                <p class="services-detail__card-text"><?= e($feature['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endforeach; ?>

<!-- CTA SECTION -->
<section class="services-cta" id="servicesCta">
    <div class="container">
        <div class="services-cta__card fade-in">
            <h2 class="services-cta__title">Need Help? Don't Hesitate to Reach Out</h2>
            <p class="services-cta__text">Our community support network is available to assist you. Whether you need emergency help, translation support, or just want to connect with fellow Nepalese in Busan.</p>
            <div class="services-cta__buttons">
                <a href="<?= url('contact') ?>" class="btn btn--red btn--lg">Contact Us &rarr;</a>
                <a href="<?= url('membership') ?>" class="btn btn--outline btn--lg">Join NCB</a>
            </div>
        </div>
    </div>
</section>

<style>
/* Services Detail */
.services-detail {
    padding: 4rem 0;
}
.services-detail:nth-child(even) {
    background: var(--color-bg, #f9fafb);
}

/* Service Header */
.services-detail__header {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    margin-bottom: 2.5rem;
}
.services-detail__header-icon {
    flex-shrink: 0;
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.services-detail__header-icon--support {
    background: rgba(220, 38, 38, 0.1);
    color: #dc2626;
}
.services-detail__header-icon--culture {
    background: rgba(245, 158, 11, 0.1);
    color: #f59e0b;
}
.services-detail__header-icon--info {
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}
.services-detail__header-text {
    flex: 1;
}
.services-detail__description {
    font-size: 1rem;
    color: var(--color-text, #374151);
    line-height: 1.7;
    margin-top: 0.5rem;
    max-width: 700px;
}

/* Feature Cards Grid */
.services-detail__grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}
@media (max-width: 1024px) {
    .services-detail__grid {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width: 640px) {
    .services-detail__grid {
        grid-template-columns: 1fr;
    }
    .services-detail__header {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .services-detail__description {
        max-width: 100%;
    }
}

/* Feature Card */
.services-detail__card {
    background: var(--color-white, #fff);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 16px rgba(0,0,0,0.04);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.services-detail:nth-child(even) .services-detail__card {
    background: var(--color-white, #fff);
}
.services-detail__card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.1);
}
.services-detail__card-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: var(--color-primary-light, #fef2f2);
    color: var(--color-primary, #dc2626);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}
.services-detail__card-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--color-dark, #111827);
    margin: 0 0 0.5rem;
}
.services-detail__card-text {
    font-size: 0.875rem;
    color: var(--color-text, #374151);
    line-height: 1.65;
    margin: 0;
}

/* CTA Section */
.services-cta {
    padding: 4rem 0;
}
.services-cta__card {
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    border-radius: 24px;
    padding: 3rem;
    text-align: center;
    color: #fff;
}
.services-cta__title {
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0 0 0.75rem;
}
.services-cta__text {
    font-size: 1rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto 1.75rem;
    line-height: 1.7;
}
.services-cta__buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}
.services-cta__buttons .btn--outline {
    border-color: rgba(255,255,255,0.4);
    color: #fff;
}
.services-cta__buttons .btn--outline:hover {
    background: rgba(255,255,255,0.1);
    border-color: rgba(255,255,255,0.7);
}
@media (max-width: 640px) {
    .services-cta__card {
        padding: 2rem 1.5rem;
    }
    .services-cta__title {
        font-size: 1.35rem;
    }
}
</style>
