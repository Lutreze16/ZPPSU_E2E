<?php
include('config.php');

session_start();

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_admin_user_id = isset($_POST["admin_user_id"]) ? $_POST["admin_user_id"] : "";
    $input_admin_password = isset($_POST["admin_password"]) ? $_POST["admin_password"] : "";

    $sql = "SELECT * FROM admin WHERE admin_user_id = '$input_admin_user_id'";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['admin_password'];

        if ($input_admin_password === $stored_password) {
            // Password is correct, set session and redirect
            $_SESSION['admin_user_id'] = $input_admin_user_id;

            header("Location: admin_dashboard.php");
            exit();

            exit();
        } else {
            $errorMessage = "Invalid password";
        }
    } else {
        $errorMessage = "User not found";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Admin</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <h2>Admin Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="admin_user_id" placeholder="Admin User ID" required>
            <input type="password" name="admin_password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($errorMessage)) echo "<p>$errorMessage</p>"; ?>
    </div>
</body>
</html>
