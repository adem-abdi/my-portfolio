<?php
session_start();
$_SESSION['contact_flash'] = ['success' => true, 'message' => 'Test feedback is visible.'];
ob_start();
include dirname(__DIR__, 2) . '/index.php';
$html = ob_get_clean();
echo strpos($html, 'Test feedback is visible.') !== false ? 'Flash feedback rendered.' : 'Flash feedback missing.';
?>
