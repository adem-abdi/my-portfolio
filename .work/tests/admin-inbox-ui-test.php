<?php
require __DIR__ . '/../../includes/database.php';
$subject = 'DASHBOARD UI TEST ' . date('YmdHis');
$saved = saveContactMessage([
    'name' => 'Dashboard UI Tester',
    'email' => 'dashboard-test@example.com',
    'subject' => $subject,
    'message' => 'Temporary message used to verify the interactive admin inbox.',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Dashboard UI verification',
]);
echo 'save=' . ($saved ? 'PASS' : 'FAIL') . PHP_EOL;
$messages = getContactMessages();
$testMessage = null;
foreach ($messages as $message) {
    if (($message['subject'] ?? '') === $subject) {
        $testMessage = $message;
        break;
    }
}
echo 'stored=' . ($testMessage ? 'PASS' : 'FAIL') . PHP_EOL;
if ($testMessage) {
    session_start();
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = 'dashboard-check';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    ob_start();
    include __DIR__ . '/../../admin/dashboard-main.php';
    $html = ob_get_clean();
    echo 'clickable details=' . (strpos($html, 'message-card') !== false ? 'PASS' : 'FAIL') . PHP_EOL;
    echo 'status action=' . (strpos($html, 'update_message') !== false ? 'PASS' : 'FAIL') . PHP_EOL;
    echo 'reply action=' . (strpos($html, 'mailto:dashboard-test@example.com') !== false ? 'PASS' : 'FAIL') . PHP_EOL;
    echo 'delete action=' . (strpos($html, 'delete_message') !== false ? 'PASS' : 'FAIL') . PHP_EOL;
    echo 'message subject=' . (strpos($html, htmlspecialchars($subject, ENT_QUOTES, 'UTF-8')) !== false ? 'PASS' : 'FAIL') . PHP_EOL;
    echo 'cleanup=' . (deleteContactMessage($testMessage['id']) ? 'PASS' : 'FAIL') . PHP_EOL;
}
?>
