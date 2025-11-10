<?php
require_once 'db.php';
require_once 'helpers.php';

$err = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $email === '' || $password === '') {
        $err = "All fields required.";
    } else {
        // check unique username/email
        $stmt = $mysqli->prepare("SELECT user_id FROM Users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $err = "Username or email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $mysqli->prepare("INSERT INTO Users (username, email, display_name, password_hash) VALUES (?, ?, ?, ?)");
            $display = $username;
            $ins->bind_param("ssss", $username, $email, $display, $hash);
            if ($ins->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $err = "Insert failed: " . $mysqli->error;
            }
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title></head>
<body>
  <h1>Register</h1>
  <?php if($err) echo "<p style='color:red;'>".h($err)."</p>"; ?>
  <form method="POST">
    Username: <input name="username"><br>
    Email: <input name="email"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Register">
  </form>
  <p><a href="index.php">Home</a></p>
</body>
</html>
