<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database configuration file
include 'config.php';

// Initialize error message
$errorMessage = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $alumni_email = mysqli_real_escape_string($conn, $_POST['alumni_email']);
    $alumni_password = mysqli_real_escape_string($conn, $_POST['alumni_password']);

    // Retrieve password from the database based on the entered email
    $query = "SELECT alumni_password FROM alumni WHERE alumni_email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $alumni_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the entered password with the hashed password from the database
            if (password_verify($alumni_password, $row['alumni_password'])) {
                // Successful login, redirect to alumni_dashboard.php
                session_start();
                $_SESSION['alumni_email'] = $alumni_email;
                header("Location: alumni_dashboard.php");
                exit();
            } else {
                $errorMessage = "Invalid password";
            }
        } else {
            $errorMessage = "Email not found";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    // Close database connection
    $stmt->close();
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Alumni Login</title>
</head>
<body>
    <!-- Container -->
    <div class="container">
        <h2>Alumni Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Alumni login form fields go here -->
            <input type="email" id="alumni_email" name="alumni_email" placeholder="Enter your email" required>
            <input type="password" id="alumni_password" name="alumni_password" placeholder="Enter your password" required>

            <button type="submit">Log In</button>
        </form>
        <?php if (!empty($errorMessage)) : ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
