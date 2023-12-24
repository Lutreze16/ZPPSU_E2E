<?php
include('config.php'); // Include the database connection file

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = ""; // Variable to store success message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form fields are set
    $first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : "";
    $middle_name = isset($_POST["middle_name"]) ? $_POST["middle_name"] : "";
    $last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
    $student_id = isset($_POST["student_id"]) ? $_POST["student_id"] : "";
    $course = isset($_POST["course"]) ? $_POST["course"] : "";

    // SQL query to insert data into the database
    $sql = "INSERT INTO students (first_name, middle_name, last_name, student_id, course) VALUES ('$first_name', '$middle_name', '$last_name', $student_id, '$course')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        $successMessage = "Registration successful!";
        
        // Redirect to student_dashboard.php
        header("Location: student_dashboard.php");
        exit(); // Ensure that no further code is executed after the redirect
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <title>Signup | For Students</title>
    <link rel="stylesheet" type="text/css" href="css/signup.css">
</head>
<body>
    <div class="container">
        <h2>Student Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Student registration form fields go here -->
            <input type="text" name="first_name" placeholder="First Name">
            <input type="text" name="middle_name" placeholder="Middle Name">
            <input type="text" name="last_name" placeholder="Last Name"><br>
            <input type="number" name="student_id" placeholder="Student ID No."><br>
            <label>Course:</label>
            <select name="course">
                <option value="BS-InfoTech">BS Information Technology</option>
                <option value="BS-CompTech">BS Computer Technology</option>
                <option value="BEED">Bachelor of Elementary Education</option>
                <option value="BSED">Bachelor of Science in Secondary Education</option>
                <option value="BSCE">Bachelor of Science in Civil Engineering</option>
                <option value="BSME">Bachelor of Science in Marine Engineering</option>
            </select>
            <br>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
