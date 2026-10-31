<?php
/**
 * Portfolio data layer.
 *
 * MySQL is used when the configured database is available. A JSON store is
 * used as a safe local fallback so the portfolio still renders and admin
 * changes persist on installations that have not configured MySQL yet.
 */
define('DB_HOST', getenv('PORTFOLIO_DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('PORTFOLIO_DB_NAME') ?: 'portfolio_db');
define('DB_USER', getenv('PORTFOLIO_DB_USER') ?: 'root');
define('DB_PASS', getenv('PORTFOLIO_DB_PASS') ?: '');
define('PORTFOLIO_DATA_FILE', __DIR__ . '/../data/content.json');

function portfolioLastError($message = null) {
    static $lastError = null;
    if ($message !== null) {
        $lastError = (string) $message;
    }
    return $lastError;
}

function getDBConnection() {
    static $pdo = null;
    static $attempted = false;

    if ($attempted) {
        return $pdo;
    }

    $attempted = true;
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    } catch (PDOException $e) {
        error_log('Portfolio database connection failed: ' . $e->getMessage());
        portfolioLastError('The message database is not available.');
        $pdo = null;
    }

    return $pdo;
}

function portfolioDefaultData() {
    return [
        'site_content' => [
            'hero_title' => 'Adem Abdifith',
            'hero_subtitle' => 'Full-Stack Developer & Tech Consultant',
            'hero_description' => 'Transforming ideas into exceptional digital experiences. I help agencies and founders build powerful web applications that drive business growth.',
            'hero_badge_1' => 'PHP',
            'hero_badge_2' => 'JavaScript',
            'hero_badge_3' => 'MySQL',
            'hero_image' => 'assets/images/mypic.png',
            'services_heading' => 'My Services',
            'services_intro' => 'Comprehensive web development solutions tailored to your business needs',
            'projects_heading' => 'Featured Projects',
            'projects_intro' => 'Showcasing my latest work and technical expertise across various domains',
            'skills_heading' => 'Technical Skills',
            'skills_intro' => 'My expertise across different technology stacks and development domains',
            'testimonials_heading' => 'Client Testimonials',
            'testimonials_intro' => 'What clients say about working with me and the quality of my deliverables',
            'contact_heading' => 'Get In Touch',
            'contact_intro' => "Ready to start your next project? Let's discuss how I can help bring your ideas to life",
            'footer_about' => "Full Stack Developer passionate about creating innovative digital solutions. Let's work together to bring your ideas to life.",
            'cta_primary_text' => 'Start Your Project',
            'cta_secondary_text' => 'View My Work',
            'stat_number_1' => '10+',
            'stat_label_1' => 'Projects Completed',
            'stat_number_2' => '2+',
            'stat_label_2' => 'Years Experience',
            'stat_number_3' => '100%',
            'stat_label_3' => 'Client Satisfaction',
            'contact_phone' => '+251 912 345 678',
            'contact_email' => 'adem@example.com',
            'contact_location' => 'Addis Ababa, Ethiopia',
        ],
        'services' => [
            ['id' => 1, 'title' => 'Web Development', 'description' => 'Custom web applications built with modern technologies. From simple landing pages to complex e-commerce platforms.', 'icon_svg' => '', 'highlighted' => 0, 'sort_order' => 1],
            ['id' => 2, 'title' => 'Mobile Development', 'description' => 'Native and cross-platform mobile applications for iOS and Android. React Native and Flutter expertise.', 'icon_svg' => '', 'highlighted' => 0, 'sort_order' => 2],
            ['id' => 3, 'title' => 'UI/UX Design', 'description' => 'User-centered design that creates intuitive and engaging user experiences. Wireframes to final prototypes.', 'icon_svg' => '', 'highlighted' => 1, 'sort_order' => 3],
            ['id' => 4, 'title' => 'Database Design', 'description' => 'Efficient database architecture and optimization. MySQL, PostgreSQL, and NoSQL solutions.', 'icon_svg' => '', 'highlighted' => 0, 'sort_order' => 4],
            ['id' => 5, 'title' => 'API Development', 'description' => 'RESTful and GraphQL APIs for seamless integration. Secure, scalable, and well-documented.', 'icon_svg' => '', 'highlighted' => 0, 'sort_order' => 5],
            ['id' => 6, 'title' => 'Tech Consulting', 'description' => 'Strategic technology consulting to help businesses choose the right tech stack and architecture for their needs.', 'icon_svg' => '', 'highlighted' => 0, 'sort_order' => 6],
        ],
        'projects' => [
            ['id' => 1, 'title' => 'E-Commerce Platform', 'description' => 'A full-featured e-commerce platform with payment integration, inventory management, and admin dashboard. Built with modern web technologies for optimal performance and user experience.', 'image_path' => '', 'github_link' => 'https://github.com/adem/ecommerce-platform', 'demo_link' => 'https://demo-ecommerce.adem.com', 'tech_stack' => 'PHP, MySQL, JavaScript, Bootstrap', 'project_date' => '2024-01-15', 'featured' => 1, 'sort_order' => 1],
            ['id' => 2, 'title' => 'Task Management App', 'description' => 'A collaborative task management application with real-time updates, team collaboration features, and progress tracking. Designed for productivity and team efficiency.', 'image_path' => '', 'github_link' => 'https://github.com/adem/task-manager', 'demo_link' => 'https://taskmanager.adem.com', 'tech_stack' => 'React, Node.js, MongoDB, Socket.io', 'project_date' => '2024-02-20', 'featured' => 0, 'sort_order' => 2],
            ['id' => 3, 'title' => 'Portfolio Website', 'description' => 'A modern, responsive portfolio website with admin panel for content management. Features dynamic content updates and professional design.', 'image_path' => '', 'github_link' => 'https://github.com/adem/portfolio-website', 'demo_link' => 'https://adem-portfolio.com', 'tech_stack' => 'PHP, MySQL, HTML5, CSS3, JavaScript', 'project_date' => '2024-03-10', 'featured' => 0, 'sort_order' => 3],
            ['id' => 4, 'title' => 'Weather Dashboard', 'description' => 'A real-time weather dashboard with location-based forecasts, interactive maps, and historical data visualization. Provides comprehensive weather information.', 'image_path' => '', 'github_link' => 'https://github.com/adem/weather-dashboard', 'demo_link' => 'https://weather.adem.com', 'tech_stack' => 'Python, Django, PostgreSQL, Chart.js', 'project_date' => '2024-04-05', 'featured' => 0, 'sort_order' => 4],
        ],
        'skills' => [
            ['id' => 1, 'name' => 'HTML/CSS', 'percentage' => 95, 'category' => 'Frontend', 'sort_order' => 1],
            ['id' => 2, 'name' => 'JavaScript', 'percentage' => 90, 'category' => 'Frontend', 'sort_order' => 2],
            ['id' => 3, 'name' => 'PHP', 'percentage' => 85, 'category' => 'Backend', 'sort_order' => 1],
            ['id' => 4, 'name' => 'Python', 'percentage' => 80, 'category' => 'Backend', 'sort_order' => 2],
            ['id' => 5, 'name' => 'MySQL', 'percentage' => 90, 'category' => 'Database', 'sort_order' => 1],
            ['id' => 6, 'name' => 'Oracle', 'percentage' => 75, 'category' => 'Database', 'sort_order' => 2],
        ],
        'testimonials' => [
            ['id' => 1, 'client_name' => 'Sarah Johnson', 'client_title' => 'CEO, TechStart Inc.', 'testimonial_text' => 'Adem transformed our business with a custom e-commerce platform. His attention to detail and technical expertise exceeded our expectations. The project was delivered on time and under budget.', 'rating' => 5, 'client_image' => '', 'sort_order' => 1],
            ['id' => 2, 'client_name' => 'Michael Chen', 'client_title' => 'Founder, Digital Solutions', 'testimonial_text' => 'Working with Adem was a game-changer for our startup. He not only built our MVP but also provided valuable insights on scaling our technology stack. Highly recommended!', 'rating' => 5, 'client_image' => '', 'sort_order' => 2],
            ['id' => 3, 'client_name' => 'Emily Rodriguez', 'client_title' => 'Marketing Director, GrowthCo', 'testimonial_text' => 'Adem\'s expertise in both frontend and backend development made our project seamless. His communication skills and ability to understand our business needs were exceptional.', 'rating' => 5, 'client_image' => '', 'sort_order' => 3],
        ],
        'contact_info' => [
            ['id' => 1, 'contact_type' => 'Phone', 'contact_value' => '+251 912 345 678', 'sort_order' => 1],
            ['id' => 2, 'contact_type' => 'Email', 'contact_value' => 'adem@example.com', 'sort_order' => 2],
            ['id' => 3, 'contact_type' => 'Location', 'contact_value' => 'Addis Ababa, Ethiopia', 'sort_order' => 3],
        ],
        'social_links' => [
            ['id' => 1, 'platform' => 'Instagram', 'url' => 'https://instagram.com/adem_abdifith', 'sort_order' => 1],
            ['id' => 2, 'platform' => 'TikTok', 'url' => 'https://tiktok.com/@adem_abdifith', 'sort_order' => 2],
            ['id' => 3, 'platform' => 'LinkedIn', 'url' => 'https://linkedin.com/in/adem-abdifith', 'sort_order' => 3],
            ['id' => 4, 'platform' => 'WhatsApp', 'url' => 'https://wa.me/251912345678', 'sort_order' => 4],
        ],
        'contact_messages' => [],
    ];
}

function portfolioFallbackData() {
    static $data = null;
    if ($data !== null) {
        return $data;
    }

    $defaults = portfolioDefaultData();
    $directory = dirname(PORTFOLIO_DATA_FILE);
    if (!is_dir($directory)) {
        @mkdir($directory, 0775, true);
    }

    if (is_file(PORTFOLIO_DATA_FILE)) {
        $decoded = json_decode((string) @file_get_contents(PORTFOLIO_DATA_FILE), true);
        if (is_array($decoded)) {
            $data = array_replace_recursive($defaults, $decoded);
            return $data;
        }
    }

    $data = $defaults;
    @file_put_contents(PORTFOLIO_DATA_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    return $data;
}

function portfolioSaveFallbackData($data) {
    $directory = dirname(PORTFOLIO_DATA_FILE);
    if (!is_dir($directory)) {
        @mkdir($directory, 0775, true);
    }
    $saved = @file_put_contents(PORTFOLIO_DATA_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    return $saved !== false;
}

function getSiteContent($key, $default = '') {
    $pdo = getDBConnection();
    if ($pdo instanceof PDO) {
        try {
            $stmt = $pdo->prepare('SELECT content_value FROM site_content WHERE content_key = ? LIMIT 1');
            $stmt->execute([$key]);
            $result = $stmt->fetch();
            return $result ? (string) $result['content_value'] : $default;
        } catch (PDOException $e) {
            // Use the fallback store below.
        }
    }

    $data = portfolioFallbackData();
    return array_key_exists($key, $data['site_content']) ? $data['site_content'][$key] : $default;
}

function getSiteContentAny($keys, $default = '') {
    foreach ((array) $keys as $key) {
        $value = getSiteContent($key, null);
        if ($value !== null && $value !== '') {
            return $value;
        }
    }
    return $default;
}

function updateSiteContent($key, $value, $type = 'text', $section = 'general') {
    $pdo = getDBConnection();
    if ($pdo instanceof PDO) {
        try {
            $stmt = $pdo->prepare("INSERT INTO site_content (content_key, content_value, content_type, section) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE content_value = VALUES(content_value), content_type = VALUES(content_type), section = VALUES(section)");
            return $stmt->execute([$key, $value, $type, $section]);
        } catch (PDOException $e) {
            // Use the fallback store below.
        }
    }

    $data = portfolioFallbackData();
    $data['site_content'][$key] = (string) $value;
    return portfolioSaveFallbackData($data);
}

function portfolioGetCollection($name, $table, $orderBy = 'sort_order ASC, id ASC') {
    $pdo = getDBConnection();
    if ($pdo instanceof PDO) {
        try {
            $stmt = $pdo->query("SELECT * FROM {$table} ORDER BY {$orderBy}");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Use the fallback store below.
        }
    }

    $data = portfolioFallbackData();
    $items = $data[$name] ?? [];
    usort($items, static function ($a, $b) {
        return ((int) ($a['sort_order'] ?? 0) <=> (int) ($b['sort_order'] ?? 0)) ?: ((int) ($a['id'] ?? 0) <=> (int) ($b['id'] ?? 0));
    });
    return $items;
}

function getAllProjects() {
    return portfolioGetCollection('projects', 'projects', 'sort_order ASC, project_date DESC, id ASC');
}

function getAllServices() {
    return portfolioGetCollection('services', 'services');
}

function getAllSkills() {
    return portfolioGetCollection('skills', 'skills', 'category ASC, sort_order ASC, id ASC');
}

function getAllTestimonials() {
    return portfolioGetCollection('testimonials', 'testimonials');
}

function getContactInfo() {
    $rows = portfolioGetCollection('contact_info', 'contact_info');
    $contactInfo = [];
    foreach ($rows as $row) {
        if (!empty($row['contact_type'])) {
            $contactInfo[$row['contact_type']] = $row['contact_value'] ?? '';
        }
    }
    return $contactInfo ?: [
        'Phone' => getSiteContentAny(['contact_phone'], '+251 912 345 678'),
        'Email' => getSiteContentAny(['contact_email'], 'adem@example.com'),
        'Location' => getSiteContentAny(['contact_location', 'contact_address'], 'Addis Ababa, Ethiopia'),
    ];
}

function getSocialLinks() {
    $rows = portfolioGetCollection('social_links', 'social_links');
    $socialLinks = [];
    foreach ($rows as $row) {
        if (!empty($row['platform'])) {
            $socialLinks[$row['platform']] = ['url' => $row['url'] ?? '', 'icon_svg' => $row['icon_svg'] ?? ''];
        }
    }
    return $socialLinks;
}

function saveContactMessage($message) {
    $fields = ['name', 'email', 'subject', 'message', 'ip_address', 'user_agent'];
    $values = [];
    foreach ($fields as $field) {
        $values[$field] = (string) ($message[$field] ?? '');
    }

    $pdo = getDBConnection();
    if (!$pdo instanceof PDO) {
        portfolioLastError('The message database is not available.');
        return false;
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)');
        $saved = $stmt->execute([
            $values['name'],
            $values['email'],
            $values['subject'],
            $values['message'],
            $values['ip_address'],
            $values['user_agent'],
        ]);
        if (!$saved) {
            portfolioLastError('Your message could not be stored in the database.');
        }
        return $saved;
    } catch (PDOException $e) {
        error_log('Contact message insert failed: ' . $e->getMessage());
        portfolioLastError('Your message could not be stored in the database. Please try again.');
        return false;
    }
}

function getContactMessages() {
    $pdo = getDBConnection();
    if ($pdo instanceof PDO) {
        try {
            $stmt = $pdo->query('SELECT id, name, email, subject, message, ip_address, user_agent, status, created_at FROM contact_messages ORDER BY created_at DESC, id DESC');
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            try {
                $stmt = $pdo->query('SELECT id, name, email, subject, message, ip_address, user_agent, created_at FROM contact_messages ORDER BY created_at DESC, id DESC');
                $messages = $stmt->fetchAll();
                foreach ($messages as &$message) {
                    $message['status'] = 'new';
                }
                unset($message);
                return $messages;
            } catch (PDOException $ignored) {
                // Use the fallback store below.
            }
        }
    }

    $data = portfolioFallbackData();
    $messages = $data['contact_messages'] ?? [];
    usort($messages, static function ($a, $b) {
        return strcmp((string) ($b['created_at'] ?? ''), (string) ($a['created_at'] ?? '')) ?: ((int) ($b['id'] ?? 0) <=> (int) ($a['id'] ?? 0));
    });
    foreach ($messages as &$message) {
        $message['status'] = $message['status'] ?? 'new';
    }
    unset($message);
    return $messages;
}

function updateContactMessageStatus($id, $status) {
    $allowed = ['new', 'read', 'replied', 'archived'];
    if (!in_array($status, $allowed, true)) {
        return false;
    }

    $pdo = getDBConnection();
    if ($pdo instanceof PDO) {
        try {
            $stmt = $pdo->prepare('UPDATE contact_messages SET status = ? WHERE id = ?');
            return $stmt->execute([$status, (int) $id]);
        } catch (PDOException $e) {
            // Use the fallback store below.
        }
    }

    $data = portfolioFallbackData();
    foreach ($data['contact_messages'] ?? [] as &$message) {
        if ((int) ($message['id'] ?? 0) === (int) $id) {
            $message['status'] = $status;
            unset($message);
            return portfolioSaveFallbackData($data);
        }
    }
    unset($message);
    return false;
}

function deleteContactMessage($id) {
    $pdo = getDBConnection();
    if ($pdo instanceof PDO) {
        try {
            $stmt = $pdo->prepare('DELETE FROM contact_messages WHERE id = ?');
            return $stmt->execute([(int) $id]);
        } catch (PDOException $e) {
            // Use the fallback store below.
        }
    }

    $data = portfolioFallbackData();
    $before = count($data['contact_messages'] ?? []);
    $data['contact_messages'] = array_values(array_filter($data['contact_messages'] ?? [], static function ($message) use ($id) {
        return (int) ($message['id'] ?? 0) !== (int) $id;
    }));
    return count($data['contact_messages']) !== $before && portfolioSaveFallbackData($data);
}

function portfolioSaveCollection($name, $table, $items, $columns) {
    $pdo = getDBConnection();
    if ($pdo instanceof PDO) {
        try {
            $pdo->beginTransaction();
            $pdo->exec("DELETE FROM {$table}");
            $placeholders = implode(', ', array_fill(0, count($columns), '?'));
            $columnSql = implode(', ', $columns);
            $stmt = $pdo->prepare("INSERT INTO {$table} ({$columnSql}) VALUES ({$placeholders})");
            foreach ($items as $item) {
                $values = [];
                foreach ($columns as $column) {
                    $values[] = $item[$column] ?? null;
                }
                $stmt->execute($values);
            }
            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return false;
        }
    }

    $data = portfolioFallbackData();
    $data[$name] = array_values($items);
    return portfolioSaveFallbackData($data);
}
?>
