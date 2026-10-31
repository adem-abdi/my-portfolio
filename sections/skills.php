<?php
require_once __DIR__ . '/../includes/database.php';
$skills = getAllSkills();
$skillsByCategory = [];
foreach ($skills as $skill) {
    $category = $skill['category'];
    if (!isset($skillsByCategory[$category])) {
        $skillsByCategory[$category] = [];
    }
    $skillsByCategory[$category][] = $skill;
}
?>
<section id="skills" class="section">
    <div class="skills-header">
        <h2><?php echo htmlspecialchars(getSiteContent('skills_heading', 'Technical Skills')); ?></h2>
        <p><?php echo htmlspecialchars(getSiteContent('skills_intro', 'My expertise across different technology stacks and development domains')); ?></p>
    </div>
    <div class="skills-container">
        <?php foreach ($skillsByCategory as $category => $categorySkills): ?>
            <div class="skills-category">
                <h3><?php echo htmlspecialchars($category); ?></h3>
                <div class="skills-grid">
                    <?php foreach ($categorySkills as $skill): ?>
                        <div class="skill-item">
                            <div class="skill-info">
                                <span class="skill-name"><?php echo htmlspecialchars($skill['name']); ?></span>
                                <span class="skill-percentage"><?php echo htmlspecialchars($skill['percentage']); ?>%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-percentage="<?php echo htmlspecialchars($skill['percentage']); ?>"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const skillBars = document.querySelectorAll('.skill-progress');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const percentage = entry.target.getAttribute('data-percentage');
                entry.target.style.width = percentage + '%';
                entry.target.style.opacity = '1';
            }
        });
    }, { threshold: 0.5 });
    
    skillBars.forEach(bar => {
        observer.observe(bar);
    });
});
</script> 