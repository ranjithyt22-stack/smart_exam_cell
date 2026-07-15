<?php
// public/students.php
require_once __DIR__.'/../inc/functions.php';
$u = current_user();
if(!$u || $u['role']!=='admin'){ header("Location: login.php"); exit; }

$action = $_GET['action'] ?? null;
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['create_student'])){
    // create user + student
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $fullname = $mysqli->real_escape_string($_POST['full_name']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $role = 'student';
    $passhash = password_hash($password, PASSWORD_DEFAULT);
    $mysqli->query("INSERT INTO users (username,password_hash,full_name,email,role) VALUES ('$username','$passhash','$fullname','$email','$role')");
    $uid = $mysqli->insert_id;
    $roll = $mysqli->real_escape_string($_POST['roll_no']);
    $dept = $mysqli->real_escape_string($_POST['department']);
    $sem = (int)$_POST['semester'];
    $phone = $mysqli->real_escape_string($_POST['phone']);
    $mysqli->query("INSERT INTO students (student_id, roll_no, course_year, department, semester, phone) VALUES ($uid,'$roll','2024-2027','$dept',$sem,'$phone')");
    header("Location: students.php");
    exit;
}
if($action === 'delete' && isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM users WHERE id=$id");
    header("Location: students.php");
    exit;
}

// list students
$res = $mysqli->query("SELECT u.id,u.username,u.full_name,u.email,s.roll_no,s.department,s.semester FROM users u JOIN students s ON u.id = s.student_id ORDER BY u.id DESC LIMIT 200");
?>
<!doctype html><html><head><meta charset="utf-8"><title>Students</title><link rel="stylesheet" href="styles.css"></head><body>
<header><a href="dashboard_admin.php">Back to Dashboard</a> | <a href="logout.php">Logout</a></header>
<main class="container">
  <h1>Students</h1>
  <section class="card">
    <h2>Add Student</h2>
    <form method="post">
      <label>Username<input name="username" required></label>
      <label>Password<input name="password" type="password" required></label>
      <label>Full name<input name="full_name" required></label>
      <label>Email<input name="email" type="email"></label>
      <label>Roll no<input name="roll_no" required></label>
      <label>Department<input name="department" value="Computer Science"></label>
      <label>Semester<input name="semester" type="number" value="3"></label>
      <label>Phone<input name="phone"></label>
      <button name="create_student" type="submit">Create</button>
    </form>
  </section>

  <section class="card">
    <h2>All students</h2>
    <table>
      <thead><tr><th>ID</th><th>Username</th><th>Name</th><th>Roll</th><th>Dept</th><th>Sem</th><th>Action</th></tr></thead>
      <tbody>
<?php while($row = $res->fetch_assoc()): ?>
<tr>
  <td><?=htmlspecialchars($row['id'])?></td>
  <td><?=htmlspecialchars($row['username'])?></td>
  <td><?=htmlspecialchars($row['full_name'])?></td>
  <td><?=htmlspecialchars($row['roll_no'])?></td>
  <td><?=htmlspecialchars($row['department'])?></td>
  <td><?=htmlspecialchars($row['semester'])?></td>
  <td><a href="students.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td>
</tr>
<?php endwhile; ?>
      </tbody>
    </table>
  </section>
</main></body></html>
