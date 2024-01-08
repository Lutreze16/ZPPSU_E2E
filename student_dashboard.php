<?php
session_start();

// Include database connection and configuration
include 'config.php';

$errorMessage = '';

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login_student.php");
    exit();
}

// Fetch user details from the session
$studentIDNumber = $_SESSION['student_id'];
$firstName = $_SESSION['first_name'];
$lastName = $_SESSION['last_name'];
$course = $_SESSION['course'];
$year_level = $_SESSION['year_level'];
$skills = $_SESSION['skills'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $newFirstName = htmlspecialchars($_POST['new_first_name']);
    $newLastName = htmlspecialchars($_POST['new_last_name']);
    $newStudentID = htmlspecialchars($_POST['new_student_id']);
    $newYearLevel = htmlspecialchars($_POST['new_year_level']);
    $newCourse = htmlspecialchars($_POST['new_course']);
    $newSkills = htmlspecialchars($_POST['new_skills']);

    // Update the user information in the database
    $stmt = $conn->prepare("UPDATE students SET first_name=?, last_name=?, student_id=?, year_level=?, course=?, skills=? WHERE student_id=?");
    $stmt->bind_param("ssissss", $newFirstName, $newLastName, $newStudentID, $newYearLevel, $newCourse, $newSkills, $studentIDNumber);

    if ($stmt->execute()) {
        // Update session variables as well
        $_SESSION['first_name'] = $newFirstName;
        $_SESSION['last_name'] = $newLastName;
        $_SESSION['student_id'] = $newStudentID;
        $_SESSION['year_level'] = $newYearLevel;
        $_SESSION['course'] = $newCourse;
        $_SESSION['skills'] = $newSkills;

        // Display success message or handle redirection
        $successMessage = "Information updated successfully!";
    } else {
        $errorMessage = "Error updating information. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="icon" href="img/zppsu-seal.png" type="image/png">
    <title>Student | Dashboard</title>
</head>
<body>

    <div class="tab">
        <div class="information">
            <img id="profileImage" src="img/user.png">
            <h3><?php echo $firstName . ' ' . $lastName; ?></h3>
            <h4 style="text-align: center;">Student ID: <?php echo $studentIDNumber; ?></h4>
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


    <!-- My information feature -->
    <div id="MyInfo" class="tabcontent">

        <div class="info">
            <h1>Dashboard</h1>
            <p>First Name: <?php echo $firstName; ?></p>
            <p>Last Name: <?php echo $lastName; ?></p>
            <p>Student ID: <?php echo $studentIDNumber; ?></p>
            <p>Year Level: <?php echo $year_level; ?></p>
            <p>Course: <?php echo $course; ?></p>
            <p>Skills: <?php echo $skills; ?></p>
        </div>

        <button class="btn" onclick="openUpdateForm()">Update Information</button>

        <div id="updateForm" style="display: none;">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">

                <label for="new_first_name">New First Name:</label>
                <input type="text" name="new_first_name" value="<?php echo $firstName; ?>" required id="new_first_name">

                <label for="new_last_name">New Last Name:</label>
                <input type="text" name="new_last_name" value="<?php echo $lastName; ?>" required id="new_last_name">

                <label for="new_student_id">New Student ID:</label>
                <input type="text" name="new_student_id" value="<?php echo $studentIDNumber; ?>" id="new_student_id">

                <label for="new_year_level">New Year Level:</label>
                <select name="new_year_level" id="new_year_level">
                    <option value="1" <?php echo ($year_level == 1) ? 'selected' : ''; ?>>1st Year</option>
                    <option value="2" <?php echo ($year_level == 2) ? 'selected' : ''; ?>>2nd Year</option>
                    <option value="3" <?php echo ($year_level == 3) ? 'selected' : ''; ?>>3rd Year</option>
                    <option value="4" <?php echo ($year_level == 4) ? 'selected' : ''; ?>>4th Year</option>
                    <option value="5" <?php echo ($year_level == 5) ? 'selected' : ''; ?>>5th Year</option>
                </select>

                <label for="new_course">New Course:</label>
                <input type="text" name="new_course" value="<?php echo $course; ?>" required id="new_course">

                <label for="new_skills">New Skills:</label>
                <input type="text" name="new_skills" value="<?php echo $skills; ?>" required id="new_skills">

                <input type="submit" value="Update">
            </form>
        </div>
    </div>

    <div id="Academic" class="tabcontent">
        <h1>Academic Records</h1>
        <table class="academic-table">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Grade</th>
                    <th>Year</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Physical Education 1</td>
                    <td>1.0</td>
                    <td>2023</td>
                </tr>
                <tr>
                    <td>NSTP 1</td>
                    <td>1.25</td>
                    <td>2023</td>
                </tr>
            </tbody>
        </table>
        <button class="btn">Print Records</button>
    </div>

    <div id="Aptitude" class="tabcontent">
        <h1>Career Aptitude Test</h1>
        <form id="aptitudeForm">
            <!-- Add more questions as needed -->
            <label>Question 1: What are your favorite subjects?</label>
            <input type="radio" name="question1" value="technology"> Technology
            <input type="radio" name="question1" value="art"> Art
            <input type="radio" name="question1" value="science"> Science
            <br>
    
            <label>Question 2: What skills do you enjoy using?</label>
            <input type="radio" name="question2" value="communication"> Communication
            <input type="radio" name="question2" value="problem-solving"> Problem Solving
            <input type="radio" name="question2" value="creativity"> Creativity
            <br>
    
            <label>Question 3: What are your career goals?</label>
            <select name="question3">
                <option value="engineering">Engineering</option>
                <option value="business">Business</option>
                <option value="medicine">Medicine</option>
            </select>
            <br>
    
            <label>Question 4: What type of work environment do you prefer?</label>
            <input type="radio" name="question4" value="collaborative"> Collaborative
            <input type="radio" name="question4" value="independent"> Independent
            <br>
    
            <label>Question 5: In which activities do you feel most engaged?</label>
            <select name="question5" multiple>
                <option value="coding">Coding</option>
                <option value="drawing">Drawing</option>
                <option value="research">Research</option>
            </select>
            <br>
    
            <button type="button" class="btn" onclick="submitAptitudeTest()">Submit Test</button>
        </form>
    
        <div id="aptitudeResults" style="display: none;">
            <!-- Display aptitude test results here -->
            <h2>Results:</h2>
            <p>Your strengths align with <span id="careerFieldResult"></span>. <span id="recommendationResult"></span></p>
        </div>
        
    </div>

    <div id="Guidance" class="tabcontent">
        <h1>Career Guidance</h1>
        <form id="careerGuidanceForm">
            <label for="careerGoals">Career Goals:</label>
            <textarea name="careerGoals" id="careerGoals" rows="4" cols="50" required></textarea>
    
            <label for="interests">Interests:</label>
            <textarea name="interests" id="interests" rows="4" cols="50" required></textarea>
    
            <label for="guidanceSeeking">Specific Guidance Seeking:</label>
            <textarea name="guidanceSeeking" id="guidanceSeeking" rows="4" cols="50" required></textarea>
    
            <button type="button" class="btn" onclick="submitCareerGuidance()">Submit</button>
        </form>
    
        <div id="careerGuidanceResults" style="display: none;">
            <!-- Display guidance messages or advice here -->
            <h2>Guidance Results:</h2>
            <p id="careerAdvice"></p>
        </div>
    </div>

    <div id="Resources" class="tabcontent">
        <h1>Career Resources</h1>
        <!-- Add resource categories or sections -->
        <div class="resource-category">
            <h2>Resume Building</h2>
            <ul>
                <li><a href="resume_template.pdf" target="_blank">Download Resume Template</a></li>
                <li><a href="resume_writing_guide.pdf" target="_blank">Resume Writing Guide</a></li>
            </ul>
        </div>

        <div class="resource-category">
            <h2>Interview Preparation</h2>
            <ul>
                <li><a href="interview_tips.pdf" target="_blank">Interview Tips</a></li>
                <li><a href="common_interview_questions.pdf" target="_blank">Common Interview Questions</a></li>
            </ul>
        </div>

        <div class="resource-category">
            <h2>Industry Insights</h2>
            <ul>
                <li><a href="industry_reports.pdf" target="_blank">Latest Industry Reports</a></li>
                <li><a href="career_podcasts.html" target="_blank">Career Podcasts</a></li>
            </ul>
        </div>
    </div>

    <div id="Portfolio" class="tabcontent">
        <h1>Portfolio Management</h1>
        <!-- Project Showcase -->
        <h2>Project Showcase</h2>
        <div>
            <label for="projectName">Project Name:</label>
            <input type="text" name="projectName" id="projectName">
    
            <label for="projectDescription">Project Description:</label>
            <textarea name="projectDescription" id="projectDescription" rows="4" cols="50"></textarea>
    
            <!-- Add more fields as needed for projects -->
    
            <button class="btn" onclick="submitProject()">Add Project</button>
        </div>
    
        <hr>
    
        <!-- Education and Certifications -->
        <h2>Education and Certifications</h2>
        <div>
            <label for="degree">Degree:</label>
            <input type="text" name="degree" id="degree">
    
            <button class="btn" onclick="submitEducation()">Add Education</button>
        </div>
    
        <hr>
    
        <!-- Skills Endorsement -->
        <h2>Skills Endorsement</h2>
        <div>
            <label for="endorsedSkill">Skill to Endorse:</label>
            <input type="text" name="endorsedSkill" id="endorsedSkill">
    
            <button class="btn" onclick="endorseSkill()">Endorse Skill</button>
        </div>
    </div>

    <div id="Plans" class="tabcontent">
        <h1>Post-Graduation Plans</h1>
        <div>
            <label for="careerGoal">Career Goal:</label>
            <textarea name="careerGoal" id="careerGoal" rows="4" cols="50" required></textarea>
    
            <label for="jobSearchStatus">Job Search Status:</label>
            <select name="jobSearchStatus" id="jobSearchStatus">
                <option value="actively_searching">Actively Searching</option>
                <option value="not_searching">Not Currently Searching</option>
                <option value="exploring_opportunities">Exploring Opportunities</option>
            </select>
    
            <!-- Add more fields as needed for post-graduation plans -->
    
            <button class="btn" onclick="submitPostGraduationPlans()">Submit Plans</button>
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

        function submitAptitudeTest() {
            // Collect user responses
            var answers = {
                question1: getRadioValue("question1"),
                question2: getRadioValue("question2"),
                question3: document.getElementById("aptitudeForm").elements["question3"].value,
                question4: getRadioValue("question4"),
                question5: getSelectMultipleValues("question5")
            };

            // Perform analysis or send data to server for processing
            // For simplicity, let's assume a basic analysis here
            var results = analyzeAptitudeTest(answers);

            // Display results
            displayAptitudeResults(results);
        }

        function getRadioValue(questionName) {
            var radioButtons = document.getElementsByName(questionName);
            for (var i = 0; i < radioButtons.length; i++) {
                if (radioButtons[i].checked) {
                    return radioButtons[i].value;
                }
            }
            return null;
        }

    function getSelectMultipleValues(questionName) {
        var selectedOptions = [];
        var selectElement = document.getElementsByName(questionName)[0];
        for (var i = 0; i < selectElement.options.length; i++) {
            if (selectElement.options[i].selected) {
                selectedOptions.push(selectElement.options[i].value);
            }
        }
        return selectedOptions;
    }

    function analyzeAptitudeTest(answers) {
        // Perform analysis based on user responses
        // You can customize this function based on your criteria
        // For simplicity, let's assume a basic analysis here
        var result = {
            careerField: getDominantField(answers),
            recommendation: "Explore opportunities in the " + getDominantField(answers) + " industry."
        };

        return result;
    }

    function getDominantField(answers) {
        // Custom logic to determine the dominant field based on user responses
        // You can replace this with your own analysis
        var fieldCounts = {};
        for (var question in answers) {
            var fieldValue = answers[question];
            if (fieldCounts[fieldValue]) {
                fieldCounts[fieldValue]++;
            } else {
                fieldCounts[fieldValue] = 1;
            }
        }

        // Find the field with the maximum count
        var dominantField = null;
        var maxCount = 0;
        for (var field in fieldCounts) {
            if (fieldCounts[field] > maxCount) {
                dominantField = field;
                maxCount = fieldCounts[field];
            }
        }

        return dominantField;
    }

    function displayAptitudeResults(results) {
        // Display results in the aptitudeResults div
        var careerFieldResult = document.getElementById("careerFieldResult");
        var recommendationResult = document.getElementById("recommendationResult");
        careerFieldResult.textContent = results.careerField;
        recommendationResult.textContent = results.recommendation;

        var aptitudeResultsDiv = document.getElementById("aptitudeResults");
        aptitudeResultsDiv.style.display = "block";
    }

    function submitCareerGuidance() {
        // Collect user input
        var careerGoals = document.getElementById("careerGoals").value;
        var interests = document.getElementById("interests").value;
        var guidanceSeeking = document.getElementById("guidanceSeeking").value;

        // Send data to server for processing or perform analysis locally
        var guidanceResults = analyzeCareerGuidance(careerGoals, interests, guidanceSeeking);

        // Display guidance results
        displayCareerGuidanceResults(guidanceResults);
    }

        function analyzeCareerGuidance(careerGoals, interests, guidanceSeeking) {
            // Perform analysis based on user input
            // You can customize this function based on your criteria
            // For simplicity, let's assume a basic analysis here
            var advice = "Based on your input, consider exploring opportunities in the following areas: Technology, Business, and Creative Arts.";

            return advice;
        }

        function displayCareerGuidanceResults(guidanceResults) {
            // Display guidance results in the careerGuidanceResults div
            var careerAdvice = document.getElementById("careerAdvice");
            careerAdvice.textContent = guidanceResults;

            var careerGuidanceResultsDiv = document.getElementById("careerGuidanceResults");
            careerGuidanceResultsDiv.style.display = "block";
        }
        // Function to submit post-graduation plans
        function submitPostGraduationPlans() {
            // Collect post-graduation plans details
            var careerGoal = document.getElementById("careerGoal").value;
            var jobSearchStatus = document.getElementById("jobSearchStatus").value;

            // Perform any additional processing or validation

            // Display success message or handle errors
            alert("Post-Graduation Plans submitted successfully!");
        }
    </script>

</body>
</html>
