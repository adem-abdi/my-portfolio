<?php
session_start();
require_once __DIR__ . '/../includes/database.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

if (empty($_SESSION['admin_csrf'])) {
    $_SESSION['admin_csrf'] = bin2hex(random_bytes(24));
}
$csrf = $_SESSION['admin_csrf'];
$username = $_SESSION['admin_username'] ?? 'Admin';
$userInitial = strtoupper(substr($username, 0, 1));
$flash = null;

function adminEscape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function adminPostString($value, $maxLength = 10000) {
    $value = trim((string) $value);
    return function_exists('mb_substr') ? mb_substr($value, 0, $maxLength) : substr($value, 0, $maxLength);
}

function adminUploadImage($field, $existing = '') {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        return $existing;
    }
    if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK || $_FILES[$field]['size'] > 8 * 1024 * 1024) {
        return $existing;
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES[$field]['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    if (!isset($allowed[$mime])) {
        return $existing;
    }

    $directory = __DIR__ . '/../assets/images/uploads';
    if (!is_dir($directory)) {
        @mkdir($directory, 0775, true);
    }
    $filename = 'portfolio-' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $directory . '/' . $filename)) {
        return $existing;
    }
    return 'assets/images/uploads/' . $filename;
}

function adminSaveHero($data) {
    $fields = [
        'hero_title' => ['text', 'hero'],
        'hero_subtitle' => ['text', 'hero'],
        'hero_description' => ['textarea', 'hero'],
        'cta_primary_text' => ['text', 'hero'],
        'cta_secondary_text' => ['text', 'hero'],
        'stat_number_1' => ['text', 'hero'],
        'stat_label_1' => ['text', 'hero'],
        'stat_number_2' => ['text', 'hero'],
        'stat_label_2' => ['text', 'hero'],
        'stat_number_3' => ['text', 'hero'],
        'stat_label_3' => ['text', 'hero'],
        'hero_badge_1' => ['text', 'hero'],
        'hero_badge_2' => ['text', 'hero'],
        'hero_badge_3' => ['text', 'hero'],
    ];
    foreach ($fields as $field => $meta) {
        if (array_key_exists($field, $data)) {
            if (!updateSiteContent($field, adminPostString($data[$field]), $meta[0], $meta[1])) {
                return false;
            }
        }
    }
    $existingImage = getSiteContent('hero_image', 'assets/images/mypic.png');
    $imagePath = adminUploadImage('hero_image_upload', $existingImage);
    $imageInput = adminPostString($data['hero_image'] ?? '');
    if ($imageInput !== '') {
        $imagePath = $imageInput;
    }
    return updateSiteContent('hero_image', $imagePath, 'image', 'hero');
}

function adminSaveCopy($section, $data) {
    $copyFields = [
        'services' => ['services_heading', 'services_intro'],
        'projects' => ['projects_heading', 'projects_intro'],
        'skills' => ['skills_heading', 'skills_intro'],
        'testimonials' => ['testimonials_heading', 'testimonials_intro'],
        'contact' => ['contact_heading', 'contact_intro'],
    ];
    foreach ($copyFields[$section] ?? [] as $field) {
        if (array_key_exists($field, $data)) {
            if (!updateSiteContent($field, adminPostString($data[$field], 1000), $field === str_replace('_heading', '_intro', $field) ? 'textarea' : 'text', $section)) {
                return false;
            }
        }
    }
    return true;
}

