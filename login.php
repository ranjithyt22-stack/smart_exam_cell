<?php
// public/login.php
require_once __DIR__ . '/../inc/config.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $mysqli->real_escape_string(trim($_POST['username']));
    $password = $_POST['password'];
    $res = $mysqli->query("SELECT id, password_hash, role FROM users WHERE username = '$username' LIMIT 1");
    if($res && $row = $res->fetch_assoc()){
        if(password_verify($password, $row['password_hash'])){
            $_SESSION['user_id'] = $row['id'];
            // redirect by role
            if($row['role']==='admin') header("Location: dashboard_admin.php");
            elseif($row['role']==='faculty') header("Location: dashboard_faculty.php");
            else header("Location: dashboard_student.php");
            exit;
        }
    }
    $error = "Invalid credentials.";
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login - Smart Exam Cell</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main class="container">
    <h1>Smart Exam Cell — Login</h1>
    <?php if(!empty($error)): ?><div class="alert"><?=$error?></div><?php endif; ?>
    <form method="post" class="card">
      <label>Username<input name="username" required></label>
      <label>Password<input name="password" type="password" required></label>
      <button type="submit">Login</button>
    </form>
    <p>Use admin / Admin@123 (after running seed.php) or any seeded account.</p>
  </main>
</body>
</html>
