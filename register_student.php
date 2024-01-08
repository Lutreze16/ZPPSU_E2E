<?php
include 'config.php'; // Include your database configuration file

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = htmlspecialchars($_POST['first_name']);
    $middle_name = htmlspecialchars($_POST['middle_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $student_id = htmlspecialchars($_POST['student_id']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $course = htmlspecialchars($_POST['course']);
    $year_level = htmlspecialchars($_POST['new_year_level']);

    // Prepare and execute SQL query
    $stmt = $conn->prepare("INSERT INTO students (first_name, middle_name, last_name, student_id, password, course, year_level) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt->bind_param("ssssssi", $first_name, $middle_name, $last_name, $student_id, $password, $course, $year_level) && $stmt->execute()) {
        // Registration successful, redirect to login_student.php
        header("Location: login_student.php");
        exit();
    } else {
        $errorMessage = "Error: Unable to register. Please try again.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup | For Students</title>
    <link rel="stylesheet" type="text/css" href="css/signup.css">
    <link rel="icon" href="img/zppsu-seal.png" type="image/png">
</head>
<body>
    <div class="container">
        <h2>Student Sign Up</h2>
        <?php if (!empty($errorMessage)) : ?>
            <p style="color: red;"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1) : ?>
            <p style="color: green;">Registration successful!</p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Your form fields go here -->
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="middle_name" placeholder="Middle Name">
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="number" name="student_id" placeholder="Student ID No." required>
            <input type="password" name="password" placeholder="Password" required>
            <label>Course:</label>
            <select name="course" required>
                <option value="BS-InfoTech">BS Information Technology</option>
                <option value="BS-CompTech">BS Computer Technology</option>
                <option value="BEED">Bachelor of Elementary Education</option>
                <option value="BSED">Bachelor of Science in Secondary Education</option>
                <option value="BSCE">Bachelor of Science in Civil Engineering</option>
                <option value="BSME">Bachelor of Science in Marine Engineering</option>
            </select>
            <label for="new_year_level">
                Year Level:</label>
            <select name="new_year_level" id="new_year_level" required>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
                <option value="5">5th Year</option>
            </select>
            <br>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
