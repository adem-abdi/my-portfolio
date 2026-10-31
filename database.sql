-- Portfolio Database Schema
-- Run this in phpMyAdmin or MySQL command line

-- Drop database if exists and recreate (for clean setup)
DROP DATABASE IF EXISTS portfolio_db;
CREATE DATABASE portfolio_db;
USE portfolio_db;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Site content table (for hero, about, contact info)
CREATE TABLE IF NOT EXISTS site_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content_key VARCHAR(100) UNIQUE NOT NULL,
    content_value TEXT,
    content_type ENUM('text', 'textarea', 'image', 'json') DEFAULT 'text',
    section VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image_path VARCHAR(255),
    github_link VARCHAR(255),
    demo_link VARCHAR(255),
    tech_stack TEXT,
    project_date DATE,
    featured BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Services table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    icon_svg TEXT,
    highlighted BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Skills table
CREATE TABLE IF NOT EXISTS skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    percentage INT NOT NULL CHECK (percentage >= 0 AND percentage <= 100),
    category VARCHAR(50) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Testimonials table
CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_title VARCHAR(200),
    testimonial_text TEXT NOT NULL,
    rating INT DEFAULT 5 CHECK (rating >= 1 AND rating <= 5),
    client_image VARCHAR(255),
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact information table
CREATE TABLE IF NOT EXISTS contact_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contact_type VARCHAR(50) NOT NULL,
    contact_value VARCHAR(255) NOT NULL,
    icon_svg TEXT,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Social media links table
CREATE TABLE IF NOT EXISTS social_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(50) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon_svg TEXT,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact form submissions
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    user_agent VARCHAR(512),
    status ENUM('new', 'read', 'replied', 'archived') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contact_messages_created_at (created_at),
    INDEX idx_contact_messages_email (email)
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email) VALUES 
('adem', '$2y$10$nxNGjbx3ql0ffnQMY8kbdeI84jrdYGpNjj24u7TBGrjwx0qlc3DY6', 'adem@example.com');

-- Insert default site content
INSERT INTO site_content (content_key, content_value, content_type, section) VALUES
-- Hero Section
('hero_title', 'Adem Abdifith', 'text', 'hero'),
('hero_subtitle', 'Full-Stack Developer & Tech Consultant', 'text', 'hero'),
('hero_description', 'Transforming ideas into exceptional digital experiences. I help agencies and founders build powerful web applications that drive business growth.', 'textarea', 'hero'),
('hero_image', 'assets/images/mypic.png', 'image', 'hero'),
('cta_primary_text', 'Start Your Project', 'text', 'hero'),
('cta_secondary_text', 'View My Work', 'text', 'hero'),
('stat1_number', '10+', 'text', 'hero'),
('stat1_label', 'Projects Completed', 'text', 'hero'),
('stat2_number', '2+', 'text', 'hero'),
('stat2_label', 'Years Experience', 'text', 'hero'),
('stat3_number', '100%', 'text', 'hero'),
('stat3_label', 'Client Satisfaction', 'text', 'hero'),
('hero_badge_1', 'PHP', 'text', 'hero'),
('hero_badge_2', 'JavaScript', 'text', 'hero'),
('hero_badge_3', 'MySQL', 'text', 'hero'),
('services_heading', 'My Services', 'text', 'services'),
('services_intro', 'Comprehensive web development solutions tailored to your business needs', 'text', 'services'),
('projects_heading', 'Featured Projects', 'text', 'projects'),
('projects_intro', 'Showcasing my latest work and technical expertise across various domains', 'text', 'projects'),
('skills_heading', 'Technical Skills', 'text', 'skills'),
('skills_intro', 'My expertise across different technology stacks and development domains', 'text', 'skills'),
('testimonials_heading', 'Client Testimonials', 'text', 'testimonials'),
('testimonials_intro', 'What clients say about working with me and the quality of my deliverables', 'text', 'testimonials'),
('contact_heading', 'Get In Touch', 'text', 'contact'),
('contact_intro', 'Ready to start your next project? Let\'s discuss how I can help bring your ideas to life', 'text', 'contact'),
('footer_about', 'Full Stack Developer passionate about creating innovative digital solutions. Let\'s work together to bring your ideas to life.', 'textarea', 'footer'),

-- Contact Section
('contact_phone', '+251 912 345 678', 'text', 'contact'),
('contact_email', 'adem@example.com', 'text', 'contact'),
('contact_location', 'Addis Ababa, Ethiopia', 'text', 'contact');

