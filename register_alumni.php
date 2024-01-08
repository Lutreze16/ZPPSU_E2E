<?php
// Include the database configuration file
include 'config.php';

// Initialize error message
$errorMessage = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $alumni_first_name = mysqli_real_escape_string($conn, $_POST['alumni_first_name']);
    $alumni_last_name = mysqli_real_escape_string($conn, $_POST['alumni_last_name']);
    $alumni_email = mysqli_real_escape_string($conn, $_POST['alumni_email']);
    $alumni_graduation_year = mysqli_real_escape_string($conn, $_POST['alumni_graduation_year']);
    $alumni_password = password_hash(mysqli_real_escape_string($conn, $_POST['alumni_password']), PASSWORD_DEFAULT); // Hash the password
    $alumni_program = mysqli_real_escape_string($conn, $_POST['alumni_program']);

    // Insert data into the database
    $query = "INSERT INTO alumni (alumni_first_name, alumni_last_name, alumni_email, alumni_graduation_year, alumni_password, alumni_program) VALUES ('$alumni_first_name', '$alumni_last_name', '$alumni_email', '$alumni_graduation_year', '$alumni_password', '$alumni_program')";

    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        // Redirect to login_alumni.php
        header("Location: login_alumni.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close database connection
    mysqli_close($conn);
}
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
        <form action="register_alumni.php" method="post">
            <!-- Alumni registration form fields go here -->
            <input type="text" name="alumni_first_name" placeholder="First Name" required>
            <input type="text" name="alumni_last_name" placeholder="Last Name" required>
            <input type="email" name="alumni_email" placeholder="Email" required>
            <input type="number" name="alumni_graduation_year" placeholder="Graduation Year" required>
            <input type="password" name="alumni_password" placeholder="Password" required>
            <label>Program:</label>
            <select name="alumni_program">
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