function adminSaveCollection($section, $items) {
    $definitions = [
        'services' => [
            'table' => 'services',
            'columns' => ['title', 'description', 'icon_svg', 'highlighted', 'sort_order'],
            'defaults' => ['title' => 'New Service', 'description' => '', 'icon_svg' => '', 'highlighted' => 0, 'sort_order' => 0],
        ],
        'projects' => [
            'table' => 'projects',
            'columns' => ['title', 'description', 'image_path', 'github_link', 'demo_link', 'tech_stack', 'project_date', 'featured', 'sort_order'],
            'defaults' => ['title' => 'New Project', 'description' => '', 'image_path' => '', 'github_link' => '', 'demo_link' => '', 'tech_stack' => '', 'project_date' => date('Y-m-d'), 'featured' => 0, 'sort_order' => 0],
        ],
        'skills' => [
            'table' => 'skills',
            'columns' => ['name', 'percentage', 'category', 'sort_order'],
            'defaults' => ['name' => 'New Skill', 'percentage' => 80, 'category' => 'Other', 'sort_order' => 0],
        ],
        'testimonials' => [
            'table' => 'testimonials',
            'columns' => ['client_name', 'client_title', 'testimonial_text', 'rating', 'client_image', 'sort_order'],
            'defaults' => ['client_name' => 'New Client', 'client_title' => '', 'testimonial_text' => '', 'rating' => 5, 'client_image' => '', 'sort_order' => 0],
        ],
    ];
    if (!isset($definitions[$section]) || !is_array($items)) {
        return false;
    }

    $definition = $definitions[$section];
    $normalized = [];
    $sort = 1;
    foreach ($items as $key => $item) {
        if (!is_array($item) || !empty($item['_delete'])) {
            continue;
        }
        $item = array_merge($definition['defaults'], $item);
        $clean = [];
        foreach ($definition['columns'] as $column) {
            $clean[$column] = adminPostString($item[$column] ?? $definition['defaults'][$column], $column === 'description' || $column === 'testimonial_text' ? 20000 : 1000);
        }
        $clean['sort_order'] = max(1, (int) ($item['sort_order'] ?? $sort));
        if (in_array($section, ['services', 'projects'], true)) {
            $clean['highlighted'] = !empty($item['highlighted']) ? 1 : 0;
        }
        if ($section === 'projects') {
            $clean['featured'] = !empty($item['featured']) ? 1 : 0;
            $existingImage = $clean['image_path'];
            $uploadField = 'project_image_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', (string) $key);
            $clean['image_path'] = adminUploadImage($uploadField, $existingImage);
        }
        if ($section === 'skills') {
            $clean['percentage'] = min(100, max(0, (int) ($item['percentage'] ?? 0)));
        }
        if ($section === 'testimonials') {
            $clean['rating'] = min(5, max(1, (int) ($item['rating'] ?? 5)));
        }
        $normalized[] = $clean;
        $sort++;
    }
    return portfolioSaveCollection($section, $definition['table'], $normalized, $definition['columns']);
}

function adminSaveContact($contact, $social) {
    $contactRows = [];
    $sort = 1;
    foreach (['Phone', 'Email', 'Location'] as $type) {
        $value = adminPostString($contact[$type] ?? '', 255);
        if ($value !== '') {
            $contactRows[] = ['contact_type' => $type, 'contact_value' => $value, 'sort_order' => $sort++];
        }
        updateSiteContent('contact_' . strtolower($type === 'Location' ? 'location' : $type), $value, 'text', 'contact');
    }
    $socialRows = [];
    $sort = 1;
    foreach (['Instagram', 'TikTok', 'LinkedIn', 'WhatsApp'] as $platform) {
        $url = adminPostString($social[$platform] ?? '', 255);
        if ($url !== '') {
            $socialRows[] = ['platform' => $platform, 'url' => $url, 'sort_order' => $sort++];
        }
    }
    return portfolioSaveCollection('contact_info', 'contact_info', $contactRows, ['contact_type', 'contact_value', 'sort_order'])
        && portfolioSaveCollection('social_links', 'social_links', $socialRows, ['platform', 'url', 'sort_order']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($csrf, (string) ($_POST['csrf'] ?? ''))) {
        $flash = ['type' => 'error', 'text' => 'Your session token expired. Refresh the page and try again.'];
    } else {
        $action = $_POST['action'] ?? '';
        $saved = false;
        if ($action === 'save_hero') {
            $saved = adminSaveHero($_POST);
        } elseif ($action === 'save_collection') {
            $section = $_POST['section'] ?? '';
            $saved = adminSaveCopy($section, $_POST) && adminSaveCollection($section, $_POST['items'] ?? []);
        } elseif ($action === 'save_contact') {
            $saved = adminSaveCopy('contact', $_POST) && adminSaveContact($_POST['contact'] ?? [], $_POST['social'] ?? []);
        } elseif ($action === 'update_message') {
            $saved = updateContactMessageStatus($_POST['message_id'] ?? 0, $_POST['status'] ?? 'new');
        } elseif ($action === 'delete_message') {
            $saved = deleteContactMessage($_POST['message_id'] ?? 0);
        }
        $flash = $saved
            ? ['type' => 'success', 'text' => $action === 'delete_message' ? 'Message deleted.' : ($action === 'update_message' ? 'Message status updated.' : 'Changes saved successfully and are now live on the portfolio.')]
            : ['type' => 'error', 'text' => 'Nothing was saved. Check the submitted values and database permissions.'];
    }
}

