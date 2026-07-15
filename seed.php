<?php
// seed.php — run once to populate demo data
$mysqli = new mysqli("localhost","root","","smart_exam_cell");
if ($mysqli->connect_errno) { die("DB connect failed: ".$mysqli->connect_error); }

function create_user($mysqli,$username,$password,$full,$email,$role){
    $pass = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("INSERT INTO users (username,password_hash,full_name,email,role) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sssss",$username,$pass,$full,$email,$role);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

// recreate admin with secure password
$mysqli->query("DELETE FROM users WHERE username='admin'");
$admin_id = create_user($mysqli,'admin','Admin@123','System Administrator','admin@example.com','admin');
echo "Created admin id=$admin_id\n";

// create several faculty
for($i=1;$i<=10;$i++){
    $u = "faculty".$i;
    $id = create_user($mysqli, $u, "FacPass{$i}", "Faculty $i", "{$u}@example.com", "faculty");
    $mysqli->query("INSERT INTO faculty (faculty_id, employee_no, department, phone) VALUES ($id, 'EMP-".(1000+$i)."','Computer Science','9".(random_int(100000000,999999999))."')");
    echo "Faculty $u id=$id\n";
}

// create many students
for($i=1;$i<=120;$i++){
    $u = "student{$i}";
    $id = create_user($mysqli, $u, "StudPass{$i}", "Student $i", "{$u}@example.com", "student");
    $roll = "24CS".str_pad($i,3,"0",STR_PAD_LEFT);
    $sem = ($i%6)+1;
    $mysqli->query("INSERT INTO students (student_id, roll_no, course_year, department, semester, phone) VALUES ($id,'$roll','2024-2027','Computer Science',$sem,'9".(random_int(100000000,999999999))."')");
    echo "Student $u id=$id\n";
}

// create sample courses if not exists
$mysqli->query("INSERT IGNORE INTO courses (code,title,credits,semester,department) VALUES
('CS301','Database Management Systems',4.0,3,'Computer Science'),
('CS302','Operating Systems',4.0,3,'Computer Science'),
('CS303','Computer Networks',3.0,3,'Computer Science')");

echo "Done seeding.\n";
$mysqli->close();
?>
