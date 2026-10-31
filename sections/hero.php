<?php
require_once __DIR__ . '/../includes/database.php';

$heroImage = getSiteContent('hero_image', 'assets/images/mypic.png');
if (!$heroImage || (!preg_match('#^https?://#i', $heroImage) && !is_file(__DIR__ . '/../' . ltrim($heroImage, '/')))) {
    $heroImage = 'assets/images/mypic.png';
}
?>
<section class="hero" id="hero">
    <div class="hero-container">
        <div class="hero-content">
            <div class="hero-text">
                <h1><?php echo htmlspecialchars(getSiteContent('hero_title', 'Adem Abdifith')); ?></h1>
                <h2><?php echo htmlspecialchars(getSiteContent('hero_subtitle', 'Full Stack Developer')); ?></h2>
                <p><?php echo htmlspecialchars(getSiteContent('hero_description', 'Passionate developer crafting digital experiences with modern technologies. Specializing in web development, mobile apps, and innovative solutions that drive business growth.')); ?></p>
            </div>
            <div class="hero-cta">
                <a href="#contact" class="btn-primary"><?php echo htmlspecialchars(getSiteContent('cta_primary_text', 'Get In Touch')); ?></a>
                <a href="#projects" class="btn-secondary"><?php echo htmlspecialchars(getSiteContent('cta_secondary_text', 'View My Work')); ?></a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-number"><?php echo htmlspecialchars(getSiteContentAny(['stat_number_1', 'stat1_number'], '50+')); ?></div>
                    <div class="stat-label"><?php echo htmlspecialchars(getSiteContentAny(['stat_label_1', 'stat1_label'], 'Projects Completed')); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo htmlspecialchars(getSiteContentAny(['stat_number_2', 'stat2_number'], '3+')); ?></div>
                    <div class="stat-label"><?php echo htmlspecialchars(getSiteContentAny(['stat_label_2', 'stat2_label'], 'Years Experience')); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo htmlspecialchars(getSiteContentAny(['stat_number_3', 'stat3_number'], '100%')); ?></div>
                    <div class="stat-label"><?php echo htmlspecialchars(getSiteContentAny(['stat_label_3', 'stat3_label'], 'Client Satisfaction')); ?></div>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <div class="image-container">
                <img id="hero-profile-image" src="<?php echo htmlspecialchars($heroImage); ?>" alt="Adem Abdifith">
                <div class="image-overlay">
                    <div class="tech-badge"><?php echo htmlspecialchars(getSiteContent('hero_badge_1', 'PHP')); ?></div>
                    <div class="tech-badge"><?php echo htmlspecialchars(getSiteContent('hero_badge_2', 'JavaScript')); ?></div>
                    <div class="tech-badge"><?php echo htmlspecialchars(getSiteContent('hero_badge_3', 'MySQL')); ?></div>
                </div>
            </div>
        </div>
    </div>
</section>
