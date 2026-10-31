<?php
require_once __DIR__ . '/../includes/database.php';
$contactInfo = getContactInfo();
$contactIcons = [
    'Phone' => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M7.2 3.8 5.6 4.9a2.4 2.4 0 0 0-.9 2.8c1.8 5.2 5.5 8.9 10.7 10.7a2.4 2.4 0 0 0 2.8-.9l1.1-1.6a1.5 1.5 0 0 0-.3-2l-2.2-1.7a1.5 1.5 0 0 0-2 .1l-1 1a12.5 12.5 0 0 1-4.1-4.1l1-1a1.5 1.5 0 0 0 .1-2L9.2 4.1a1.5 1.5 0 0 0-2-.3Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>',
    'Email' => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.7"/><path d="m4.5 7 7.5 6 7.5-6" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>',
    'Location' => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M19 10.2c0 5.1-7 10.3-7 10.3S5 15.3 5 10.2a7 7 0 1 1 14 0Z" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="10" r="2.3" stroke="currentColor" stroke-width="1.7"/></svg>',
];
?>
<section id="contact" class="section">
    <div class="contact-header">
        <h2><?php echo htmlspecialchars(getSiteContent('contact_heading', 'Get In Touch')); ?></h2>
        <p><?php echo htmlspecialchars(getSiteContent('contact_intro', "Ready to start your next project? Let's discuss how I can help bring your ideas to life")); ?></p>
    </div>
    <div class="contact-container">
        <div class="contact-info">
            <h3>Contact Information</h3>
            <div class="contact-details">
                <?php foreach (['Phone', 'Email', 'Location'] as $type): ?>
                    <?php if (!empty($contactInfo[$type])): ?>
                    <div class="contact-item">
                        <div class="contact-icon"><?php echo $contactIcons[$type] ?? ''; ?></div>
                        <div class="contact-text"><h4><?php echo htmlspecialchars($type); ?></h4><p><?php echo htmlspecialchars($contactInfo[$type]); ?></p></div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="contact-form">
            <h3>Send Message</h3>
            <div class="form-status<?php echo !empty($contactFlash) ? (!empty($contactFlash['success']) ? ' success' : ' error') : ''; ?>" id="contact-form-status" role="status" aria-live="polite"><?php echo htmlspecialchars($contactFlash['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
            <form action="index.php#contact" method="POST" id="contact-form">
                <input type="hidden" name="contact_form" value="1">
                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['contact_csrf'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-group" aria-hidden="true" style="position:absolute;left:-10000px;width:1px;height:1px;overflow:hidden;"><label for="website">Website</label><input type="text" id="website" name="website" tabindex="-1" autocomplete="off"></div>
                <div class="form-group"><label for="contact-name" class="sr-only">Your Name</label><input type="text" id="contact-name" name="name" placeholder="Your Name" autocomplete="name" minlength="2" maxlength="120" required></div>
                <div class="form-group"><label for="contact-email" class="sr-only">Your Email</label><input type="email" id="contact-email" name="email" placeholder="Your Email" autocomplete="email" maxlength="190" required></div>
                <div class="form-group"><label for="contact-subject" class="sr-only">Subject</label><input type="text" id="contact-subject" name="subject" placeholder="Subject" maxlength="200" required></div>
                <div class="form-group"><label for="contact-message" class="sr-only">Your Message</label><textarea id="contact-message" name="message" placeholder="Your Message" rows="5" minlength="10" maxlength="10000" required></textarea></div>
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </div>
</section>
