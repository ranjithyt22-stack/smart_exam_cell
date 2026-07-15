<?php
// public/courses.php
require_once __DIR__.'/../inc/functions.php';
$u = current_user();
if(!$u || $u['role']!=='admin'){ header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['create_course'])){
    $code = $mysqli->real_escape_string($_POST['code']);
    $title = $mysqli->real_escape_string($_POST['title']);
    $credits = floatval($_POST['credits']);
    $sem = intval($_POST['semester']);
    $dept = $mysqli->real_escape_string($_POST['department']);
    $mysqli->query("INSERT INTO courses (code,title,credits,semester,department) VALUES ('$code','$title',$credits,$sem,'$dept')");
    header("Location: courses.php");
    exit;
}

if(isset($_GET['action']) && $_GET['action']==='delete'){
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM courses WHERE id=$id");
    header("Location: courses.php");
    exit;
}

$res = $mysqli->query("SELECT * FROM courses ORDER BY semester");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Courses</title>
<link rel="stylesheet" href="styles.css"></head>
<body>
<header><a href="dashboard_admin.php">Dashboard</a> | <a href="logout.php">Logout</a></header>
<main class="container">
<h1>Courses</h1>
<section class="card">
<h2>Add Course</h2>
<form method="post">
  <label>Course Code<input name="code" required></label>
  <label>Title<input name="title" required></label>
  <label>Credits<input name="credits" type="number" step="0.5" value="3.0"></label>
  <label>Semester<input name="semester" type="number" value="3"></label>
  <label>Department<input name="department" value="Computer Science"></label>
  <button name="create_course" type="submit">Add</button>
</form>
</section>

<section class="card">
<h2>All Courses</h2>
<table>
<thead><tr><th>ID</th><th>Code</th><th>Title</th><th>Credits</th><th>Semester</th><th>Dept</th><th>Action</th></tr></thead>
<tbody>
<?php while($c=$res->fetch_assoc()): ?>
<tr>
<td><?= $c['id'] ?></td>
<td><?= htmlspecialchars($c['code']) ?></td>
<td><?= htmlspecialchars($c['title']) ?></td>
<td><?= htmlspecialchars($c['credits']) ?></td>
<td><?= htmlspecialchars($c['semester']) ?></td>
<td><?= htmlspecialchars($c['department']) ?></td>
<td><a href="?action=delete&id=<?= $c['id'] ?>" onclick="return confirm('Delete course?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</section>
</main>
</body></html>
