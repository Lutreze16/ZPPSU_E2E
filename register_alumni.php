<?php
include('config.php'); // Include the database connection file

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if form fields are set
    $first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : "";
    $last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $graduation_year = isset($_POST["graduation_year"]) ? $_POST["graduation_year"] : "";
    $program = isset($_POST["program"]) ? $_POST["program"] : "";

    // SQL query to insert data into the database
    $sql = "INSERT INTO alumni (first_name, last_name, email, graduation_year, program) 
            VALUES ('$first_name', '$last_name', '$email', $graduation_year, '$program')";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
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
    <title>Alumni Registration</title>
    <link rel="stylesheet" type="text/css" href="css/signup.css">
</head>
<body>
    <div class="container">
        <h2>Alumni Registration</h2>
        <form action="process_alumni.php" method="post">
            <!-- Alumni registration form fields go here -->
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="number" name="graduation_year" placeholder="Graduation Year" required>
            <label>Program:</label>
            <select name="program">
                <option value="BS-InfoTech">BS Information Technology</option>
                <option value="BS-CompTech">BS Computer Technology</option>
                <option value="BEED">Bachelor of Elementary Education</option>
                <option value="BSED">Bachelor of Science in Secondary Education</option>
                <option value="BSCE">Bachelor of Science in Civil Engineering</option>
                <option value="BSME">Bachelor of Science in Marine Engineering</option>
            </select>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
