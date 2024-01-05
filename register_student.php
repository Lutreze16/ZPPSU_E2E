<?php
include('config.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : "";
    $middle_name = isset($_POST["middle_name"]) ? $_POST["middle_name"] : "";
    $last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
    $student_id = isset($_POST["student_id"]) ? $_POST["student_id"] : "";
    $course = isset($_POST["course"]) ? $_POST["course"] : "";
    $password = isset($_POST["password"]) ? password_hash($_POST["password"], PASSWORD_DEFAULT) : ""; // Hash the password

    // Check if the referenced student_id exists in the students table
    $checkStudentQuery = "SELECT id FROM students WHERE id = $student_id";
    $result = $conn->query($checkStudentQuery);

    if ($result->num_rows == 0) {
        echo "Error: The referenced student_id does not exist.";
    } else {
        // Insert the new student
        $sql = "INSERT INTO students (first_name, middle_name, last_name, student_id, course, password) VALUES ('$first_name', '$middle_name', '$last_name', $student_id, '$course', '$password')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "Registration successful!";
            header("Location: login_student.php"); // Redirect to login_student.php
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | For Students</title>
    <link rel="stylesheet" type="text/css" href="css/signup.css">
</head>
<body>
    <div class="container">
        <h2>Student Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="first_name" placeholder="First Name">
            <input type="text" name="middle_name" placeholder="Middle Name">
            <input type="text" name="last_name" placeholder="Last Name">
            <input type="number" name="student_id" placeholder="Student ID No.">
            <input type="password" name="password" placeholder="Password" required>
            <label>Course:</label>
            <select name="course">
                <option value="BS-InfoTech">BS Information Technology</option>
                <option value="BS-CompTech">BS Computer Technology</option>
                <option value="BEED">Bachelor of Elementary Education</option>
                <option value="BSED">Bachelor of Science in Secondary Education</option>
                <option value="BSCE">Bachelor of Science in Civil Engineering</option>
                <option value="BSME">Bachelor of Science in Marine Engineering</option>
            </select>
            <label for="new_year_level">
                Year Level:</label>
            <select name="new_year_level" id="new_year_level">
                <option value="1" <?php echo ($year_level == 1) ? 'selected' : ''; ?>>1st Year</option>
                <option value="2" <?php echo ($year_level == 2) ? 'selected' : ''; ?>>2nd Year</option>
                <option value="3" <?php echo ($year_level == 3) ? 'selected' : ''; ?>>3rd Year</option>
                <option value="4" <?php echo ($year_level == 4) ? 'selected' : ''; ?>>4th Year</option>
                <option value="5" <?php echo ($year_level == 5) ? 'selected' : ''; ?>>5th Year</option>
            </select>
            <br>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
