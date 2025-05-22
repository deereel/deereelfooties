<?php
$clientId = '156593122800-3h88d3u4unlv80uua3favh5om3uls0nt.apps.googleusercontent.com';
$redirectUri = 'http://localhost/drf/auth/google-callback.php'; // use your domain in production
$scope = 'email profile';
$responseType = 'code';

$url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
  'client_id' => $clientId,
  'redirect_uri' => $redirectUri,
  'response_type' => $responseType,
  'scope' => $scope,
  'access_type' => 'offline',
  'prompt' => 'consent'
]);

header('Location: ' . $url);
exit;
?>
