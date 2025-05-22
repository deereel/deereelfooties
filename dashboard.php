<!-- dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit();
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
    <p>Your email: <?= htmlspecialchars($user['email']) ?></p>

    <a href="/auth/logout.php" class="btn btn-danger mt-3">Logout</a>
  </div>
</body>
</html>
