<?php
require_once __DIR__ . '/../includes/database.php';
$services = getAllServices();
$serviceIcons = [
    '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2 2 7l10 5 10-5-10-5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="m2 12 10 5 10-5M2 17l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="2" width="14" height="20" rx="2" stroke="currentColor" stroke-width="2"/><path d="M10 18h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
    '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/><path d="M12 1v2m0 18v2M4.2 4.2l1.4 1.4m12.8 12.8 1.4 1.4M1 12h2m18 0h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
    '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="12" cy="5" rx="9" ry="3" stroke="currentColor" stroke-width="2"/><path d="M3 5v14c0 1.7 4 3 9 3s9-1.3 9-3V5M3 12c0 1.7 4 3 9 3s9-1.3 9-3" stroke="currentColor" stroke-width="2"/></svg>',
    '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 13a5 5 0 0 0 7.5.5l3-3a5 5 0 0 0-7-7l-1.7 1.7M14 11a5 5 0 0 0-7.5-.5l-3 3a5 5 0 0 0 7 7l1.7-1.7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
];
?>
<section id="services" class="section">
    <div class="services-header">
        <h2><?php echo htmlspecialchars(getSiteContent('services_heading', 'My Services')); ?></h2>
        <p><?php echo htmlspecialchars(getSiteContent('services_intro', 'Comprehensive web development solutions tailored to your business needs')); ?></p>
    </div>
    <div class="services-grid">
        <?php foreach ($services as $index => $service): ?>
            <div class="service-card <?php echo !empty($service['highlighted']) ? 'highlighted' : ''; ?>">
                <div class="service-icon"><?php echo !empty($service['icon_svg']) ? $service['icon_svg'] : ($serviceIcons[$index % count($serviceIcons)]); ?></div>
                <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                <p><?php echo htmlspecialchars($service['description']); ?></p>
                <a href="#contact" class="learn-more">Learn More →</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
