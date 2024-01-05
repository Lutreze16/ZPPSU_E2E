<?php
// Start the session before any output
session_start();

// Include database configuration
include('config.php');

// Error message variable
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $alumniEmail = isset($_POST["alumni_email"]) ? trim($_POST["alumni_email"]) : "";
    $password = isset($_POST["alumni_password"]) ? trim($_POST["alumni_password"]) : "";

    if (empty($alumniEmail) || empty($password)) {
        $errorMessage = "Email and password are required";
    } else {
        $sql = "SELECT alumni_email, alumni_password FROM alumni WHERE alumni_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $alumniEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['alumni_password'])) {
                session_start();
                $_SESSION['alumni_email'] = $row['alumni_email'];

                // Prevent browser caching
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Pragma: no-cache");
                header("Expires: 0");

                // Redirect to the dashboard
                header("Location: alumni_dashboard.php");
                exit();
            } else {
                $errorMessage = "Invalid password";
            }
        } else {
            $errorMessage = "Invalid email";
        }

        $stmt->close();
    }
}

$conn->close();
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
        <div class="error-message"><?php echo $errorMessage; ?></div>
    </div>

</body>
</html>
