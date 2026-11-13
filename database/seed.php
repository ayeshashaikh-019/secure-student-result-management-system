<?php
require_once __DIR__.'/../config/config.php';
$pdo=db();
$accounts=[
 ['admin','Admin@12345','admin','System Administrator'],
 ['teacher1','Teacher@12345','teacher','Demo Teacher'],
 ['student1','Student@12345','student','Demo Student']
];
foreach($accounts as [$username,$password,$role,$name]){
 $q=$pdo->prepare('SELECT user_id FROM users WHERE username=?');$q->execute([$username]);
 if(!$q->fetchColumn()){
  $pdo->prepare('INSERT INTO users(username,password_hash,role,name) VALUES(?,?,?,?)')->execute([$username,password_hash($password,PASSWORD_DEFAULT),$role,$name]);
  $id=(int)$pdo->lastInsertId();
  if($role==='teacher')$pdo->prepare('INSERT INTO teachers(user_id,department) VALUES(?,?)')->execute([$id,'Computer Science']);
  if($role==='student')$pdo->prepare('INSERT INTO students(user_id,roll_number,department,semester) VALUES(?,?,?,?)')->execute([$id,'SE-001','Computer Science',1]);
 }
}
$pdo->exec("INSERT IGNORE INTO subjects(subject_name,subject_code,credit_hours) VALUES ('Information Security','IS101',3),('Database Systems','DB101',3)");
$pdo->exec("INSERT IGNORE INTO classes(class_name,department,semester) VALUES ('SE-B','Computer Science',1)");
$teacherId=(int)$pdo->query("SELECT teacher_id FROM teachers t JOIN users u ON u.user_id=t.user_id WHERE u.username='teacher1'")->fetchColumn();
$subjectId=(int)$pdo->query("SELECT subject_id FROM subjects WHERE subject_code='IS101'")->fetchColumn();
$classId=(int)$pdo->query("SELECT class_id FROM classes WHERE class_name='SE-B' LIMIT 1")->fetchColumn();
if($teacherId&&$subjectId&&$classId){$s=$pdo->prepare('INSERT IGNORE INTO teacher_subjects(teacher_id,subject_id,class_id) VALUES(?,?,?)');$s->execute([$teacherId,$subjectId,$classId]);}
$studentId=(int)$pdo->query("SELECT student_id FROM students s JOIN users u ON u.user_id=s.user_id WHERE u.username='student1'")->fetchColumn();
if($studentId&&$classId){$s=$pdo->prepare("INSERT IGNORE INTO enrollments(student_id,class_id,academic_session) VALUES(?,?,'2026-2027')");$s->execute([$studentId,$classId]);}
$pdo->exec("INSERT INTO exams(exam_name,semester,academic_session,exam_type) SELECT 'Midterm Examination',1,'2026-2027','Midterm' WHERE NOT EXISTS (SELECT 1 FROM exams WHERE exam_name='Midterm Examination' AND academic_session='2026-2027')");
echo "Demo data ready. Delete this seed file after setup.\\n";
