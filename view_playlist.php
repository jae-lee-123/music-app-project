<?php
require_once 'db.php';
require_once 'helpers.php';
require_login();

$playlist_id = (int)($_GET['id'] ?? 0);
if ($playlist_id <= 0) {
    header('Location: playlists.php');
    exit;
}

// fetch playlist info
$stmt = $mysqli->prepare("SELECT p.playlist_id, p.title, p.owner_user_id, p.collaborative, p.total_votes, u.username
                          FROM Playlists p JOIN Users u ON p.owner_user_id = u.user_id WHERE p.playlist_id = ? LIMIT 1");
$stmt->bind_param("i", $playlist_id);
$stmt->execute();
$playlist = $stmt->get_result()->fetch_assoc();

if (!$playlist) {
    echo "Playlist not found.";
    exit;
}

// fetch songs in playlist
$stmt2 = $mysqli->prepare("SELECT ps.position, s.song_id, s.song_title, s.duration_sec
                           FROM PlaylistSongs ps JOIN Songs s ON ps.song_id = s.song_id
                           WHERE ps.playlist_id = ? ORDER BY ps.position ASC");
$stmt2->bind_param("i", $playlist_id);
$stmt2->execute();
$songs = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Playlist <?= h($playlist['title']) ?></title></head>
<body>
  <h1><?= h($playlist['title']) ?></h1>
  <p>Owner: <?= h($playlist['username']) ?> | Collaborative: <?= h($playlist['collaborative']) ?></p>
  <p><a href="playlists.php">Back to Playlists</a></p>

  <h3>Songs</h3>
  <table border="1">
    <tr><th>Position</th><th>Song Title</th><th>Duration</th></tr>
    <?php foreach($songs as $s): ?>
      <tr>
        <td><?= h($s['position']) ?></td>
        <td><?= h($s['song_title']) ?></td>
        <td><?= h($s['duration_sec']) ?> sec</td>
      </tr>
    <?php endforeach; ?>
  </table>

  <?php if($playlist['owner_user_id'] == current_user_id() || $playlist['collaborative']): ?>
    <h4>Add song to playlist (by song_id)</h4>
    <form method="POST" action="add_song_to_playlist.php">
      <input type="hidden" name="playlist_id" value="<?= h($playlist_id) ?>">
      Song ID: <input name="song_id"><br>
      <input type="submit" value="Add">
    </form>
  <?php endif; ?>

</body>
</html>
