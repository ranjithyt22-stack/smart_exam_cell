<?php
// public/faculty.php
require_once __DIR__.'/../inc/functions.php';
$u = current_user();
if(!$u || $u['role']!=='admin'){ header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['create_faculty'])){
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $fullname = $mysqli->real_escape_string($_POST['full_name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $passhash = password_hash($password, PASSWORD_DEFAULT);
    $mysqli->query("INSERT INTO users (username,password_hash,full_name,email,role) VALUES ('$username','$passhash','$fullname','$email','faculty')");
    $uid = $mysqli->insert_id;
    $emp = $mysqli->real_escape_string($_POST['employee_no']);
    $dept = $mysqli->real_escape_string($_POST['department']);
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $mysqli->query("INSERT INTO faculty (faculty_id,employee_no,department,phone) VALUES ($uid,'$emp','$dept','$phone')");
    header("Location: faculty.php");
    exit;
}

if(isset($_GET['action']) && $_GET['action']==='delete'){
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM users WHERE id=$id");
    header("Location: faculty.php");
    exit;
}

$res = $mysqli->query("SELECT u.id,u.username,u.full_name,u.email,f.employee_no,f.department,f.phone 
                       FROM users u JOIN faculty f ON u.id=f.faculty_id ORDER BY u.id DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8"><title>Faculty Management</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header><a href="dashboard_admin.php">Dashboard</a> | <a href="logout.php">Logout</a></header>
<main class="container">
<h1>Faculty Management</h1>
<section class="card">
<h2>Add Faculty</h2>
<form method="post">
  <label>Username<input name="username" required></label>
  <label>Password<input name="password" type="password" required></label>
  <label>Full name<input name="full_name" required></label>
  <label>Email<input name="email" type="email"></label>
  <label>Employee No<input name="employee_no" required></label>
  <label>Department<input name="department" value="Computer Science"></label>
  <label>Phone<input name="phone"></label>
  <button name="create_faculty" type="submit">Create</button>
</form>
</section>

<section class="card">
<h2>All Faculty</h2>
<table>
<thead><tr><th>ID</th><th>Username</th><th>Name</th><th>Email</th><th>Employee No</th><th>Dept</th><th>Phone</th><th>Action</th></tr></thead>
<tbody>
<?php while($row=$res->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['username']) ?></td>
<td><?= htmlspecialchars($row['full_name']) ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['employee_no']) ?></td>
<td><?= htmlspecialchars($row['department']) ?></td>
<td><?= htmlspecialchars($row['phone']) ?></td>
<td><a href="?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Delete faculty?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</section>
</main>
</body></html>
