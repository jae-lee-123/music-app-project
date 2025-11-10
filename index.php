<?php
require_once 'db.php';
require_once 'helpers.php';
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>MusicApp</title>
</head>
<body>
  <h1>MusicApp</h1>
  <?php if(isset($_SESSION['user_id'])): ?>
    <p>Logged in as user_id = <?= h($_SESSION['user_id']) ?> — <a href="logout.php">Logout</a></p>
  <?php else: ?>
    <p><a href="login.php">Login</a> | <a href="register.php">Register</a></p>
  <?php endif; ?>

  <ul>
    <li><a href="playlists.php">Playlists (view/add/manage)</a></li>
    <li><a href="songs.php">Songs (view/vote)</a></li>
  </ul>
</body>
</html>
