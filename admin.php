<?php
require_once 'db.php';
session_start();

$stmt = $pdo->prepare("SELECT user_role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$role = $stmt->fetchColumn();

if ($role !== 'admin') die("Access denied.");

$users = $pdo->query("SELECT id, email, credits, created_at FROM users")->fetchAll();
$usage = $pdo->query("SELECT user_id, COUNT(*) as total FROM usage_log GROUP BY user_id")->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<h2>Admin Dashboard</h2>
<table border="1">
<tr><th>Email</th><th>Credits</th><th>Created</th><th>Usage</th></tr>
<?php foreach ($users as $u): ?>
<tr>
  <td><?= htmlspecialchars($u['email']) ?></td>
  <td><?= $u['credits'] ?></td>
  <td><?= $u['created_at'] ?></td>
  <td><?= $usage[$u['id']] ?? 0 ?></td>
</tr>
<?php endforeach; ?>
</table>
