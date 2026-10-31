<?php
// index.php - Adem's Freelance Developer Portfolio
session_start();
require_once __DIR__ . '/includes/database.php';

if (empty($_SESSION['contact_csrf'])) {
    $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
}

$contactFlash = $_SESSION['contact_flash'] ?? null;
unset($_SESSION['contact_flash']);

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['contact_form'] ?? '') === '1') {
    $isJsonRequest = strpos((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json') !== false;
    $respond = static function ($success, $message, $status = 200) use ($isJsonRequest) {
        if ($isJsonRequest) {
            http_response_code($status);
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode(['success' => $success, 'message' => $message]);
            exit;
        }

        $_SESSION['contact_flash'] = ['success' => $success, 'message' => $message];
        header('Location: index.php#contact');
        exit;
    };

    if (!hash_equals($_SESSION['contact_csrf'], (string) ($_POST['csrf'] ?? ''))) {
        $respond(false, 'Your session expired. Please refresh the page and try again.', 419);
    }

    // Silently accept the honeypot so automated submissions do not learn the field was detected.
    if (trim((string) ($_POST['website'] ?? '')) !== '') {
        $respond(true, 'Thanks. Your message has been received.');
    }

    $now = time();
    if (!empty($_SESSION['last_contact_submission']) && $now - (int) $_SESSION['last_contact_submission'] < 30) {
        $respond(false, 'Please wait a moment before sending another message.', 429);
    }

    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $subject = trim((string) ($_POST['subject'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));
    $length = static function ($value) {
        return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
    };

    if ($length($name) < 2 || $length($name) > 120) {
        $respond(false, 'Please enter a name between 2 and 120 characters.', 422);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $length($email) > 190) {
        $respond(false, 'Please enter a valid email address.', 422);
    }
    if ($length($subject) < 2 || $length($subject) > 200) {
        $respond(false, 'Please enter a subject between 2 and 200 characters.', 422);
    }
    if ($length($message) < 10 || $length($message) > 10000) {
        $respond(false, 'Please enter a message between 10 and 10,000 characters.', 422);
    }

    $saved = saveContactMessage([
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
        'user_agent' => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 512),
    ]);

    if (!$saved) {
        $respond(false, portfolioLastError() ?: 'We could not save your message right now. Please try again later.', 500);
    }

    $_SESSION['last_contact_submission'] = $now;
    $respond(true, 'Thanks. Your message has been received. I will get back to you soon.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Adem's Freelance Developer Portfolio</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/improvements.css">
  <link rel="stylesheet" href="assets/css/responsive-improvements.css">
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
  <style>html { scroll-behavior: smooth; scroll-padding-top: 86px; }</style>
</head>
<body>
  <nav class="navbar">
    <div class="nav-container">
      <a href="#hero" class="logo">
        <span class="logo-text">Adem</span>
        <span class="logo-separator">|</span>
        <span class="logo-subtitle">Developer</span>
      </a>
      
      <div class="nav-menu" id="nav-menu">
        <ul class="nav-list">
          <li class="nav-item">
            <a href="#hero" class="nav-link active">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <polyline points="9,22 9,12 15,12 15,22" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Home</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#services" class="nav-link">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Services</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#projects" class="nav-link">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M14 2H6A2 2 0 0 0 4 4V20A2 2 0 0 0 6 22H18A2 2 0 0 0 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <line x1="16" y1="13" x2="8" y2="13" stroke="currentColor" stroke-width="2"/>
                <line x1="16" y1="17" x2="8" y2="17" stroke="currentColor" stroke-width="2"/>
                <polyline points="10,9 9,9 8,9" stroke="currentColor" stroke-width="2"/>
              </svg>
              <span>Projects</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#skills" class="nav-link">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Skills</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#testimonials" class="nav-link">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 21C3 17.6863 5.68629 15 9 15C10.6569 15 12 13.6569 12 12C12 10.3431 10.6569 9 9 9C6.23858 9 4 11.2386 4 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M12 21C12 17.6863 14.6863 15 18 15C19.6569 15 21 13.6569 21 12C21 10.3431 19.6569 9 18 9C15.2386 9 13 11.2386 13 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Testimonials</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#contact" class="nav-link">
              <svg class="nav-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 16.92V19.92C22.0011 20.1985 21.9441 20.4742 21.8325 20.7294C21.7209 20.9846 21.5573 21.2136 21.3521 21.4019C21.1469 21.5901 20.9046 21.7335 20.6407 21.8227C20.3769 21.9119 20.0975 21.9452 19.82 21.92C16.7428 21.5856 13.787 20.5341 11.19 18.85C8.77382 17.3146 6.72533 15.2661 5.18999 12.85C3.49997 10.2412 2.44824 7.27099 2.11999 4.18C2.09477 3.90347 2.12787 3.62476 2.21649 3.36162C2.30512 3.09849 2.44756 2.85669 2.63476 2.65171C2.82196 2.44673 3.0498 2.28284 3.30379 2.17052C3.55778 2.0582 3.83233 2.00026 4.10999 2H7.10999C7.59522 1.99522 8.06574 2.16708 8.43376 2.48353C8.80178 2.79999 9.042 3.23945 9.10999 3.72C9.23662 4.68007 9.47144 5.62273 9.80999 6.53C9.94454 6.88792 9.97348 7.27675 9.89381 7.653C9.81415 8.02925 9.62877 8.38336 9.35999 8.67L8.08999 9.94C9.51355 12.5795 11.4205 14.4865 14.06 15.91L15.33 14.64C15.6166 14.3712 15.9708 14.1858 16.347 14.1062C16.7233 14.0265 17.1121 14.0555 17.47 14.19C18.3773 14.5286 19.3199 14.7634 20.28 14.89C20.7658 14.9585 21.2094 15.2032 21.5265 15.5775C21.8437 15.9518 22.0122 16.4296 22 16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Contact</span>
            </a>
          </li>
        </ul>
      </div>
      
      <button class="mobile-menu-btn" id="nav-toggle" aria-label="Toggle mobile menu" aria-expanded="false">
        <span class="menu-bar"></span>
        <span class="menu-bar"></span>
        <span class="menu-bar"></span>
      </button>
    </div>
  </nav>
  <main class="portfolio-main">
    <?php include __DIR__ . '/sections/hero.php'; ?>
    <?php include __DIR__ . '/sections/services.php'; ?>
    <?php include __DIR__ . '/sections/projects.php'; ?>
    <?php include __DIR__ . '/sections/skills.php'; ?>
    <?php include __DIR__ . '/sections/testimonials.php'; ?>
    <?php include __DIR__ . '/sections/contact.php'; ?>
  </main>
  <?php include __DIR__ . '/sections/footer.php'; ?>
  <script src="assets/js/script.js?v=20261031-1"></script>
</body>
</html> 