$hero = [
    'hero_title' => getSiteContent('hero_title', 'Adem Abdifith'),
    'hero_subtitle' => getSiteContent('hero_subtitle', 'Full-Stack Developer & Tech Consultant'),
    'hero_description' => getSiteContent('hero_description', 'Transforming ideas into exceptional digital experiences.'),
    'hero_image' => getSiteContent('hero_image', 'assets/images/mypic.png'),
    'cta_primary_text' => getSiteContent('cta_primary_text', 'Start Your Project'),
    'cta_secondary_text' => getSiteContent('cta_secondary_text', 'View My Work'),
    'stat_number_1' => getSiteContentAny(['stat_number_1', 'stat1_number'], '10+'),
    'stat_label_1' => getSiteContentAny(['stat_label_1', 'stat1_label'], 'Projects Completed'),
    'stat_number_2' => getSiteContentAny(['stat_number_2', 'stat2_number'], '2+'),
    'stat_label_2' => getSiteContentAny(['stat_label_2', 'stat2_label'], 'Years Experience'),
    'stat_number_3' => getSiteContentAny(['stat_number_3', 'stat3_number'], '100%'),
    'stat_label_3' => getSiteContentAny(['stat_label_3', 'stat3_label'], 'Client Satisfaction'),
    'hero_badge_1' => getSiteContent('hero_badge_1', 'PHP'),
    'hero_badge_2' => getSiteContent('hero_badge_2', 'JavaScript'),
    'hero_badge_3' => getSiteContent('hero_badge_3', 'MySQL'),
];
$services = getAllServices();
$projects = getAllProjects();
$skills = getAllSkills();
$testimonials = getAllTestimonials();
$messages = getContactMessages();
$contact = getContactInfo();
$social = getSocialLinks();
$copy = [];
foreach (['services_heading', 'services_intro', 'projects_heading', 'projects_intro', 'skills_heading', 'skills_intro', 'testimonials_heading', 'testimonials_intro', 'contact_heading', 'contact_intro'] as $copyKey) {
    $copy[$copyKey] = getSiteContent($copyKey, '');
}
$counts = ['projects' => count($projects), 'services' => count($services), 'skills' => count($skills), 'testimonials' => count($testimonials), 'messages' => count($messages), 'unread_messages' => count(array_filter($messages, static function ($message) { return ($message['status'] ?? 'new') === 'new'; }))];

function adminNavIcon($type) {
    $icons = [
        'dashboard' => '<path d="M4 13h6V4H4v9Zm0 7h6v-4H4v4Zm10 0h6v-9h-6v9Zm0-16v4h6V4h-6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
        'hero' => '<path d="M3 9 12 2l9 7v11H3V9Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 22V12h6v10" stroke="currentColor" stroke-width="1.8"/>',
        'services' => '<path d="m12 2 10 5-10 5L2 7l10-5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m2 12 10 5 10-5M2 17l10 5 10-5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
        'projects' => '<path d="M5 3h10l4 4v14H5V3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 3v5h5M8 13h8M8 17h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
        'skills' => '<path d="M4 19V5m0 14h16M7 15l3-4 3 2 4-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        'testimonials' => '<path d="M4 5h16v11H8l-4 4V5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8 9h8M8 12h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
        'contact' => '<path d="M4 5h16v14H4V5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>',
        'messages' => '<path d="M4 5h16v12H8l-4 4V5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M8 9h8M8 12h5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>',
    ];
    return '<svg class="admin-nav-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">' . ($icons[$type] ?? $icons['dashboard']) . '</svg>';
}