-- Insert default services
INSERT INTO services (title, description, icon_svg, highlighted, sort_order) VALUES
('Web Development', 'Custom web applications built with modern technologies. From simple landing pages to complex e-commerce platforms.', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', FALSE, 1),
('Mobile Development', 'Native and cross-platform mobile applications for iOS and Android. React Native and Flutter expertise.', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="5" y="2" width="14" height="20" rx="2" ry="2" stroke="currentColor" stroke-width="2"/><line x1="12" y1="18" x2="12.01" y2="18" stroke="currentColor" stroke-width="2"/></svg>', FALSE, 2),
('UI/UX Design', 'User-centered design that creates intuitive and engaging user experiences. Wireframes to final prototypes.', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/><path d="M12 1V3" stroke="currentColor" stroke-width="2"/><path d="M12 21V23" stroke="currentColor" stroke-width="2"/><path d="M4.22 4.22L5.64 5.64" stroke="currentColor" stroke-width="2"/><path d="M18.36 18.36L19.78 19.78" stroke="currentColor" stroke-width="2"/><path d="M1 12H3" stroke="currentColor" stroke-width="2"/><path d="M21 12H23" stroke="currentColor" stroke-width="2"/><path d="M4.22 19.78L5.64 18.36" stroke="currentColor" stroke-width="2"/><path d="M18.36 5.64L19.78 4.22" stroke="currentColor" stroke-width="2"/></svg>', FALSE, 3),
('Database Design', 'Efficient database architecture and optimization. MySQL, PostgreSQL, and NoSQL solutions.', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><ellipse cx="12" cy="5" rx="9" ry="3" stroke="currentColor" stroke-width="2"/><path d="M21 12C21 13.66 16.97 15 12 15S3 13.66 3 12" stroke="currentColor" stroke-width="2"/><path d="M3 5V19C3 20.66 7.03 22 12 22S21 20.66 21 19V5" stroke="currentColor" stroke-width="2"/></svg>', FALSE, 4),
('API Development', 'RESTful and GraphQL APIs for seamless integration. Secure, scalable, and well-documented.', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', FALSE, 5),
('Tech Consulting', 'Strategic technology consulting to help businesses choose the right tech stack and architecture for their needs.', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', TRUE, 6);

-- Insert default skills
INSERT INTO skills (name, percentage, category, sort_order) VALUES
-- Frontend
('HTML/CSS', 95, 'Frontend', 1),
('JavaScript', 90, 'Frontend', 2),
-- Backend
('PHP', 85, 'Backend', 1),
('Python', 80, 'Backend', 2),
-- Database
('MySQL', 90, 'Database', 1),
('Oracle', 75, 'Database', 2);

-- Insert default testimonials
INSERT INTO testimonials (client_name, client_title, testimonial_text, rating, sort_order) VALUES
('Sarah Johnson', 'CEO, TechStart Inc.', 'Adem transformed our business with a custom e-commerce platform. His attention to detail and technical expertise exceeded our expectations. The project was delivered on time and under budget.', 5, 1),
('Michael Chen', 'Founder, Digital Solutions', 'Working with Adem was a game-changer for our startup. He not only built our MVP but also provided valuable insights on scaling our technology stack. Highly recommended!', 5, 2),
('Emily Rodriguez', 'Marketing Director, GrowthCo', 'Adem\'s expertise in both frontend and backend development made our project seamless. His communication skills and ability to understand our business needs were exceptional.', 5, 3);

-- Insert default contact information
INSERT INTO contact_info (contact_type, contact_value, icon_svg, sort_order) VALUES
('Phone', '+251 912 345 678', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', 1),
('Email', 'adem@example.com', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', 2),
('Location', 'Addis Ababa, Ethiopia', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="10" r="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', 3);

-- Insert default social media links
INSERT INTO social_links (platform, url, icon_svg, sort_order) VALUES
('Instagram', 'https://instagram.com/adem_abdifith', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke="currentColor" stroke-width="2"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" stroke="currentColor" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke="currentColor" stroke-width="2"/></svg>', 1),
('TikTok', 'https://tiktok.com/@adem_abdifith', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', 2),
('LinkedIn', 'https://linkedin.com/in/adem-abdifith', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><rect x="2" y="9" width="4" height="12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="4" cy="4" r="2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', 3),
('WhatsApp', 'https://wa.me/251912345678', '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>', 4);

-- Insert default projects
INSERT INTO projects (title, description, image_path, github_link, demo_link, tech_stack, project_date, featured, sort_order) VALUES
('E-Commerce Platform', 'A full-featured e-commerce platform with payment integration, inventory management, and admin dashboard. Built with modern web technologies for optimal performance and user experience.', 'assets/images/project1.jpg', 'https://github.com/adem/ecommerce-platform', 'https://demo-ecommerce.adem.com', 'PHP, MySQL, JavaScript, Bootstrap', '2024-01-15', TRUE, 1),
('Task Management App', 'A collaborative task management application with real-time updates, team collaboration features, and progress tracking. Designed for productivity and team efficiency.', 'assets/images/project2.jpg', 'https://github.com/adem/task-manager', 'https://taskmanager.adem.com', 'React, Node.js, MongoDB, Socket.io', '2024-02-20', FALSE, 2),
('Portfolio Website', 'A modern, responsive portfolio website with admin panel for content management. Features dynamic content updates and professional design.', 'assets/images/project3.jpg', 'https://github.com/adem/portfolio-website', 'https://adem-portfolio.com', 'PHP, MySQL, HTML5, CSS3, JavaScript', '2024-03-10', FALSE, 3),
('Weather Dashboard', 'A real-time weather dashboard with location-based forecasts, interactive maps, and historical data visualization. Provides comprehensive weather information.', 'assets/images/project4.jpg', 'https://github.com/adem/weather-dashboard', 'https://weather.adem.com', 'Python, Django, PostgreSQL, Chart.js', '2024-04-05', FALSE, 4); 
