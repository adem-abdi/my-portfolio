<?php
require_once __DIR__ . '/../includes/database.php';
$projects = getAllProjects();
?>
<section id="projects" class="section">
    <div class="projects-header">
        <h2><?php echo htmlspecialchars(getSiteContent('projects_heading', 'Featured Projects')); ?></h2>
        <p><?php echo htmlspecialchars(getSiteContent('projects_intro', 'Showcasing my latest work and technical expertise across various domains')); ?></p>
    </div>
    <div class="projects-grid">
        <?php foreach ($projects as $project): ?>
            <?php $image = !empty($project['image_path']) ? $project['image_path'] : 'assets/images/mypic.png'; ?>
            <div class="project-card <?php echo !empty($project['featured']) ? 'featured' : ''; ?>">
                <div class="project-image"><img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($project['title']); ?>" loading="lazy"></div>
                <div class="project-content">
                    <h3 class="project-title"><?php echo htmlspecialchars($project['title']); ?></h3>
                    <p class="project-description"><?php echo htmlspecialchars($project['description']); ?></p>
                    <?php if (!empty($project['tech_stack'])): ?><div class="project-date"><?php echo htmlspecialchars($project['tech_stack']); ?></div><?php endif; ?>
                    <div class="project-links">
                        <?php if (!empty($project['github_link'])): ?><a href="<?php echo htmlspecialchars($project['github_link']); ?>" class="project-link" target="_blank" rel="noopener noreferrer" title="View on GitHub"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 19c-4 1.2-4-2-5.5-2.5M14.5 21v-3.4c0-1 .1-1.4-.5-2.1 2.8-.3 5.7-1.4 5.7-6.1 0-1.3-.5-2.4-1.3-3.3.1-.3.6-1.6-.1-3.2 0 0-1-.3-3.4 1.3a11.8 11.8 0 0 0-6.2 0C6.3 2.6 5.3 3 5.3 3c-.7 1.6-.2 2.9-.1 3.2-.8.9-1.3 2-1.3 3.3 0 4.7 2.9 5.8 5.7 6.1-.6.5-.6 1.2-.5 2.1V21" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg><span class="sr-only">View on GitHub</span></a><?php endif; ?>
                        <?php if (!empty($project['demo_link'])): ?><a href="<?php echo htmlspecialchars($project['demo_link']); ?>" class="project-link" target="_blank" rel="noopener noreferrer" title="View Demo"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M14 5h5v5M19 5l-9 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M18 13v5a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg><span class="sr-only">View Demo</span></a><?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
