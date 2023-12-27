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
    <title>Alumni Login</title>
    <style>
        /* Styles remain unchanged */
        <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        /* Container styles */
        .container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Heading styles */
        h2 {
            text-align: center;
            color: #333;
        }

        /* Form styles */
        form {
            margin-top: 20px;
        }

        /* Label styles */
        label {
            display: block;
            margin-top: 10px;
            color: #333;
        }

        /* Input and select styles */
        input,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Button styles */
        button {
            background-color: #800000;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Button hover styles */
        button:hover {
            color: #dcab2d;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
    </style>
</head>
<body>

    <!-- Container -->
    <div class="container">
        <h2>Alumni Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- Alumni login form fields go here -->
            <label for="alumni_email">Email:</label>
            <input type="email" id="alumni_email" name="alumni_email" placeholder="Enter your email" required>

            <label for="alumni_password">Password:</label>
            <input type="password" id="alumni_password" name="alumni_password" placeholder="Enter your password" required>

            <button type="submit">Log In</button>
        </form>
        <div class="error-message"><?php echo $errorMessage; ?></div>
    </div>

</body>
</html>
