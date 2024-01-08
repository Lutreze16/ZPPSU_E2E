<?php
include('config.php');

session_start();
$alumniEmail = isset($_SESSION['alumni_email']) ? $_SESSION['alumni_email'] : null;

if (!$alumniEmail) {
    header("Location: login_alumni.php");
    exit();
}

$firstName = "";
$lastName = "";
$email = "";
$graduationYear = "";
$program = "";

$sql = "SELECT alumni_first_name, alumni_last_name, alumni_email, alumni_graduation_year, alumni_program FROM alumni WHERE alumni_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $alumniEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $alumniInfo = $result->fetch_assoc();
    $firstName = $alumniInfo['alumni_first_name'];
    $lastName = $alumniInfo['alumni_last_name'];
    $email = $alumniInfo['alumni_email'];
    $graduationYear = $alumniInfo['alumni_graduation_year'];
    $program = $alumniInfo['alumni_program'];
} else {
    header("Location: login_alumni.php");
    exit();
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['new_first_name'], $_POST['new_last_name'], $_POST['new_email'], $_POST['new_graduation_year'], $_POST['new_program'])) {
        $newFirstName = mysqli_real_escape_string($conn, $_POST['new_first_name']);
        $newLastName = mysqli_real_escape_string($conn, $_POST['new_last_name']);
        $newEmail = mysqli_real_escape_string($conn, $_POST['new_email']);
        $newGraduationYear = mysqli_real_escape_string($conn, $_POST['new_graduation_year']);
        $newProgram = mysqli_real_escape_string($conn, $_POST['new_program']);

        // Update alumni's personal information
        $sqlUpdate = "UPDATE alumni SET alumni_first_name = ?, alumni_last_name = ?, alumni_email = ?, alumni_graduation_year = ?, alumni_program = ? WHERE alumni_email = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("ssssss", $newFirstName, $newLastName, $newEmail, $newGraduationYear, $newProgram, $alumniEmail);

        if ($stmtUpdate->execute()) {
            // Update successful
            $firstName = $newFirstName;
            $lastName = $newLastName;
            $email = $newEmail;
            $graduationYear = $newGraduationYear;
            $program = $newProgram;
        } else {
            // Handle update error
            echo "Error updating record: " . $stmtUpdate->error;
        }

        $stmtUpdate->close();
    }

    // Additional processing for job applications
    if (isset($_POST['company_name'], $_POST['application_date'], $_POST['status'], $_POST['notes'])) {
        $companyName = mysqli_real_escape_string($conn, $_POST['company_name']);
        $applicationDate = mysqli_real_escape_string($conn, $_POST['application_date']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $notes = mysqli_real_escape_string($conn, $_POST['notes']);

        // Insert job application data into the alumni table
        $sqlInsertApplication = "INSERT INTO alumni (company_name, application_date, status, notes, alumni_email) VALUES (?, ?, ?, ?, ?)";
        $stmtInsertApplication = $conn->prepare($sqlInsertApplication);
        $stmtInsertApplication->bind_param("sssss", $companyName, $applicationDate, $status, $notes, $alumniEmail);

        if ($stmtInsertApplication->execute()) {
            // Insert successful
            // Redirect to avoid resubmitting form on page refresh
            header("Location: ".$_SERVER['PHP_SELF']);
            exit();
        } else {
            // Handle insert error
            echo "Error inserting record: " . $stmtInsertApplication->error;
        }

        $stmtInsertApplication->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="icon" href="img/zppsu-seal.png" type="image/png">
</head>
<body>

    <div class="tab">
        <div class="information">
            <img id="profileImage" src="img/user.png">
            <h3><?php echo $firstName . ' ' . $lastName; ?></h3>
            <h4>Email: <?php echo $email; ?></h4>
            <h4>Graduation Year: <?php echo $graduationYear; ?></h4>
            <h4>Program: <?php echo $program; ?></h4>
        </div>
        <button class="tablinks" onclick="openTab(event, 'MyInfo')" id="defaultOpen">My Information</button>
        <button class="tablinks" onclick="openTab(event, 'JobApplications')">Job Applications</button>
        <button class="tablinks" onclick="openTab(event, 'CareerResources')">Career Resources</button>
        <div class="logout">
            <button class="btn" onclick="location.href='index.html'">Log Out</button>
        </div>
    </div>

    <!-- Main Content -->

    <!-- My Information Feature -->
<div id="MyInfo" class="tabcontent">
    <div class="info">
        <h1>Alumni Dashboard</h1>
        <p>View and update your personal information here.</p>
        <p>First Name: <?php echo $firstName; ?></p>
        <p>Last Name: <?php echo $lastName; ?></p>
        <p>Email: <?php echo $email; ?></p>
        <p>Graduation Year: <?php echo $graduationYear; ?></p>
        <p>Program: <?php echo $program; ?></p>
    </div>

    <button class="btn" onclick="openUpdateForm()">Update Information</button>

    <div id="updateForm" style="display: none;">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="new_first_name">New First Name:</label>
            <input type="text" name="new_first_name" value="<?php echo $firstName; ?>" required id="new_first_name">

            <label for="new_last_name">New Last Name:</label>
            <input type="text" name="new_last_name" value="<?php echo $lastName; ?>" required id="new_last_name">

            <label for="new_email">New Email:</label>
            <input type="text" name="new_email" value="<?php echo $email; ?>" required id="new_email">

            <label for="new_graduation_year">New Graduation Year:</label>
            <input type="text" name="new_graduation_year" value="<?php echo $graduationYear; ?>" required id="new_graduation_year">

            <label for="new_program">New Program:</label>
            <input type="text" name="new_program" value="<?php echo $program; ?>" required id="new_program">

            <label for="alignment_status">Alignment Status:</label>
            <select name="alignment_status" required>
                <option value="Aligned">Aligned</option>
                <option value="Partially Aligned">Partially Aligned</option>
                <option value="Not Aligned">Not Aligned</option>
            </select>

            <label for="alignment_notes">Alignment Notes:</label>
            <textarea name="alignment_notes" rows="4" cols="50"></textarea>
            <!-- End of new fields -->

            <input type="submit" value="Update">
        </form>
    </div>
</div>

    <!-- Job Applications Feature -->
    <div id="JobApplications" class="tabcontent">
        <h1>Job Applications</h1>
        <button class="btn" onclick="openJobApplicationForm()">Add New Application</button>

        <div id="jobApplicationForm" style="display: none;">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="company_name">Company Name:</label>
                <input type="text" name="company_name" required>

                <label for="application_date">Application Date:</label>
                <input type="date" name="application_date" required>

                <label for="status">Status:</label>
                <select name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>

                <label for="notes">Notes:</label>
                <textarea name="notes" rows="4" cols="50"></textarea>

                <input type="submit" value="Add Application">
            </form>
        </div>
    </div>

    <!-- Career resources feature -->

    <div id="CareerResources" class="tabcontent">
        <h1>Career Resources</h1>
        <p>Explore resources to enhance your career knowledge.</p>
        <ul>
            <li><a href="https://example1.com" target="_blank">Resource 1</a></li>
            <li><a href="https://example2.com" target="_blank">Resource 2</a></li>
            <li><a href="https://example3.com" target="_blank">Resource 3</a></li>
        </ul>
    </div>

    <div id="AlignmentMonitoring" class="tabcontent">
        <h1>Alignment Monitoring</h1>

        <div id="employmentDetails">
            <label for="employment_status">Are you currently employed?</label>
            <select id="employment_status" onchange="toggleAlignmentForm()">
                <option value="yes">Yes</option>
                <option value="no">No</option>
            </select>
        </div>
    </div>

    <script src="js/index.js"></script>
</body>
</html>
