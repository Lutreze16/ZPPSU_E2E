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
$profileImage = "img/profile.png";

// Fetch alumni information using $alumniEmail
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
        $email = $newEmail;
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

    // Insert job application
    $companyName = $_POST['company_name'];
    $applicationDate = $_POST['application_date'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    // Prepare and execute the SQL statement
    $sqlInsertApplication = "INSERT INTO job_applications (alumni_id, company_name, application_date, status, notes) VALUES (?, ?, ?, ?, ?)";
    $stmtInsertApplication = $conn->prepare($sqlInsertApplication);
    $stmtInsertApplication->bind_param("issss", $alumniID, $companyName, $applicationDate, $status, $notes);

    if ($stmtInsertApplication->execute()) {
        echo "Job application inserted successfully.";
    } else {
        echo "Error inserting job application: " . $stmtInsertApplication->error;
    }

    $stmtInsertApplication->close();

    // Fetch existing job applications
    $sqlFetchApplications = "SELECT * FROM job_applications WHERE alumni_id = ?";
    $stmtFetchApplications = $conn->prepare($sqlFetchApplications);
    $stmtFetchApplications->bind_param("i", $alumniID);
    $stmtFetchApplications->execute();
    $resultApplications = $stmtFetchApplications->get_result();

    if ($resultApplications && $resultApplications->num_rows > 0) {
        while ($application = $resultApplications->fetch_assoc()) {
            // Display each job application
            echo "<p><strong>Company:</strong> " . $application['company_name'] . "</p>";
            echo "<p><strong>Date:</strong> " . $application['application_date'] . "</p>";
            echo "<p><strong>Status:</strong> " . $application['status'] . "</p>";
            echo "<p><strong>Notes:</strong> " . $application['notes'] . "</p>";
            echo "<hr>";
        }
    } else {
        echo "<p>No job applications found.</p>";
    }

    $stmtFetchApplications->close();
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
        <div class="information">
            <!--<img id="profileImage" src="<?php echo $profileImage; ?>">-->
            <img id="profileImage" src="img/user.png">
            <h2><?php echo $firstName . ' ' . $lastName; ?></h2>
            <h4>Email: <?php echo $email; ?></h4>
            <h4>Graduation Year: <?php echo $graduationYear; ?></h4>
            <h4>Program: <?php echo $program; ?></h4>
        </div>
        <button class="tablinks" onclick="openTab(event, 'MyInfo')" id="defaultOpen">My Information</button>
        <button class="tablinks" onclick="openTab(event, 'JobApplications')">Job Applications</button>
        <button class="tablinks" onclick="openTab(event, 'CareerResources')">Career Resources</button>
        <button class="tablinks" onclick="openTab(event, 'AlignmentMonitoring')">Alignment Monitoring</button>
        <div class="logout">
            <button class="btn" onclick="location.href='index.html'">Log Out</button>
        </div>
    </div>

    <!-- Main Content -->

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
    
        <div id="alignmentForm" style="display: none;">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="course_name">Course Name:</label>
                <input type="text" name="course_name" required>
    
                <label for="alignment_status">Alignment Status:</label>
                <select name="alignment_status" required>
                    <option value="Aligned">Aligned</option>
                    <option value="Partially Aligned">Partially Aligned</option>
                    <option value="Not Aligned">Not Aligned</option>
                </select>
    
                <label for="alignment_notes">Alignment Notes:</label>
                <textarea name="alignment_notes" rows="4" cols="50"></textarea>
    
                <input type="submit" value="Update Alignment">
            </form>
        </div>
    
        <div id="alignmentButtonContainer">
            <button class="btn" onclick="toggleAlignmentForm()">Update Alignment</button>
        </div>
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
        
        // Function to open or close the alignment form
        function openAlignmentForm() {
            var alignmentForm = document.getElementById("alignmentForm");
            if (alignmentForm.style.display === "none" || alignmentForm.style.display === "") {
                alignmentForm.style.display = "block";
            } else {
                alignmentForm.style.display = "none";
            }
        }

        function toggleAlignmentForm() {
            var employmentStatus = document.getElementById("employment_status").value;
            var alignmentForm = document.getElementById("alignmentForm");

            if (employmentStatus === "yes") {
                alignmentForm.style.display = "block";
            } else {
                alignmentForm.style.display = "none";
            }
        }

        function openJobApplicationForm() {
            var jobApplicationForm = document.getElementById("jobApplicationForm");
            if (jobApplicationForm.style.display === "none" || jobApplicationForm.style.display === "") {
                jobApplicationForm.style.display = "block";
            } else {
                jobApplicationForm.style.display = "none";
            }
        }
    </script>
</body>
</html>
