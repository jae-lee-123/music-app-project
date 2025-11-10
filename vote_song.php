<?php
require_once 'db.php';
require_once 'helpers.php';
require_login();

$user_id = current_user_id();
$song_id = (int)($_POST['song_id'] ?? 0);
$vote_value = (int)($_POST['vote'] ?? 0);
if (!in_array($vote_value, [1, -1]) || $song_id <= 0) {
    header('Location: songs.php');
    exit;
}

// Upsert vote
$stmt = $mysqli->prepare("INSERT INTO SongVotes (user_id, song_id, vote_value) VALUES (?, ?, ?)
                          ON DUPLICATE KEY UPDATE vote_value = VALUES(vote_value), voted_at = CURRENT_TIMESTAMP");
$stmt->bind_param("iii", $user_id, $song_id, $vote_value);
$stmt->execute();

// the triggers in DB will update Songs.total_votes if present
header('Location: songs.php');
exit;
?>
