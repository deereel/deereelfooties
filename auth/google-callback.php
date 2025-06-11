<?php
$clientId = '156593122800-3h88d3u4unlv80uua3favh5om3uls0nt.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/drf/auth/google-callback.php';

if (!isset($_GET['code'])) {
  die('No code returned from Google.');
}

// Step 1: Exchange code for access token
$tokenResponse = file_get_contents('https://oauth2.googleapis.com/token?' . http_build_query([
  'code' => $_GET['code'],
  'client_id' => $clientId,
  'client_secret' => $clientSecret,
  'redirect_uri' => $redirectUri,
  'grant_type' => 'authorization_code'
]));
$tokenData = json_decode($tokenResponse, true);

if (!isset($tokenData['access_token'])) {
  die('Failed to get access token.');
}

// Step 2: Fetch user info
$accessToken = $tokenData['access_token'];
$userResponse = file_get_contents('https://www.googleapis.com/oauth2/v2/userinfo?access_token=' . $accessToken);
$userData = json_decode($userResponse, true);

if (!isset($userData['email'])) {
  die('Failed to get user info.');
}

// Step 3: Store user in DB or session
require_once 'db.php';

$email = $userData['email'];
$name = $userData['name'] ?? 'Google User';

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If not exists, insert
if (!$user) {
  $insert = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, '')");
  $insert->execute([$name, $email]);
  $user = ['name' => $name, 'email' => $email];
}

header("Location: /dashboard.php");
exit();


// Redirect with user info as JSON (simplified version)
echo "<script>
  localStorage.setItem('DRFUser', JSON.stringify(" . json_encode($user) . "));
  alert('Logged in with Google!');
  window.location.href = '/index.php';
</script>";
?>
