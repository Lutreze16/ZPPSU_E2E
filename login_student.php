<?php
include('config.php');

session_start();

// Check if the user is logged in
if (!isset($_SESSION['student_id']) || !isset($_SESSION['course'])) {
    header("Location: index.php");
    exit();
}

// Get user information from the session
$session_student_id = $_SESSION['student_id']; // Use a different variable name
$course = $_SESSION['course'];

// Query to retrieve user information
$sql = "SELECT first_name, middle_name, last_name, student_id, course FROM students WHERE student_id = '$session_student_id' AND course = '$course'";
$result = $conn->query($sql);

if ($result === false) {
    die("Query failed: " . $conn->error);
}

// Check if the user exists
if ($result->num_rows == 1) {
    // Fetch user data
    $row = $result->fetch_assoc();
    $first_name = $row['first_name'];
    $middle_name = $row['middle_name'];
    $last_name = $row['last_name'];
    $student_id = $row['student_id'];
    $course = $row['course'];

    // Output user information or perform other actions as needed

} else {
    echo "User not found!";
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
    <style>
       body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .container {
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        color: #333333;
        text-align: center;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    input,
    select,
    button {
        margin: 10px;
        padding: 8px;
        width: 100%;
        box-sizing: border-box;
    }

    input,
    select {
        border: 1px solid #ccc;
        border-radius: 3px;
        height: 30px;
    }

    button {
        background-color: maroon;
        color: #ffffff;
        cursor: pointer;
        border: none;
        border-radius: 3px;
        height: 40px;
        font-size: 16px;
    }

    button:hover {
        background-color: #800000; /* Darker shade on hover */
    }

    p {
        color: #ff0000; /* Red color for error message */
        text-align: center;
        margin-top: 10px;
    }
    </style>
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