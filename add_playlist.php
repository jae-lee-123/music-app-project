<?php
require_once 'db.php';
require_once 'helpers.php';
require_login();

$title = trim($_POST['title'] ?? '');
$collab = isset($_POST['collaborative']) ? (int)$_POST['collaborative'] : 0;
$owner = current_user_id();
if ($title === '') {
    header('Location: playlists.php');
    exit;
}
$stmt = $mysqli->prepare("INSERT INTO Playlists (owner_user_id, title, collaborative) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $owner, $title, $collab);
$stmt->execute();
header('Location: playlists.php');
exit;
?>
