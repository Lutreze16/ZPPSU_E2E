<?php
include('config.php');

session_start();
$studentID = isset($_SESSION['student_id']) ? $_SESSION['student_id'] : null;

$firstName = "";
$lastName = "";
$studentIDNumber = "";
$course = "";
$email = "";
$skills = "";
$profileImage = "img/profile.png"; // Default image

// Fetch student information
if ($studentID) {
    $sql = "SELECT first_name, last_name, student_id, course, email, skills, profile_image FROM students WHERE id = $studentID";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $studentInfo = $result->fetch_assoc();
        $firstName = $studentInfo['first_name'];
        $lastName = $studentInfo['last_name'];
        $studentIDNumber = $studentInfo['student_id'];
        $course = $studentInfo['course'];
        $email = $studentInfo['email'];
        $skills = $studentInfo['skills'];

        // Check if 'profile_image' key exists before accessing it
        $profileImage = isset($studentInfo['profile_image']) ? $studentInfo['profile_image'] : $profileImage;
    } else {
        // Handle error or redirect to login if no student is found
    }
} else {
    header("Location: login_student.php");
    exit();
}

// Handle profile update and image upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newFirstName = $_POST['new_first_name'];
    $newLastName = $_POST['new_last_name'];
    $newSkills = $_POST['new_skills'];
    $newEmail = $_POST['new_email']; // Add this line to capture the new email

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
        $sqlUpdateImage = "UPDATE students SET profile_image = '$imagePath' WHERE id = $studentID";
        $conn->query($sqlUpdateImage);

        // Update the profile image variable
        $profileImage = $imagePath;
    }

    // Add more fields as needed and update the SQL query
    $sqlUpdate = "UPDATE students SET first_name = '$newFirstName', last_name = '$newLastName', skills = '$newSkills', email = '$newEmail' WHERE id = $studentID";

    if ($conn->query($sqlUpdate) === TRUE) {
        // Update successful
        $firstName = $newFirstName;
        $lastName = $newLastName;
        $skills = $newSkills;
        $email = $newEmail; // Update the email variable

        // Fetch updated student information
        $sqlUpdatedInfo = "SELECT first_name, last_name, student_id, course, email, skills, profile_image FROM students WHERE id = $studentID";
        $resultUpdatedInfo = $conn->query($sqlUpdatedInfo);

        if ($resultUpdatedInfo && $resultUpdatedInfo->num_rows > 0) {
            $studentInfo = $resultUpdatedInfo->fetch_assoc();
            $firstName = $studentInfo['first_name'];
            $lastName = $studentInfo['last_name'];
            $studentIDNumber = $studentInfo['student_id'];
            $course = $studentInfo['course'];
            $email = $studentInfo['email'];
            $skills = $studentInfo['skills'];

            // Update the profile image variable
            $profileImage = isset($studentInfo['profile_image']) ? $studentInfo['profile_image'] : $profileImage;
        } else {
            // Handle error or redirect to login if no student is found
        }
    } else {
        // Handle update error
        echo "Error updating record: " . $conn->error;
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
</head>
<body>

    <div class="tab">
        <div class="info">
            <img id="profileImage" src="<?php echo $profileImage; ?>">
            <h2><?php echo $firstName . ' ' . $lastName; ?></h2>
            <h3>Student ID: <?php echo $studentIDNumber; ?></h3>
        </div>
        <button class="tablinks" onclick="openTab(event, 'MyInfo')" id="defaultOpen">My Information</button>
        <button class="tablinks" onclick="openTab(event, 'Academic')">Academic Records</button>
        <button class="tablinks" onclick="openTab(event, 'Aptitude')">Career Aptitude Test</button>
        <button class="tablinks" onclick="openTab(event, 'Guidance')">Career Guidance</button>
        <button class="tablinks" onclick="openTab(event, 'Resources')">Career Resources</button>
        <button class="tablinks" onclick="openTab(event, 'Portfolio')">Portfolio Management</button>
        <button class="tablinks" onclick="openTab(event, 'Plans')">Post-Graduation Plans</button>
        <div class="logout">
            <button onclick="location.href='index.html'">Log Out</button>
        </div>
    </div>

    <!-- Main Content -->

    <div id="MyInfo" class="tabcontent">
        <h3>Dashboard</h3>
        <p>View and update your personal information here.</p>
        <p>First Name: <?php echo $firstName; ?></p>
        <p>Last Name: <?php echo $lastName; ?></p>
        <p>Student ID: <?php echo $studentIDNumber; ?></p>
        <p>Year Level: <?php echo $year_level; ?></p>
        <p>Course: <?php echo $course; ?></p>
        <p>Email: <?php echo $email; ?></p>
        <p>Skills: <?php echo $skills; ?></p>

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

                <label for="new_student_id">New Student ID:</label>
                <input type="text" name="new_student_id" value="<?php echo $studentIDNumber; ?>" id="new_student_id">

                <label for="new_course">New Course:</label>
                <input type="text" name="new_course" value="<?php echo $course; ?>" required id="new_course">

                <label for="new_email">New Email:</label>
                <input type="text" name="new_email" value="<?php echo $email; ?>" required id="new_email">

                <label for="new_skills">New Skills:</label>
                <input type="text" name="new_skills" value="<?php echo $skills; ?>" required id="new_skills">

                <!-- Add more fields for additional information -->

                <input type="submit" value="Update">
            </form>
        </div>

    </div>

    <div id="Academic" class="tabcontent">
        <h3>Academic Records</h3>
        <p>Check your academic records and performance.</p>
    </div>

    <div id="Aptitude" class="tabcontent">
        <h3>Career Aptitude Test</h3>
        <p>Take a career aptitude test to discover your strengths.</p>
    </div>

    <div id="Guidance" class="tabcontent">
        <h3>Career Guidance</h3>
        <p>Get guidance on your career path and choices.</p>
    </div>

    <div id="Resources" class="tabcontent">
        <h3>Career Resources</h3>
        <p>Explore resources to enhance your career knowledge.</p>
    </div>

    <div id="Portfolio" class="tabcontent">
        <h3>Portfolio Management</h3>
        <p>Manage and showcase your portfolio.</p>
    </div>

    <div id="Plans" class="tabcontent">
        <h3>Post-Graduation Plans</h3>
        <p>Plan for your post-graduation journey.</p>
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
