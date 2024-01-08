<?php
include 'config.php';

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = htmlspecialchars($_POST['student_id']);
    $password = htmlspecialchars($_POST['password']);

    $stmt = $conn->prepare("SELECT id, first_name, last_name, student_id, course, year_level, skills, password FROM students WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['student_id'] = $row['student_id'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['course'] = $row['course'];
            $_SESSION['year_level'] = $row['year_level'];
            $_SESSION['skills'] = $row['skills'];

            // Redirect to the dashboard
            header("Location: student_dashboard.php");
            exit();
        } else {
            $errorMessage = "Invalid password";
        }
    } else {
        $errorMessage = "Student not found";
    }

    $stmt->close();
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>Login | For Students</title>
    <link rel="icon" href="img/zppsu-seal.png" type="image/png">
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="number" name="student_id" placeholder="Student ID No." required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($errorMessage)) echo "<p style='color: red;'>$errorMessage</p>"; ?>
    </div>
</body>
</html>
