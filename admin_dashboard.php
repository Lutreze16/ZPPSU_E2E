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
$profileImage = "img/profile.png"; // Default image

// Fetch alumni information using $alumniEmail...
$sql = "SELECT alumni_first_name, alumni_last_name, alumni_email, alumni_graduation_year, alumni_program, alumni_profile_image FROM alumni WHERE alumni_email = ?";
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

    // Check if 'alumni_profile_image' key exists before accessing it
    $profileImage = isset($alumniInfo['alumni_profile_image']) ? $alumniInfo['alumni_profile_image'] : $profileImage;
} else {
    // Handle error or redirect to login if no alumni is found
    header("Location: login_alumni.php");
    exit();
}

$stmt->close();

// Handle profile update and image upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newFirstName = $_POST['new_first_name'];
    $newLastName = $_POST['new_last_name'];
    $newEmail = $_POST['new_email'];
    $newGraduationYear = $_POST['new_graduation_year'];
    $newProgram = $_POST['new_program'];

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['profile_image']['tmp_name'];
        $imageName = $_FILES['profile_image']['name'];
        $imagePath = "ZPPSU-E2E/uploads/" . $imageName;

        // Ensure the directory exists
        if (!file_exists("ZPPSU-E2E/uploads/")) {
            mkdir("ZPPSU-E2E/uploads/", 0755, true);
        }

        if (!move_uploaded_file($imageTmpName, $imagePath)) {
            error_log("File move failed: " . $imagePath);
        }

        // Update the profile image path in the database
        $sqlUpdateImage = "UPDATE alumni SET alumni_profile_image = ? WHERE alumni_email = ?";
        $stmtUpdateImage = $conn->prepare($sqlUpdateImage);
        $stmtUpdateImage->bind_param("ss", $imagePath, $alumniEmail);
        $stmtUpdateImage->execute();
        $stmtUpdateImage->close();

        // Update the profile image variable
        $profileImage = $imagePath;
    }

    // Add more fields as needed and update the SQL query
    $sqlUpdate = "UPDATE alumni SET alumni_first_name = ?, alumni_last_name = ?, alumni_email = ?, alumni_graduation_year = ?, alumni_program = ? WHERE alumni_email = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssssss", $newFirstName, $newLastName, $newEmail, $newGraduationYear, $newProgram, $alumniEmail);
    
    if ($stmtUpdate->execute()) {
        // Update successful
        $firstName = $newFirstName;
        $lastName = $newLastName;
        $email = $newEmail; // Update the email variable
        $graduationYear = $newGraduationYear;
        $program = $newProgram;

        // Fetch updated alumni information
        $stmtUpdatedInfo = $conn->prepare("SELECT alumni_first_name, alumni_last_name, alumni_email, alumni_graduation_year, alumni_program, alumni_profile_image FROM alumni WHERE alumni_email = ?");
        $stmtUpdatedInfo->bind_param("s", $alumniEmail);
        $stmtUpdatedInfo->execute();
        $resultUpdatedInfo = $stmtUpdatedInfo->get_result();

        if ($resultUpdatedInfo && $resultUpdatedInfo->num_rows > 0) {
            $alumniInfo = $resultUpdatedInfo->fetch_assoc();
            $firstName = $alumniInfo['alumni_first_name'];
            $lastName = $alumniInfo['alumni_last_name'];
            $email = $alumniInfo['alumni_email'];
            $graduationYear = $alumniInfo['alumni_graduation_year'];
            $program = $alumniInfo['alumni_program'];

            // Update the profile image variable
            $profileImage = isset($alumniInfo['alumni_profile_image']) ? $alumniInfo['alumni_profile_image'] : $profileImage;
        } else {
            // Handle error or redirect to login if no alumni is found
        }

        $stmtUpdatedInfo->close();
    } else {
        // Handle update error
        echo "Error updating record: " . $stmtUpdate->error;
    }

    $stmtUpdate->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

    <div class="tab">
        <div class="info">
            <img id="profileImage" src="<?php echo $profileImage; ?>">
            <h2><?php echo $firstName . ' ' . $lastName; ?></h2>
            <h3>Email: <?php echo $email; ?></h3>
            <h3>Graduation Year: <?php echo $graduationYear; ?></h3>
            <h3>Program: <?php echo $program; ?></h3>
        </div>
        <button class="tablinks" onclick="openTab(event, 'MyInfo')" id="defaultOpen">My Information</button>
        <button class="tablinks" onclick="openTab(event, 'JobApplications')">Job Applications</button>
        <button class="tablinks" onclick="openTab(event, 'CareerResources')">Career Resources</button>
        <button class="tablinks" onclick="openTab(event, 'AlignmentMonitoring')">Alignment Monitoring</button>
        <div class="logout">
            <button onclick="location.href='index.html'">Log Out</button>
        </div>
    </div>

    <!-- Main Content -->

    <div id="MyInfo" class="tabcontent">
        <h3>Alumni Dashboard</h3>
        <p>View and update your personal information here.</p>
        <p>First Name: <?php echo $firstName; ?></p>
        <p>Last Name: <?php echo $lastName; ?></p>
        <p>Email: <?php echo $email; ?></p>
        <p>Graduation Year: <?php echo $graduationYear; ?></p>
        <p>Program: <?php echo $program; ?></p>

        <button onclick="openUpdateForm()">Update Information</button>

        <div id="updateForm" style="display: none;">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <!-- Profile Image Upload -->
                <label for="profile_image">Profile Image:</label>
                <input type="file" name="profile_image" accept="image/*" onchange="updateProfileImage(this)" id="profile_image">

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

                <input type="submit" value="Update">
            </form>
        </div>

    </div>

    <div id="JobApplications" class="tabcontent">
        <h3>Job Applications</h3>
        <p>Track your personal job applications here.</p>
    </div>

    <div id="CareerResources" class="tabcontent">
        <h3>Career Resources</h3>
        <p>Explore resources to enhance your career knowledge.</p>
    </div>

    <div id="AlignmentMonitoring" class="tabcontent">
        <h3>Alignment Monitoring</h3>
        <p>Provide a section to monitor and update career alignment with your courses.</p>
    </div>

    <script>
        // Function to open or close the update form
        function openUpdateForm() {
            var updateForm = document.getElementById("updateForm");
            if (updateForm.style.display === "none" || updateForm.style.display === "") {
                updateForm.style.display = "block";
            } else {
                updateForm.style.display = "none";
            }
        }

        // Function to update the profile image when a new image is selected
        function updateProfileImage(input) {
            var profileImage = document.getElementById("profileImage");
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    profileImage.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>

</body>
</html>
