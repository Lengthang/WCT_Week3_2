<?php
session_start();

class Student{
    
}

// Load existing students from a JSON file
function loadStudents() {
    if (file_exists("students.json")) {
        $data = file_get_contents("students.json");
        return json_decode($data, true) ?: [];
    }
    return [];
}

// Save students to JSON file
function saveStudents($students) {
    file_put_contents("students.json", json_encode($students, JSON_PRETTY_PRINT));
}

$students = loadStudents();

// Add Student
if (isset($_POST['add'])) {
    $newStudent = [
        "id" => uniqid(),
        "name" => $_POST['name'],
        "age" => $_POST['age'],
        "class" => $_POST['class']
    ];
    $students[] = $newStudent;
    saveStudents($students);
}

// Delete Student
if (isset($_GET['delete'])) {
    $students = array_filter($students, fn($s) => $s['id'] !== $_GET['delete']);
    saveStudents($students);
}

// Edit Student
if (isset($_POST['edit'])) {
    foreach ($students as &$student) {
        if ($student['id'] === $_POST['id']) {
            $student['name'] = $_POST['name'];
            $student['age'] = $_POST['age'];
            $student['class'] = $_POST['class'];
            break;
        }
    }
    saveStudents($students);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Classroom Management</title>
</head>
<body>
    <h2>Student Management</h2>
    <form method="POST">
        <input type="hidden" name="id" id="studentId">
        Name: <input type="text" name="name" id="name" required>
        Age: <input type="number" name="age" id="age" required>
        Class: <input type="text" name="class" id="class" required>
        <button type="submit" name="add">Add Student</button>
        <button type="submit" name="edit">Update Student</button>
    </form>
    
    <h3>All Students</h3>
    <table border="1">
        <tr><th>Name</th><th>Age</th><th>Class</th><th>Actions</th></tr>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['name']) ?></td>
                <td><?= htmlspecialchars($student['age']) ?></td>
                <td><?= htmlspecialchars($student['class']) ?></td>
                <td>
                    <a href="?delete=<?= $student['id'] ?>">Delete</a>
                    <button onclick="editStudent('<?= $student['id'] ?>', '<?= $student['name'] ?>', '<?= $student['age'] ?>', '<?= $student['class'] ?>')">Edit</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <script>
        function editStudent(id, name, age, className) {
            document.getElementById('studentId').value = id;
            document.getElementById('name').value = name;
            document.getElementById('age').value = age;
            document.getElementById('class').value = className;
        }
    </script>
</body>
</html>
