<?php
require_once __DIR__ . '/../includes/database.php';
$testimonials = getAllTestimonials();
?>
<section id="testimonials" class="section">
    <div class="testimonials-header">
        <h2><?php echo htmlspecialchars(getSiteContent('testimonials_heading', 'Client Testimonials')); ?></h2>
        <p><?php echo htmlspecialchars(getSiteContent('testimonials_intro', 'What clients say about working with me and the quality of my deliverables')); ?></p>
    </div>
    <div class="testimonials-grid">
        <?php foreach ($testimonials as $testimonial): ?>
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <div class="quote-icon">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 21C3 17.6863 5.68629 15 9 15C10.6569 15 12 13.6569 12 12C12 10.3431 10.6569 9 9 9C6.23858 9 4 11.2386 4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 21C12 17.6863 14.6863 15 18 15C19.6569 15 21 13.6569 21 12C21 10.3431 19.6569 9 18 9C15.2386 9 13 11.2386 13 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <p class="testimonial-text"><?php echo htmlspecialchars($testimonial['testimonial_text']); ?></p>
                    <div class="testimonial-author">
                        <div class="author-info">
                            <div class="author-name"><?php echo htmlspecialchars($testimonial['client_name']); ?></div>
                            <div class="author-title"><?php echo htmlspecialchars($testimonial['client_title']); ?></div>
                        </div>
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span class="star"><?php echo $i <= $testimonial['rating'] ? '★' : '☆'; ?></span>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section> 