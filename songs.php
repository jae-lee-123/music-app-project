<?php
require_once 'db.php';
require_once 'helpers.php';
require_login();

// list songs and allow voting
$res = $mysqli->query("SELECT s.song_id, s.song_title, s.duration_sec, s.total_votes, GROUP_CONCAT(a.artist_name) AS artists
                       FROM Songs s
                       LEFT JOIN SongArtists sa ON s.song_id = sa.song_id
                       LEFT JOIN Artists a ON sa.artist_id = a.artist_id
                       GROUP BY s.song_id
                       ORDER BY s.song_id ASC
                       LIMIT 200");
$songs = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Songs</title></head>
<body>
  <h1>Songs</h1>
  <p><a href="index.php">Home</a></p>
  <table border="1">
    <tr><th>ID</th><th>Title</th><th>Artists</th><th>Duration</th><th>Votes</th><th>Action</th></tr>
    <?php foreach($songs as $s): ?>
      <tr>
        <td><?= h($s['song_id']) ?></td>
        <td><?= h($s['song_title']) ?></td>
        <td><?= h($s['artists']) ?></td>
        <td><?= h($s['duration_sec']) ?> sec</td>
        <td><?= h($s['total_votes']) ?></td>
        <td>
          <form method="POST" action="vote_song.php" style="display:inline;">
            <input type="hidden" name="song_id" value="<?= h($s['song_id']) ?>">
            <button name="vote" value="1">Upvote</button>
            <button name="vote" value="-1">Downvote</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
