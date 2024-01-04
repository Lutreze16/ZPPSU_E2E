<?php
include('config.php');

session_start();

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessage = ""; // Variable to store error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = isset($_POST["student_id"]) ? $_POST["student_id"] : "";
    $course = isset($_POST["course"]) ? $_POST["course"] : "";

    // Validate user credentials
    $sql = "SELECT * FROM students WHERE student_id = '$student_id' AND course = '$course'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    if ($result->num_rows == 1) {
        // Successful login
        $_SESSION['student_id'] = $student_id;
        $_SESSION['course'] = $course;
        header("Location: student_dashboard.php");
        exit();
    } else {
        $errorMessage = "Invalid login credentials.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | For Students</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="number" name="student_id" placeholder="Student ID No." required>
            <br>
            <label>Course:</label>
            <select name="course" required>
                <option value="BS-InfoTech">BS Information Technology</option>
                <!-- Include other course options here -->
            </select>
            <br>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($errorMessage)) echo "<p>$errorMessage</p>"; ?>
    </div>
</body>
</html>