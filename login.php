<?php
require_once 'db.php';
require_once 'helpers.php';

$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $mysqli->prepare("SELECT user_id, password_hash FROM Users WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $hash);
    if ($stmt->fetch() && password_verify($password, $hash)) {
        $_SESSION['user_id'] = $user_id;
        header('Location: index.php');
        exit;
    } else {
        $err = "Invalid username or password";
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title></head>
<body>
  <h1>Login</h1>
  <?php if($err) echo "<p style='color:red;'>".h($err)."</p>"; ?>
  <form method="POST">
    Username: <input name="username"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Login">
  </form>
  <p><a href="index.php">Home</a></p>
</body>
</html>
