<?php
require __DIR__ . '/../../includes/database.php';
$subject = 'DATABASE FIRST TEST ' . date('YmdHis');
$saved = saveContactMessage([
    'name' => 'Database First Tester',
    'email' => 'db-test@example.com',
    'subject' => $subject,
    'message' => 'Controlled test of direct contact_messages insertion.',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Database-first verification',
]);
echo 'insert=' . ($saved ? 'PASS' : 'FAIL: ' . portfolioLastError()) . PHP_EOL;
$pdo = getDBConnection();
$stmt = $pdo->prepare('SELECT id, name, email, subject, status FROM contact_messages WHERE subject = ? LIMIT 1');
$stmt->execute([$subject]);
$row = $stmt->fetch();
echo 'mysql row=' . ($row ? 'PASS ' . json_encode($row) : 'FAIL') . PHP_EOL;
if ($row) {
    $delete = $pdo->prepare('DELETE FROM contact_messages WHERE id = ?');
    $delete->execute([$row['id']]);
    echo 'cleanup=' . ($delete->rowCount() === 1 ? 'PASS' : 'FAIL') . PHP_EOL;
}
?>
