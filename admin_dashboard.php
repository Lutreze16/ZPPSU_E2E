<!--<?php
// Include config.php for database connection
include('config.php');

// Sample query to fetch students from the database (replace with your actual query)
$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

// Check if there are results from the database
if ($result) {
    // Fetch students from the database
    $students_from_database = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    // Handle the case where there are no results or an error occurred
    $students_from_database = array();
}

// Close the database connection
mysqli_close($conn);
?>-->

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
            <h4>Admin123</h4>
        </div>
        <button class="tablinks" onclick="openTab(event, 'StudentManagement')" id="defaultOpen">Student Management</button>
        <button class="tablinks" onclick="openTab(event, 'AcademicRecords')">Academic Records</button>
        <button class="tablinks" onclick="openTab(event, 'CareerGuidance')">Career Guidance</button>
        <button class="tablinks" onclick="openTab(event, 'JobPostings')">Job Postings</button>
        <button class="tablinks" onclick="openTab(event, 'ProgressMonitoring')">Progress Monitoring</button>
        <button class="tablinks" onclick="openTab(event, 'AlumniTracking')">Alumni Tracking</button>
        <div class="logout">
            <button onclick="location.href='index.html'">Log Out</button>
        </div>
    </div>

    <!-- Main Content -->

    <div id="StudentManagement" class="tabcontent">
        <h1>Student Management</h1>
        <!-- Add New Student Form -->
        <div id="addStudentForm" style="display: none;">
            <h2>Add New Student</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <label for="new_first_name">First Name:</label>
                <input type="text" name="new_first_name" required>

                <label for="new_last_name">Last Name:</label>
                <input type="text" name="new_last_name" required>

                <label for="new_course">Course:</label>
                <input type="text" name="new_course" required>

                <label for="new_year_level">Year Level:</label>
                <input type="text" name="new_year_level" required>

                <label for="new_skills">Skills:</label>
                <input type="text" name="new_skills" required>

                <label for="new_email">Email:</label>
                <input type="text" name="new_email" required>

                <label for="new_password">Password:</label>
                <input type="password" name="new_password" required>

                <label for="new_profile_image">Profile Image:</label>
                <input type="file" name="new_profile_image" accept="image/*" required>

                <input type="submit" value="Add Student">
            </form>
        </div>

        <!-- View and Manage Students -->
        <div id="studentList">
            <!-- Display Students Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Skills</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch and Display Students from the Database -->
                    <?php
                    // Fetch students from the database and display them in rows
                    // Customize this part based on your database connection and query
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['first_name']}</td>";
                        echo "<td>{$row['last_name']}</td>";
                        echo "<td>{$row['course']}</td>";
                        echo "<td>{$row['year_level']}</td>";
                        echo "<td>{$row['skills']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td><button class='actions' onclick=\"editStudent({$row['id']})\">Edit</button> <button class='action' onclick=\"deleteStudent({$row['id']})\">Delete</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="AcademicRecords" class="tabcontent">
        <h1>Academic Records</h1>
        <!-- Add New Academic Record Form -->
        <div id="addAcademicRecordForm" style="display: none;">
            <h2>Add New Academic Record</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <!-- Add fields for academic record details -->
                <!-- Example: -->
                <label for="course_name">Course Name:</label>
                <input type="text" name="course_name" required>

                <label for="grade">Grade:</label>
                <input type="text" name="grade" required>

                <input type="submit" value="Add Academic Record">
            </form>
        </div>

        <!-- View and Manage Academic Records -->
        <div id="academicRecordList">
            <!-- Display Academic Records Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course Name</th>
                        <th>Grade</th>
                        <!-- Add more fields as needed -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch and Display Academic Records from the Database -->
                    <?php
                    // Fetch academic records from the database and display them in rows
                    // Customize this part based on your database connection and query
                    while ($academicRecord = mysqli_fetch_assoc($academicRecordsResult)) {
                        echo "<tr>";
                        echo "<td>{$academicRecord['id']}</td>";
                        echo "<td>{$academicRecord['course_name']}</td>";
                        echo "<td>{$academicRecord['grade']}</td>";
                        <!-- Add more fields as needed -->
                        echo "<td><button class='actions' onclick='editAcademicRecord({$academicRecord['id']})'>Edit</button> <button class='action' onclick='deleteAcademicRecord({$academicRecord['id']})'>Delete</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="CareerGuidance" class="tabcontent">
        <h1>Career Guidance</h1>
        <!-- Add New Career Guidance Form -->
        <div id="addCareerGuidanceForm" style="display: none;">
            <h2>Add New Career Guidance</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <!-- Add fields for career guidance details -->
                <!-- Example: -->
                <label for="counselor_name">Counselor Name:</label>
                <input type="text" name="counselor_name" required>

                <label for="advice">Advice:</label>
                <textarea name="advice" rows="4" cols="50" required></textarea>

                <input type="submit" value="Add Career Guidance">
            </form>
        </div>

        <!-- View and Manage Career Guidance -->
        <div id="careerGuidanceList">
            <!-- Display Career Guidance Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Counselor Name</th>
                        <th>Advice</th>
                        <!-- Add more fields as needed -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch and Display Career Guidance from the Database -->
                    <?php
                    // Fetch career guidance records from the database and display them in rows
                    // Customize this part based on your database connection and query
                    while ($careerGuidance = mysqli_fetch_assoc($careerGuidanceResult)) {
                        echo "<tr>";
                        echo "<td>{$careerGuidance['id']}</td>";
                        echo "<td>{$careerGuidance['counselor_name']}</td>";
                        echo "<td>{$careerGuidance['advice']}</td>";
                        <!-- Add more fields as needed -->
                        echo "<td><button class='actions' onclick='editCareerGuidance({$careerGuidance['id']})'>Edit</button> <button class='action' onclick='deleteCareerGuidance({$careerGuidance['id']})'>Delete</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="JobPostings" class="tabcontent">
        <h1>Job Postings</h1>
        <!-- Add New Job Posting Form -->
        <div id="addJobPostingForm" style="display: none;">
            <h2>Add New Job Posting</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <!-- Add fields for job posting details -->
                <label for="job_title">Job Title:</label>
                <input type="text" name="job_title" required>

                <label for="employer">Employer:</label>
                <input type="text" name="employer" required>

                <label for="job_description">Job Description:</label>
                <textarea name="job_description" rows="4" cols="50" required></textarea>

                <input type="submit" value="Add Job Posting">
            </form>
        </div>

        <!-- View and Manage Job Postings -->
        <div id="jobPostingList">
            <!-- Display Job Postings Table -->
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Job Title</th>
                        <th>Employer</th>
                        <th>Job Description</th>
                        <!-- Add more fields as needed -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch and Display Job Postings from the Database -->
                    <?php
                    // Fetch job postings from the database and display them in rows
                    // Customize this part based on your database connection and query
                    while ($jobPosting = mysqli_fetch_assoc($jobPostingsResult)) {
                        echo "<tr>";
                        echo "<td>{$jobPosting['id']}</td>";
                        echo "<td>{$jobPosting['job_title']}</td>";
                        echo "<td>{$jobPosting['employer']}</td>";
                        echo "<td>{$jobPosting['job_description']}</td>";
                        <!-- Add more fields as needed -->
                        echo "<td><button class='actions' onclick='editJobPosting({$jobPosting['id']})'>Edit</button> <button class='action' onclick='deleteJobPosting({$jobPosting['id']})'>Delete</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="ProgressMonitoring" class="tabcontent">
        <h1>Progress Monitoring</h1>
    
        <!-- Update Progress Bars Form -->
        <div id="updateProgressBarsForm" style="display: none;">
            <h2>Update Progress Bars</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <!-- Add fields for progress bar updates -->
                <!-- Example: -->
                <label for="student_id">Student ID:</label>
                <input type="text" name="student_id" required>
    
                <label for="progress">Progress (in percentage):</label>
                <input type="number" name="progress" min="0" max="100" required>
    
                <input type="submit" value="Update Progress Bars">
            </form>
        </div>
    
        <!-- View Progress Bars -->
        <div id="progressBars">
            <!-- Display Progress Bars -->
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Progress</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch and Display Progress Bars from the Database -->
                    <?php
                    // Fetch progress bars from the database and display them in rows
                    // Customize this part based on your database connection and query
                    while ($progressBar = mysqli_fetch_assoc($progressBarsResult)) {
                        echo "<tr>";
                        echo "<td>{$progressBar['student_id']}</td>";
                        echo "<td>{$progressBar['progress']}%</td>";
                        <!-- Add more fields as needed -->
                        echo "<td><button class='actions' onclick='editProgressBars({$progressBar['id']})'>Edit</button> <button class='action' onclick='deleteProgressBars({$progressBar['id']})'>Delete</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    

    <div id="AlumniTracking" class="tabcontent">
        <h1>Alumni Tracking</h1>
    
        <!-- Track Alumni Activities -->
        <div id="alumniActivities">
            <!-- Display Alumni Activities Table -->
            <table>
                <thead>
                    <tr>
                        <th>Alumni ID</th>
                        <th>Name</th>
                        <th>Job Applications</th>
                        <th>Positions Held</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch and Display Alumni Activities from the Database -->
                    <?php
                    // Fetch alumni activities from the database and display them in rows
                    // Customize this part based on your database connection and query
                    while ($alumniActivity = mysqli_fetch_assoc($alumniActivitiesResult)) {
                        echo "<tr>";
                        echo "<td>{$alumniActivity['alumni_id']}</td>";
                        echo "<td>{$alumniActivity['name']}</td>";
                        echo "<td>{$alumniActivity['job_applications']}</td>";
                        echo "<td>{$alumniActivity['positions_held']}</td>";
                        <!-- Add more fields as needed -->
                        echo "<td><button class='actions' onclick='editAlumniActivities({$alumniActivity['id']})'>Edit</button> <button class='action' onclick='deleteAlumniActivities({$alumniActivity['id']})'>Delete</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="js/index.js"></script>
</body>
</html>
