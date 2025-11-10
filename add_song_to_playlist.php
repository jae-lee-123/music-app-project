<?php
require_once 'db.php';
require_once 'helpers.php';
require_login();

$playlist_id = (int)($_POST['playlist_id'] ?? 0);
$song_id = (int)($_POST['song_id'] ?? 0);
if ($playlist_id <= 0 || $song_id <= 0) {
    header("Location: view_playlist.php?id=$playlist_id");
    exit;
}

// compute next position
$stmt = $mysqli->prepare("SELECT COALESCE(MAX(position), 0) + 1 AS next_pos FROM PlaylistSongs WHERE playlist_id = ?");
$stmt->bind_param("i", $playlist_id);
$stmt->execute();
$next_pos = $stmt->get_result()->fetch_assoc()['next_pos'] ?? 1;

$ins = $mysqli->prepare("INSERT INTO PlaylistSongs (playlist_id, song_id, position) VALUES (?, ?, ?)");
$ins->bind_param("iii", $playlist_id, $song_id, $next_pos);
$ins->execute();

header("Location: view_playlist.php?id=$playlist_id");
exit;
?>