function renderItemStart($key, $label, $count = null) {
    return '<div class="editor-card" data-repeatable-item><div class="editor-card-top"><div><span class="eyebrow">' . adminEscape($label) . '</span>' . ($count !== null ? '<span class="item-index">#' . adminEscape($count) . '</span>' : '') . '</div><label class="delete-toggle"><input type="checkbox" name="items[' . adminEscape($key) . '][_delete]" value="1"><span>Remove</span></label></div>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Control Center</title>
    <link rel="stylesheet" href="assets/css/admin-dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Lora:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="brand-lockup"><div class="brand-mark">A</div><div><strong>Portfolio</strong><span>Control Center</span></div></div>
        <div class="sidebar-label">Workspace</div>
        <nav class="sidebar-nav" aria-label="Admin sections">
            <a href="#dashboard" class="nav-link active" data-section="dashboard"><?php echo adminNavIcon('dashboard'); ?><span>Overview</span></a>
            <div class="sidebar-label content-label">Content</div>
            <a href="#hero" class="nav-link" data-section="hero"><?php echo adminNavIcon('hero'); ?><span>Hero section</span></a>
            <a href="#services" class="nav-link" data-section="services"><?php echo adminNavIcon('services'); ?><span>Services</span></a>
            <a href="#projects" class="nav-link" data-section="projects"><?php echo adminNavIcon('projects'); ?><span>Projects</span></a>
            <a href="#skills" class="nav-link" data-section="skills"><?php echo adminNavIcon('skills'); ?><span>Skills</span></a>
            <a href="#testimonials" class="nav-link" data-section="testimonials"><?php echo adminNavIcon('testimonials'); ?><span>Testimonials</span></a>
            <a href="#contact" class="nav-link" data-section="contact"><?php echo adminNavIcon('contact'); ?><span>Contact & socials</span></a>
            <a href="#messages" class="nav-link" data-section="messages"><?php echo adminNavIcon('messages'); ?><span>Messages<?php if ($counts['unread_messages'] > 0): ?><b class="nav-badge"><?php echo $counts['unread_messages']; ?></b><?php endif; ?></span></a>
        </nav>
        <div class="sidebar-footer"><a class="site-link" href="../index.php" target="_blank">View live portfolio <span>↗</span></a><div class="account"><div class="avatar"><?php echo adminEscape($userInitial); ?></div><div><strong><?php echo adminEscape($username); ?></strong><span>Administrator</span></div><a class="logout" href="logout.php" title="Log out">↪</a></div></div>
    </aside>

    <main class="admin-main">
        <header class="topbar"><button class="mobile-toggle" id="mobileToggle" aria-label="Open menu">☰</button><div><span class="topbar-kicker">Content management</span><h1>Keep your portfolio current</h1></div><a class="preview-button" href="../index.php" target="_blank">Preview site <span>↗</span></a></header>
        <?php if ($flash): ?><div class="flash <?php echo adminEscape($flash['type']); ?>" role="status"><span class="flash-dot"></span><?php echo adminEscape($flash['text']); ?></div><?php endif; ?>

        <section class="panel-section active" id="dashboard-section" data-panel="dashboard">
            <div class="section-heading"><div><span class="topbar-kicker">Overview</span><h2>Your portfolio at a glance</h2><p>Manage the content that appears on your public site. Every saved change is written to the live data source.</p></div><span class="live-pill"><i></i> Live system</span></div>
            <div class="stats-grid">
                <button class="metric-card" data-section="projects"><span class="metric-icon"><?php echo adminNavIcon('projects'); ?></span><strong><?php echo $counts['projects']; ?></strong><span>Projects</span><em>Manage work ↗</em></button>
                <button class="metric-card" data-section="services"><span class="metric-icon"><?php echo adminNavIcon('services'); ?></span><strong><?php echo $counts['services']; ?></strong><span>Services</span><em>Update offers ↗</em></button>
                <button class="metric-card" data-section="skills"><span class="metric-icon"><?php echo adminNavIcon('skills'); ?></span><strong><?php echo $counts['skills']; ?></strong><span>Skills</span><em>Adjust expertise ↗</em></button>
                <button class="metric-card" data-section="testimonials"><span class="metric-icon"><?php echo adminNavIcon('testimonials'); ?></span><strong><?php echo $counts['testimonials']; ?></strong><span>Testimonials</span><em>Edit reviews ↗</em></button>
                <button class="metric-card" data-section="messages"><span class="metric-icon"><?php echo adminNavIcon('messages'); ?></span><strong><?php echo $counts['unread_messages']; ?></strong><span>Unread messages</span><em>Open inbox ↗</em></button>
            </div>
            <div class="overview-grid"><div class="surface-card"><div class="card-heading"><div><span class="eyebrow">Workflow</span><h3>What you can control</h3></div></div><div class="workflow-list"><button data-section="hero"><span class="workflow-number">01</span><span><strong>Hero presentation</strong><small>Title, positioning, calls to action, stats and profile image.</small></span><b>→</b></button><button data-section="projects"><span class="workflow-number">02</span><span><strong>Proof of work</strong><small>Projects, links, featured status, dates and project imagery.</small></span><b>→</b></button><button data-section="contact"><span class="workflow-number">03</span><span><strong>Contact details</strong><small>Public contact information and social profile links.</small></span><b>→</b></button></div></div><div class="surface-card accent-card"><span class="eyebrow">Publishing model</span><h3>One source of truth</h3><p>The editor uses the same data layer as the public portfolio. No copy-paste, no separate templates, and no hidden staging values.</p><div class="completion"><span><b>Connected</b><small>Admin ↔ Frontend</small></span><div class="completion-bar"><i style="width:100%"></i></div></div></div></div>
        </section>

        <section class="panel-section" id="hero-section" data-panel="hero">
            <div class="section-heading"><div><span class="topbar-kicker">Content / Hero</span><h2>Shape your first impression</h2><p>These fields power the home section at the top of the public portfolio.</p></div></div>
            <form class="editor-form" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?php echo adminEscape($csrf); ?>"><input type="hidden" name="action" value="save_hero"><div class="form-grid two-col"><label class="field"><span>Headline</span><input name="hero_title" value="<?php echo adminEscape($hero['hero_title']); ?>" required></label><label class="field"><span>Role / subtitle</span><input name="hero_subtitle" value="<?php echo adminEscape($hero['hero_subtitle']); ?>" required></label><label class="field full"><span>Introduction</span><textarea name="hero_description" rows="4" required><?php echo adminEscape($hero['hero_description']); ?></textarea></label><label class="field"><span>Primary button</span><input name="cta_primary_text" value="<?php echo adminEscape($hero['cta_primary_text']); ?>"></label><label class="field"><span>Secondary button</span><input name="cta_secondary_text" value="<?php echo adminEscape($hero['cta_secondary_text']); ?>"></label></div><div class="subheading"><span class="eyebrow">Proof points</span><h3>Hero statistics</h3></div><div class="form-grid three-col"><?php for ($i = 1; $i <= 3; $i++): ?><div class="stat-editor"><span class="stat-editor-index">0<?php echo $i; ?></span><label class="field"><span>Value</span><input name="stat_number_<?php echo $i; ?>" value="<?php echo adminEscape($hero['stat_number_' . $i]); ?>"></label><label class="field"><span>Label</span><input name="stat_label_<?php echo $i; ?>" value="<?php echo adminEscape($hero['stat_label_' . $i]); ?>"></label></div><?php endfor; ?></div><div class="subheading"><span class="eyebrow">Visual identity</span><h3>Technology badges</h3></div><div class="form-grid three-col"><label class="field"><span>Badge 1</span><input name="hero_badge_1" value="<?php echo adminEscape($hero['hero_badge_1']); ?>"></label><label class="field"><span>Badge 2</span><input name="hero_badge_2" value="<?php echo adminEscape($hero['hero_badge_2']); ?>"></label><label class="field"><span>Badge 3</span><input name="hero_badge_3" value="<?php echo adminEscape($hero['hero_badge_3']); ?>"></label></div><div class="subheading"><span class="eyebrow">Visual identity</span><h3>Profile image</h3></div><div class="image-editor"><div class="image-preview"><img src="<?php echo adminEscape($hero['hero_image']); ?>" alt="Current profile image"></div><div class="image-inputs"><label class="field"><span>Image URL or project-relative path</span><input name="hero_image" value="<?php echo adminEscape($hero['hero_image']); ?>" placeholder="assets/images/mypic.png"></label><label class="field"><span>Or upload a new image</span><input type="file" name="hero_image_upload" accept="image/jpeg,image/png,image/webp,image/gif"><small>JPG, PNG, WEBP or GIF. Maximum 8 MB.</small></label></div></div><div class="form-actions"><span class="save-note">Changes are published immediately.</span><button class="primary-button" type="submit">Save hero section <span>→</span></button></div></form>
        </section>

        <section class="panel-section" id="services-section" data-panel="services">
            <div class="section-heading"><div><span class="topbar-kicker">Content / Services</span><h2>Present your capabilities</h2><p>Edit service cards, reorder them, or add new offerings without touching the layout.</p></div></div>
            <form class="editor-form collection-form" method="post"><input type="hidden" name="csrf" value="<?php echo adminEscape($csrf); ?>"><input type="hidden" name="action" value="save_collection"><input type="hidden" name="section" value="services"><div class="form-grid two-col"><label class="field"><span>Section heading</span><input name="services_heading" value="<?php echo adminEscape($copy['services_heading'] ?: 'My Services'); ?>"></label><label class="field"><span>Section introduction</span><input name="services_intro" value="<?php echo adminEscape($copy['services_intro'] ?: 'Comprehensive web development solutions tailored to your business needs'); ?>"></label></div><div class="repeatable-list" data-list="services"><?php foreach ($services as $index => $service): $key = (string) ($service['id'] ?? $index + 1); echo renderItemStart($key, 'Service', $index + 1); ?><div class="form-grid two-col"><label class="field"><span>Title</span><input name="items[<?php echo adminEscape($key); ?>][title]" value="<?php echo adminEscape($service['title']); ?>" required></label><label class="field"><span>Display order</span><input type="number" min="1" name="items[<?php echo adminEscape($key); ?>][sort_order]" value="<?php echo adminEscape($service['sort_order'] ?? $index + 1); ?>"></label><label class="field full"><span>Description</span><textarea name="items[<?php echo adminEscape($key); ?>][description]" rows="3" required><?php echo adminEscape($service['description']); ?></textarea></label></div><label class="switch-field"><input type="checkbox" name="items[<?php echo adminEscape($key); ?>][highlighted]" value="1" <?php echo !empty($service['highlighted']) ? 'checked' : ''; ?>><span class="switch"></span><span><strong>Highlight this service</strong><small>Uses the portfolio’s featured card treatment.</small></span></label></div><?php endforeach; ?></div><button type="button" class="add-button" data-add="service">+ Add service</button><div class="form-actions"><span class="save-note">Removed cards disappear from the public site.</span><button class="primary-button" type="submit">Save services <span>→</span></button></div></form>
        </section>

        <section class="panel-section" id="projects-section" data-panel="projects">
            <div class="section-heading"><div><span class="topbar-kicker">Content / Projects</span><h2>Curate your work</h2><p>Keep your case studies, links, dates, and featured work in sync with the public grid.</p></div></div>
            <form class="editor-form collection-form" method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?php echo adminEscape($csrf); ?>"><input type="hidden" name="action" value="save_collection"><input type="hidden" name="section" value="projects"><div class="form-grid two-col"><label class="field"><span>Section heading</span><input name="projects_heading" value="<?php echo adminEscape($copy['projects_heading'] ?: 'Featured Projects'); ?>"></label><label class="field"><span>Section introduction</span><input name="projects_intro" value="<?php echo adminEscape($copy['projects_intro'] ?: 'Showcasing my latest work and technical expertise across various domains'); ?>"></label></div><div class="repeatable-list" data-list="projects"><?php foreach ($projects as $index => $project): $key = (string) ($project['id'] ?? $index + 1); echo renderItemStart($key, 'Project', $index + 1); ?><div class="form-grid two-col"><label class="field"><span>Project title</span><input name="items[<?php echo adminEscape($key); ?>][title]" value="<?php echo adminEscape($project['title']); ?>" required></label><label class="field"><span>Date</span><input type="date" name="items[<?php echo adminEscape($key); ?>][project_date]" value="<?php echo adminEscape($project['project_date'] ?? ''); ?>"></label><label class="field full"><span>Description</span><textarea name="items[<?php echo adminEscape($key); ?>][description]" rows="3" required><?php echo adminEscape($project['description']); ?></textarea></label><label class="field"><span>GitHub URL</span><input type="url" name="items[<?php echo adminEscape($key); ?>][github_link]" value="<?php echo adminEscape($project['github_link'] ?? ''); ?>"></label><label class="field"><span>Demo URL</span><input type="url" name="items[<?php echo adminEscape($key); ?>][demo_link]" value="<?php echo adminEscape($project['demo_link'] ?? ''); ?>"></label><label class="field"><span>Technology stack</span><input name="items[<?php echo adminEscape($key); ?>][tech_stack]" value="<?php echo adminEscape($project['tech_stack'] ?? ''); ?>" placeholder="PHP, MySQL, JavaScript"></label><label class="field"><span>Display order</span><input type="number" min="1" name="items[<?php echo adminEscape($key); ?>][sort_order]" value="<?php echo adminEscape($project['sort_order'] ?? $index + 1); ?>"></label><label class="field"><span>Image URL or relative path</span><input name="items[<?php echo adminEscape($key); ?>][image_path]" value="<?php echo adminEscape($project['image_path'] ?? ''); ?>" placeholder="assets/images/project.jpg"></label><label class="field"><span>Upload project image</span><input type="file" name="project_image_<?php echo adminEscape($key); ?>" accept="image/jpeg,image/png,image/webp,image/gif"></label></div><div class="inline-toggles"><label class="switch-field"><input type="checkbox" name="items[<?php echo adminEscape($key); ?>][featured]" value="1" <?php echo !empty($project['featured']) ? 'checked' : ''; ?>><span class="switch"></span><span><strong>Featured project</strong><small>Marks the project as a priority item.</small></span></label></div></div><?php endforeach; ?></div><button type="button" class="add-button" data-add="project">+ Add project</button><div class="form-actions"><span class="save-note">Upload images as JPG, PNG, WEBP or GIF.</span><button class="primary-button" type="submit">Save projects <span>→</span></button></div></form>
        </section>

        <section class="panel-section" id="skills-section" data-panel="skills">
            <div class="section-heading"><div><span class="topbar-kicker">Content / Skills</span><h2>Show your technical range</h2><p>Use percentage values between 0 and 100. Skills are grouped on the public site by category.</p></div></div>
            <form class="editor-form collection-form" method="post"><input type="hidden" name="csrf" value="<?php echo adminEscape($csrf); ?>"><input type="hidden" name="action" value="save_collection"><input type="hidden" name="section" value="skills"><div class="form-grid two-col"><label class="field"><span>Section heading</span><input name="skills_heading" value="<?php echo adminEscape($copy['skills_heading'] ?: 'Technical Skills'); ?>"></label><label class="field"><span>Section introduction</span><input name="skills_intro" value="<?php echo adminEscape($copy['skills_intro'] ?: 'My expertise across different technology stacks and development domains'); ?>"></label></div><div class="repeatable-list" data-list="skills"><?php foreach ($skills as $index => $skill): $key = (string) ($skill['id'] ?? $index + 1); echo renderItemStart($key, 'Skill', $index + 1); ?><div class="form-grid three-col"><label class="field"><span>Skill name</span><input name="items[<?php echo adminEscape($key); ?>][name]" value="<?php echo adminEscape($skill['name']); ?>" required></label><label class="field"><span>Category</span><input name="items[<?php echo adminEscape($key); ?>][category]" value="<?php echo adminEscape($skill['category']); ?>" required></label><label class="field"><span>Proficiency (0–100)</span><input type="number" min="0" max="100" name="items[<?php echo adminEscape($key); ?>][percentage]" value="<?php echo adminEscape($skill['percentage']); ?>" required></label><label class="field"><span>Display order</span><input type="number" min="1" name="items[<?php echo adminEscape($key); ?>][sort_order]" value="<?php echo adminEscape($skill['sort_order'] ?? $index + 1); ?>"></label></div></div><?php endforeach; ?></div><button type="button" class="add-button" data-add="skill">+ Add skill</button><div class="form-actions"><span class="save-note">The public progress bars use these percentages.</span><button class="primary-button" type="submit">Save skills <span>→</span></button></div></form>
        </section>

        <section class="panel-section" id="testimonials-section" data-panel="testimonials">
            <div class="section-heading"><div><span class="topbar-kicker">Content / Testimonials</span><h2>Let client feedback speak</h2><p>Keep names, titles, ratings, and review copy polished and current.</p></div></div>
            <form class="editor-form collection-form" method="post"><input type="hidden" name="csrf" value="<?php echo adminEscape($csrf); ?>"><input type="hidden" name="action" value="save_collection"><input type="hidden" name="section" value="testimonials"><div class="form-grid two-col"><label class="field"><span>Section heading</span><input name="testimonials_heading" value="<?php echo adminEscape($copy['testimonials_heading'] ?: 'Client Testimonials'); ?>"></label><label class="field"><span>Section introduction</span><input name="testimonials_intro" value="<?php echo adminEscape($copy['testimonials_intro'] ?: 'What clients say about working with me and the quality of my deliverables'); ?>"></label></div><div class="repeatable-list" data-list="testimonials"><?php foreach ($testimonials as $index => $testimonial): $key = (string) ($testimonial['id'] ?? $index + 1); echo renderItemStart($key, 'Testimonial', $index + 1); ?><div class="form-grid two-col"><label class="field"><span>Client name</span><input name="items[<?php echo adminEscape($key); ?>][client_name]" value="<?php echo adminEscape($testimonial['client_name']); ?>" required></label><label class="field"><span>Client title</span><input name="items[<?php echo adminEscape($key); ?>][client_title]" value="<?php echo adminEscape($testimonial['client_title'] ?? ''); ?>"></label><label class="field full"><span>Review</span><textarea name="items[<?php echo adminEscape($key); ?>][testimonial_text]" rows="4" required><?php echo adminEscape($testimonial['testimonial_text']); ?></textarea></label><label class="field"><span>Rating (1–5)</span><input type="number" min="1" max="5" name="items[<?php echo adminEscape($key); ?>][rating]" value="<?php echo adminEscape($testimonial['rating'] ?? 5); ?>"></label><label class="field"><span>Display order</span><input type="number" min="1" name="items[<?php echo adminEscape($key); ?>][sort_order]" value="<?php echo adminEscape($testimonial['sort_order'] ?? $index + 1); ?>"></label></div></div><?php endforeach; ?></div><button type="button" class="add-button" data-add="testimonial">+ Add testimonial</button><div class="form-actions"><span class="save-note">Ratings are displayed as stars.</span><button class="primary-button" type="submit">Save testimonials <span>→</span></button></div></form>
        </section>

        <section class="panel-section" id="contact-section" data-panel="contact">
            <div class="section-heading"><div><span class="topbar-kicker">Content / Contact</span><h2>Make it easy to reach you</h2><p>These values feed the contact section and social buttons in the public footer.</p></div></div>
            <form class="editor-form" method="post"><input type="hidden" name="csrf" value="<?php echo adminEscape($csrf); ?>"><input type="hidden" name="action" value="save_contact"><div class="form-grid two-col"><label class="field"><span>Section heading</span><input name="contact_heading" value="<?php echo adminEscape($copy['contact_heading'] ?: 'Get In Touch'); ?>"></label><label class="field"><span>Section introduction</span><input name="contact_intro" value="<?php echo adminEscape($copy['contact_intro'] ?: "Ready to start your next project? Let's discuss how I can help bring your ideas to life"); ?>"></label></div><div class="subheading"><span class="eyebrow">Direct contact</span><h3>Contact information</h3></div><div class="form-grid three-col"><label class="field"><span>Phone</span><input name="contact[Phone]" value="<?php echo adminEscape($contact['Phone'] ?? ''); ?>"></label><label class="field"><span>Email</span><input type="email" name="contact[Email]" value="<?php echo adminEscape($contact['Email'] ?? ''); ?>"></label><label class="field"><span>Location</span><input name="contact[Location]" value="<?php echo adminEscape($contact['Location'] ?? ''); ?>"></label></div><div class="subheading"><span class="eyebrow">Social presence</span><h3>Profile links</h3></div><div class="form-grid two-col"><?php foreach (['Instagram', 'TikTok', 'LinkedIn', 'WhatsApp'] as $platform): ?><label class="field"><span><?php echo adminEscape($platform); ?> URL</span><input type="url" name="social[<?php echo adminEscape($platform); ?>]" value="<?php echo adminEscape($social[$platform]['url'] ?? ''); ?>" placeholder="https://"></label><?php endforeach; ?></div><div class="form-actions"><span class="save-note">Leave a social field empty to hide it from the footer.</span><button class="primary-button" type="submit">Save contact details <span>→</span></button></div></form>
        </section>

        <section class="panel-section" id="messages-section" data-panel="messages">
            <div class="section-heading"><div><span class="topbar-kicker">Inbox / Messages</span><h2>Stay on top of new enquiries</h2><p>Messages submitted through the public contact form are stored here. Update their status as you follow up.</p></div><span class="live-pill"><i></i><?php echo $counts['unread_messages']; ?> unread</span></div>
            <?php if (!$messages): ?>
                <div class="surface-card empty-state"><h3>Your inbox is empty</h3><p>New contact-form submissions will appear here.</p></div>
            <?php else: ?>
                <div class="message-list">
                    <?php foreach ($messages as $message): $status = $message['status'] ?? 'new'; ?>
                        <details class="message-card <?php echo $status === 'new' ? 'is-unread' : ''; ?>">
                            <summary class="message-card-header"><div><span class="eyebrow"><?php echo adminEscape($message['email']); ?></span><h3><?php echo adminEscape($message['name']); ?></h3><span class="message-preview"><?php echo adminEscape($message['subject']); ?></span></div><span class="message-summary-side"><span class="message-status status-<?php echo adminEscape($status); ?>"><?php echo adminEscape(ucfirst($status)); ?></span><span class="message-chevron" aria-hidden="true">⌄</span></span></summary>
                            <div class="message-meta"><strong><?php echo adminEscape($message['subject']); ?></strong><time datetime="<?php echo adminEscape($message['created_at']); ?>"><?php echo adminEscape(date('M j, Y · g:i A', strtotime($message['created_at']))); ?></time></div>
                            <p class="message-body"><?php echo nl2br(adminEscape($message['message'])); ?></p>
                            <div class="message-actions"><form method="post"><input type="hidden" name="csrf" value="<?php echo adminEscape($csrf); ?>"><input type="hidden" name="action" value="update_message"><input type="hidden" name="message_id" value="<?php echo (int) $message['id']; ?>"><label class="message-status-control"><span>Update status</span><select name="status"><option value="new" <?php echo $status === 'new' ? 'selected' : ''; ?>>New</option><option value="read" <?php echo $status === 'read' ? 'selected' : ''; ?>>Read</option><option value="replied" <?php echo $status === 'replied' ? 'selected' : ''; ?>>Replied</option><option value="archived" <?php echo $status === 'archived' ? 'selected' : ''; ?>>Archived</option></select><button class="secondary-button" type="submit">Save status</button></label></form><a class="secondary-button" href="mailto:<?php echo adminEscape($message['email']); ?>?subject=<?php echo rawurlencode('Re: ' . $message['subject']); ?>">Reply by email ↗</a><form method="post" onsubmit="return confirm('Delete this message permanently?');"><input type="hidden" name="csrf" value="<?php echo adminEscape($csrf); ?>"><input type="hidden" name="action" value="delete_message"><input type="hidden" name="message_id" value="<?php echo (int) $message['id']; ?>"><button class="danger-button" type="submit">Delete</button></form></div>
                        </details>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>
<script src="assets/js/admin-dashboard.js?v=20261030-2"></script>
</body>
</html>
