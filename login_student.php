<?php
session_start();

if (isset($_SESSION['student_id'])) {
    header("Location: student_dashboard.php");
    exit();
}

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('config.php');

    $input_student_id = isset($_POST["student_id"]) ? $_POST["student_id"] : "";
    $input_password = isset($_POST["password"]) ? $_POST["password"] : "";

    $sql = "SELECT student_id, password FROM students WHERE student_id = '$input_student_id'";
    $result = $conn->query($sql);

    if ($result === false) {
        die("Query failed: " . $conn->error);
    }

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        if (password_verify($input_password, $stored_password)) {
            $_SESSION['student_id'] = $row['student_id'];
            header("Location: student_dashboard.php");
            exit();
        } else {
            $errorMessage = "Invalid password";
        }
    } else {
        $errorMessage = "User not found";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | For Students</title>
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
            background-color: #800000;
        }

        p {
            color: #ff0000;
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
            <input type="password" name="password" placeholder="Password" required>
            <br>
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($errorMessage)) echo "<p>$errorMessage</p>"; ?>
    </div>
</body>
</html>