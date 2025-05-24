<!-- dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: index.php');
  exit();
}
$user = $_SESSION['user'];
?>

<?php include('components/header.php'); ?>

<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>
    <p>Your email: <?= htmlspecialchars($user['email']) ?></p>

    <a href="/auth/logout.php" class="btn btn-danger mt-3">Logout</a>
  </div>
</body>
</html>
