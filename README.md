# Adem Abdifith - Portfolio Website

A modern, responsive portfolio website with a custom PHP/MySQL admin panel for easy content management.

## 🚀 Features

### Frontend
- **Modern Design**: Glassmorphism effects, smooth animations, and professional styling
- **Responsive Layout**: Optimized for all devices (desktop, tablet, mobile)
- **Dynamic Content**: All content managed through admin panel
- **Interactive Elements**: Smooth scrolling, hover effects, and animations
- **Professional Sections**: Hero, Services, Projects, Skills, Testimonials, Contact, Footer

### Admin Panel
- **Secure Login**: Database-based authentication
- **Content Management**: Edit all website content through admin interface
- **Project Management**: Add, edit, delete projects with images and links
- **Hero Section Editor**: Update profile image, title, description, and statistics
- **Skills Management**: Edit skill percentages and categories
- **Testimonials Management**: Add, edit, delete client testimonials
- **Services Management**: Manage services offered
- **Contact Information**: Update contact details and social media links

### Technical Stack
- **Frontend**: HTML5, CSS3, JavaScript (vanilla)
- **Backend**: PHP (core, no frameworks)
- **Database**: MySQL (via XAMPP)
- **Admin Panel**: Custom PHP/MySQL CMS
- **Design**: Modern UI with glassmorphism effects

## 📋 Prerequisites

- XAMPP (Apache + MySQL + PHP)
- Web browser
- Text editor (VS Code, Sublime Text, etc.)

## 🛠️ Installation

### 1. Setup XAMPP
1. Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Start Apache and MySQL services
3. Open phpMyAdmin at `http://localhost/phpmyadmin`

### 2. Database Setup
1. Create a new database called `portfolio_db`
2. Import the `database.sql` file to set up all tables and default data
3. The database includes:
   - Admin user: `adem` / password: `admin123`
   - Default content for all sections
   - Sample projects, services, skills, and testimonials

### 3. Project Setup
1. Place the project files in your XAMPP htdocs directory:
   ```
   C:\xampp\htdocs\your-portfolio\
   ```
2. Ensure the file structure matches:
   ```
   your-portfolio/
   ├── admin/
   ├── assets/
   ├── includes/
   ├── sections/
   ├── index.php
   └── database.sql
   ```

### 4. Configuration
1. Update database connection in `includes/database.php` if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'portfolio_db');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

## 🌐 Access

### Main Website
- URL: `http://localhost/your-portfolio/`
- Features: Dynamic content, responsive design, professional portfolio

### Admin Panel
- URL: `http://localhost/your-portfolio/admin/`
- Login: `adem` / `admin123`
- Features: Complete content management system

## 📁 File Structure

```
your-portfolio/
├── admin/                          # Admin panel files
│   ├── dashboard.php              # Main admin dashboard
│   ├── edit-hero.php             # Hero section editor
│   ├── edit-projects.php         # Projects management
│   ├── index.php                 # Admin login page
│   ├── login.php                 # Login handler
│   └── logout.php                # Logout handler
├── assets/                        # Static assets
│   ├── css/
│   │   └── style.css             # Main stylesheet
│   ├── js/
│   │   └── script.js             # JavaScript functionality
│   └── images/                   # Images and uploads
│       ├── mypic.png            # Background image
│       └── profile.jpg          # Profile image (uploaded)
├── includes/                      # PHP includes
│   └── database.php              # Database connection and helpers
├── sections/                      # Website sections
│   ├── hero.php                  # Hero section
│   ├── services.php              # Services section
│   ├── projects.php              # Projects section
│   ├── skills.php                # Skills section
│   ├── testimonials.php          # Testimonials section
│   ├── contact.php               # Contact section
│   └── footer.php                # Footer section
├── index.php                     # Main website file
├── database.sql                  # Database schema and data
└── README.md                     # This file
```

## 🎨 Design Features

### Visual Design
- **Color Scheme**: #AD8B73 (warm brown) and #FFFBE9 (cream)
- **Typography**: Google Fonts (Sora for headings, Lora for body text)
- **Effects**: Glassmorphism, gradients, shadows, and smooth animations
- **Background**: Custom background image with overlay effects

### Responsive Design
- **Desktop**: Full-featured layout with grid systems
- **Tablet**: Optimized spacing and touch-friendly elements
- **Mobile**: Stacked layout with mobile-first navigation

### Interactive Elements
- **Smooth Scrolling**: Navigation links with smooth scroll behavior
- **Hover Effects**: Cards, buttons, and links with engaging hover states
- **Animations**: CSS keyframes for loading and interaction effects
- **Skill Bars**: Animated progress bars for skills section

## 🔧 Admin Panel Features

### Content Management
- **Hero Section**: Edit title, subtitle, description, stats, and profile image
- **Projects**: Add, edit, delete projects with images, links, and tech stacks
- **Services**: Manage services with icons and descriptions
- **Skills**: Edit skill percentages and categories
- **Testimonials**: Manage client testimonials with ratings
- **Contact Info**: Update contact details and social media links

### Security Features
- **Session Management**: Secure admin sessions
- **Password Hashing**: Bcrypt password hashing
- **Input Validation**: Sanitized inputs and prepared statements
- **Access Control**: Admin-only access to management pages

## 🚀 Customization

### Adding New Sections
1. Create new section file in `sections/` directory
2. Add section to `index.php` with include statement
3. Add corresponding CSS styles to `assets/css/style.css`
4. Create admin management page if needed

### Modifying Styles
- Main stylesheet: `assets/css/style.css`
- Color variables: Update CSS custom properties
- Responsive breakpoints: Modify media queries
- Animations: Edit keyframe definitions

### Database Modifications
- Schema: Update `database.sql` for new tables
- Connection: Modify `includes/database.php` for new helpers
- Admin pages: Add CRUD operations for new content types

## 🔒 Security Notes

- Change default admin password after first login
- Keep XAMPP updated for security patches
- Use HTTPS in production environment
- Regularly backup database and files
- Validate and sanitize all user inputs

## 📞 Support

For technical support or customization requests:
- Email: adem@example.com
- GitHub: [Your GitHub Profile]
- LinkedIn: [Your LinkedIn Profile]

## 📄 License

This project is for personal/portfolio use. Please respect the original design and code structure.

---

**Built with ❤️ by Adem Abdifith** 