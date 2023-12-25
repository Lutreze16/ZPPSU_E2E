<?php
include('config.php'); // Include the database connection file

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulate a session (replace this with your actual session handling)
session_start();
$studentID = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;

// Initialize variables
$firstName = "";
$lastName = "";
$studentIDNumber = "";

// Check if a user is logged in
if ($studentID) {
    // SQL query to retrieve student information
    $sql = "SELECT first_name, last_name, student_id FROM students WHERE id = $studentID";
    $result = $conn->query($sql);

    // Check if the query was successful
    if ($result && $result->num_rows > 0) {
        // Fetch student information
        $studentInfo = $result->fetch_assoc();
        $firstName = $studentInfo['first_name'];
        $lastName = $studentInfo['last_name'];
        $studentIDNumber = $studentInfo['student_id'];
    } else {
        // Handle error or redirect to login if no student is found
    }
} else {
    // Redirect to login page if the user is not logged in
    header("Location: login_student.php");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>

    <!-- Navigation Bar -->
    <div class="sidenav">
        <div class="user_info">
            <img src="img/profile.png">
            <h2><?php echo $firstName . ' ' . $lastName; ?></h2>
            <h2>Student ID: <?php echo $studentIDNumber; ?></h2>
        </div>
        <div class="links">
            <a href="#">Dashboard</a>
            <a href="#">My Information</a>
            <a href="#">Academic Records</a>
            <a href="#">Career Aptitude Test</a>
            <a href="#">Career Guidance</a>
            <a href="#">Career Resources</a>
            <a href="#">Portfolio Management</a>
            <a href="#">Post-Graduation Plans</a>
        </div>
        <div class="logout">
            <button onclick="location.href='index.html'">Log Out</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
    </div>

    <style>
        body {
        font-family: Arial, sans-serif;
        margin: 0;
    }

    img {
        width: 100px;
        height: 100px;
        border-radius: 50px;
    }

    h2 {
        color: #fff;
    }

    .sidenav {
        height: 100%;
        width: 350px;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color: #800000;
        display: flex; /* Use flexbox */
        flex-direction: column; /* Stack flex items vertically */
        align-items: center; /* Center align items horizontally */
        padding-top: 20px;
    }

    .user_info {
        margin-bottom: 30px; /* Add margin at the bottom */
        /* Add the following styles for justification */
        width: 100%;
        text-align: center;
    }

    .links {
        margin-bottom: 20px; /* Add margin at the bottom */
        /* Add the following styles for justification */
        display: flex;
        flex-direction: column;
        align-items: left;
        justify-content: space-between; /* Justify content between flex items */
        height: 100%; /* Expand to full height */
        margin-top: 50px;
    }

    .sidenav a {
        padding: 15px 15px 15px 32px;
        text-decoration: none;
        font-size: 25px;
        color: #f1f1f1;
        display: block;
    }

    .sidenav a:hover {
        color: #dcab2d;
    }

    .logout {
        margin-top: 200px; /* Add margin at the top */
        align-items: center;
    }

    button {
        border: none;
        border-radius: 10px;
        cursor: pointer;
        outline: none;
        background-color: #a52424;
        padding: 15px;
        color: #fff;
        font-size: 20px;
    }

    .links, .logout {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
    }
    </style>

</body>
</html>
