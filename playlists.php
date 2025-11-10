<?php
require_once 'db.php';
require_once 'helpers.php';
require_login();

$user_id = current_user_id();

// fetch playlists
$stmt = $mysqli->prepare("SELECT p.playlist_id, p.title, p.owner_user_id, p.collaborative, p.total_votes, u.username
                          FROM Playlists p JOIN Users u ON p.owner_user_id = u.user_id
                          ORDER BY p.playlist_id ASC");
$stmt->execute();
$res = $stmt->get_result();
$playlists = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Playlists</title></head>
<body>
  <h1>Playlists</h1>
  <p><a href="index.php">Home</a></p>

  <table border="1">
    <tr><th>ID</th><th>Title</th><th>Owner</th><th>Collaborative</th><th>Total Votes</th><th>Actions</th></tr>
    <?php foreach($playlists as $p): ?>
      <tr>
        <td><?= h($p['playlist_id']) ?></td>
        <td><?= h($p['title']) ?></td>
        <td><?= h($p['username']) ?></td>
        <td><?= h($p['collaborative']) ?></td>
        <td><?= h($p['total_votes']) ?></td>
        <td>
          <?php if($p['owner_user_id'] == $user_id): ?>
            <a href="edit_playlist.php?id=<?= h($p['playlist_id']) ?>">Edit</a>
          <?php endif; ?>
          <a href="view_playlist.php?id=<?= h($p['playlist_id']) ?>">View</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>

  <h3>Create Playlist</h3>
  <form method="POST" action="add_playlist.php">
    Title: <input name="title"><br>
    Collaborative: <select name="collaborative"><option value="0">No</option><option value="1">Yes</option></select><br>
    <input type="submit" value="Create">
  </form>
</body>
</html>
