<?php
require_once __DIR__ . '/../includes/database.php';
$socialLinks = getSocialLinks();
$contactInfo = getContactInfo();

function renderSocialIcon($platform) {
    $icons = [
        'Instagram' => '<svg class="social-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.2" y="3.2" width="17.6" height="17.6" rx="5" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="12" r="4.1" stroke="currentColor" stroke-width="1.8"/><circle cx="17.5" cy="6.7" r="1.1" fill="currentColor"/></svg>',
        'TikTok' => '<svg class="social-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14.1 4v10.1a4.2 4.2 0 1 1-3.1-4.06" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14.1 4c.45 2.35 1.8 3.8 4.2 4.18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        'LinkedIn' => '<svg class="social-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3.2" y="3.2" width="17.6" height="17.6" rx="3" stroke="currentColor" stroke-width="1.8"/><path d="M7.5 10.2v6.3M7.5 7.5v.1M11.2 16.5v-3.4a2.7 2.7 0 0 1 5.4 0v3.4M11.2 10.2v6.3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>',
        'WhatsApp' => '<svg class="social-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.2 11.4a8.2 8.2 0 0 1-12.1 7.2L3.5 20l1.4-4.3a8.2 8.2 0 1 1 15.3-4.3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M8.4 8.2c.2-.4.5-.4.8-.4h.5c.2 0 .4.1.5.4l.7 1.7c.1.3.1.5-.1.7l-.6.7c.6 1.1 1.5 2 2.6 2.6l.7-.6c.2-.2.4-.2.7-.1l1.7.7c.3.1.4.3.4.5v.5c0 .3 0 .6-.4.8-.4.2-1.3.4-2.8-.3-1.5-.7-3.5-2.5-4.4-3.8-.9-1.3-1.4-2.7-1-3.4Z" fill="currentColor"/></svg>',
    ];
    return $icons[$platform] ?? '<svg class="social-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
}
?>
<footer class="footer">
    <div class="footer-container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Adem Abdifith</h3>
                <p><?php echo htmlspecialchars(getSiteContent('footer_about', "Full Stack Developer passionate about creating innovative digital solutions. Let's work together to bring your ideas to life.")); ?></p>
                <div class="social-card">
                    <?php $socialClasses = ['Instagram' => 'containerOne', 'TikTok' => 'containerTwo', 'LinkedIn' => 'containerThree', 'WhatsApp' => 'containerFour']; ?>
                    <?php foreach ($socialClasses as $platform => $class): ?>
                        <?php if (!empty($socialLinks[$platform]['url'])): ?>
                            <a href="<?php echo htmlspecialchars($socialLinks[$platform]['url']); ?>" class="socialContainer <?php echo $class; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo htmlspecialchars($platform); ?>">
                                <?php echo renderSocialIcon($platform); ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="footer-section"><h4>Quick Links</h4><ul class="footer-links"><li><a href="#hero">Home</a></li><li><a href="#services">Services</a></li><li><a href="#projects">Projects</a></li><li><a href="#skills">Skills</a></li><li><a href="#testimonials">Testimonials</a></li><li><a href="#contact">Contact</a></li></ul></div>
            <div class="footer-section"><h4>Services</h4><ul class="footer-links"><li><a href="#services">Web Development</a></li><li><a href="#services">Mobile Development</a></li><li><a href="#services">UI/UX Design</a></li><li><a href="#services">Database Design</a></li><li><a href="#services">API Development</a></li></ul></div>
            <div class="footer-section"><h4>Contact Info</h4><p><?php echo htmlspecialchars($contactInfo['Email'] ?? ''); ?></p><p><?php echo htmlspecialchars($contactInfo['Phone'] ?? ''); ?></p><p><?php echo htmlspecialchars($contactInfo['Location'] ?? ''); ?></p></div>
        </div>
        <div class="footer-bottom"><div class="footer-divider"></div><div class="footer-bottom-content"><p>&copy; <?php echo date('Y'); ?> Adem Abdifith. All rights reserved.</p></div></div>
    </div>
</footer>
