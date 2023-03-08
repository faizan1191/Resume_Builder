<?php
include_once('db_connection.php');
include_once('session.php');

//if request coming for edit of a resume
$id = null;
$row = [];
$experiences = [];
$education = [];
$projects = [];
$certificates = [];

if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
}
if ($id) {
    $sql = "SELECT * from resume_data where id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_all(MYSQLI_ASSOC)[0];
    // Free result set
    $result->free_result();

    if (!empty($row) && isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']) {
        //fetching experience details from experience table
        $sql = "SELECT * FROM resume_experiences where resume_id=$id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $experiences = $result->fetch_all(MYSQLI_ASSOC);
            // Free result set
            $result->free_result();
        }

        //fetching education details from education table
        $sql = "SELECT * FROM resume_education where resume_id=$id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $education = $result->fetch_all(MYSQLI_ASSOC);
            // Free result set
            $result->free_result();
        }

        //fetching projects details from projects table
        $sql = "SELECT * FROM resume_projects where resume_id=$id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $projects = $result->fetch_all(MYSQLI_ASSOC);
            // Free result set
            $result->free_result();
        }

        //fetching certificates details from certificates table
        $sql = "SELECT * FROM resume_certificates where resume_id=$id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $certificates = $result->fetch_all(MYSQLI_ASSOC);
            // Free result set
            $result->free_result();
        }

        // $profilePic = "../assets/img/" . $row['profile_pic'];
    } else {
        echo "User Not authorized to edit this resume.";
        die;
    }
}

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="../css/form_style.css">
    <title>Resume Form | Resume Builder</title>
</head>

<body>
    <div class="container" id="form_Container">
        <h1 class="text-center my-2">Resume Builder</h1>
        <h3 class="text-center my-2">Fill your details</h3>
        <form action="../php/formCode.php" method="post" enctype="multipart/form-data">
            <div class="row">

                <!-- first col -->
                <div id="personal_info" class="col-md-6">

                    <h4 class="text-center">Personal Information</h4>

                    <input type="hidden" name="id" value="<?= $id ?>" />
                    <!-- Name -->
                    <div class="form-group mt-2">
                        <label for="name">Name</label>
                        <input type="text" required class="form-control" id="name" name="name" placeholder="Enter your full name" value="<?= isset($row['name']) ? $row['name'] : '' ?>">
                    </div>

                    <!-- Email -->
                    <div class="form-group mt-2">
                        <label for="email">Email address</label>
                        <input type="email" required class="form-control" id="email" name="email" placeholder="Enter your email" value="<?= isset($row['email']) ? $row['email'] : '' ?>">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                            else.</small>
                    </div>

                    <!-- Contact -->
                    <div class="form-group mt-2">
                        <label for="contact">Contact</label>
                        <input type="number" required class="form-control" id="contact" name="contact" placeholder="Enter your phone number" value="<?= isset($row['phone']) ? $row['phone'] : '' ?>">
                    </div>

                    <!-- Address -->
                    <div class="form-group mt-2">
                        <label for="address">Address</label>
                        <textarea name="address" required class="form-control" id="address" placeholder="Enter your address"><?= isset($row['address']) ? $row['address'] : '' ?></textarea>
                    </div>

                    <!-- Languages -->
                    <div class="form-group language_field mt-2">
                        <label>Languages</label>
                        <input type="text" class="form-control form-control-sm" name="language" placeholder="Enter your languages sepreated by commas not spaces!" value="<?= isset($row['language']) ? $row['language'] : '' ?>">
                        <small class="form-text text-muted">Hindi,English</small>
                    </div>

                    <!-- Interests -->
                    <div class="form-group interest_field mt-2">
                        <label>Interests</label>
                        <input type="text" class="form-control form-control-sm" name="interest" placeholder="Enter your interests sepreated by commas not spaces!" value="<?= isset($row['interest']) ? $row['interest'] : '' ?>">
                        <small class="form-text text-muted">Reading Books,Listening Music</small>
                    </div>

                    <!-- Skills -->
                    <div class="form-group skill_field mt-2">
                        <label>Skills</label>
                        <input type="text" class="form-control form-control-sm" name="skill" placeholder="Enter your skills sepreated by commas not spaces!" value="<?= isset($row['skill']) ? $row['skill'] : '' ?>">
                        <small class="form-text text-muted">PHP,jquery,HTML</small>
                    </div>

                    <p class="secondary-text mt-2">Important Links</p>

                    <!-- Linkedin -->
                    <div class="form-group mt-2">
                        <label for="linkedin">Linkedin</label>
                        <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?= isset($row['linkedin']) ? $row['linkedin'] : '' ?>">
                    </div>

                    <!-- GitHub -->
                    <div class="form-group mt-2">
                        <label for="github">GitHub</label>
                        <input type="text" class="form-control" id="github" name="github" value="<?= isset($row['github']) ? $row['github'] : '' ?>">
                    </div>

                    <!-- Instagram -->
                    <div class="form-group mt-2">
                        <label for="instagram">Instagram</label>
                        <input type="text" class="form-control" id="instagram" name="instagram" value="<?= isset($row['instagram']) ? $row['instagram'] : '' ?>">
                    </div>

                </div>

                <!-- second col -->
                <div id="professional_info" class="col-md-6">
                    <h4 class="text-center">Professional Information</h4>

                    <!-- Objective -->
                    <div class="form-group mt-2">
                        <label for="objective">Career Objective</label>
                        <textarea name="objective" required class="form-control" id="objective" name="objective"><?= isset($row['career_objective']) ? $row['career_objective'] : '' ?></textarea>
                    </div>

                    <!-- Title -->
                    <div class="form-group mt-2">
                        <label for="title">Title</label>
                        <input type="text" required class="form-control" id="title" name="title" placeholder="Enter your title" value="<?= isset($row['title']) ? $row['title'] : '' ?>">
                    </div>

                    <!-- Profile Picture -->
                    <div class="form-group mt-2">
                        <label class="form-label" for="profile_pic">Profile Picture</label>
                        <input type="file" accept="image/*" class="form-control" id="profile_pic" name="profile_pic" />
                    </div>

                    <!-- Experience -->
                    <h5 class="mt-2">Work Experience</h5>
                    <div class="form-group" id="experience">
                        <?php if (empty($experiences)) { ?>
                            <div class="experience_field mt-3">
                                <!-- Experience Field Remover Button -->
                                <button class="btn btn-outline-danger btn-sm float-end remove_field mb-2" type="button" title="remove experience field">Remove</button>
                                <label>Position</label>
                                <input type="text" class="form-control form-control-sm" name="expr_position[]" value="<?= isset($expr['position']) ? $expr['position'] : '' ?>" />
                                <label>Company</label>
                                <input type="text" class="form-control form-control-sm" name="expr_company[]" value="<?= isset($expr['company']) ? $expr['company'] : '' ?>" />
                                <label>Duration</label>
                                <input type="text" class="form-control form-control-sm" name="expr_duration[]" value="<?= isset($expr['duration']) ? $expr['duration'] : '' ?>" />
                                <input type="checkbox" class="present_check">
                                <label><small class="text-muted">Present</small></label>
                            </div>
                        <?php } ?>
                        <?php foreach ($experiences as $expr) { ?>
                            <input type="hidden" name="expr_id[]" value="<?= $expr['id'] ?>" />
                            <div class="experience_field mt-3">
                                <!-- Experience Field Remover Button -->
                                <button class="btn btn-outline-danger btn-sm float-end remove_field mb-2" type="button" title="remove experience field">Remove</button>
                                <label>Position</label>
                                <input type="text" class="form-control form-control-sm" name="expr_position[]" value="<?= isset($expr['position']) ? $expr['position'] : '' ?>" />
                                <label>Company</label>
                                <input type="text" class="form-control form-control-sm" name="expr_company[]" value="<?= isset($expr['company']) ? $expr['company'] : '' ?>" />
                                <label>Duration</label>
                                <input type="text" class="form-control form-control-sm" name="expr_duration[]" value="<?= isset($expr['duration']) ? $expr['duration'] : '' ?>" />
                                <input type="checkbox" class="present_check">
                                <label><small class="text-muted">Present</small></label>
                            </div>
                        <?php } ?>
                        <!-- Experience Field Adder Button -->
                        <div class="container text-center mt-2" id="expr_btn">
                            <button class="btn btn-secondary btn-sm" type="button" onclick="fieldAdder('experience_field','expr_btn');" title="add experience field">Add</button>
                        </div>
                    </div>

                    <!-- Educational Qualification -->
                    <h5 class="mt-2">Educational Qualification</h5>
                    <div class="form-group" id="education" name="education">
                        <?php if (empty($education)) { ?>
                            <div class="education_field mt-3">
                                <!-- Education Field Remover Button -->
                                <button class="btn btn-outline-danger btn-sm float-end remove_field mb-2" type="button" title="remove education field">Remove</button>
                                <label>Course</label>
                                <input type="text" class="form-control form-control-sm" name="educ_course[]" />
                                <label>College</label>
                                <input type="text" class="form-control form-control-sm" name="educ_college[]" />
                                <label>Percentage</label>
                                <input type="float" class="form-control form-control-sm" name="educ_percentage[]" />
                                <label>Duration</label>
                                <input type="text" class="form-control form-control-sm" name="educ_duration[]" />
                                <input type="checkbox" class="present_check">
                                <label><small class="text-muted">Present</small></label>
                            </div>
                        <?php } ?>
                        <?php foreach ($education as $educ) { ?>
                            <input type="hidden" name="educ_id[]" value="<?= $educ['id'] ?>" />
                            <div class="education_field mt-3">
                                <!-- Education Field Remover Button -->
                                <button class="btn btn-outline-danger btn-sm float-end remove_field mb-2" type="button" title="remove education field">Remove</button>
                                <label>Course</label>
                                <input type="text" class="form-control form-control-sm" name="educ_course[]" value="<?= isset($educ['course']) ? $educ['course'] : '' ?>" />
                                <label>College</label>
                                <input type="text" class="form-control form-control-sm" name="educ_college[]" value="<?= isset($educ['college']) ? $educ['college'] : '' ?>" />
                                <label>Percentage</label>
                                <input type="float" class="form-control form-control-sm" name="educ_percentage[]" value="<?= isset($educ['percentage']) ? $educ['percentage'] : '' ?>" />
                                <label>Duration</label>
                                <input type="text" class="form-control form-control-sm" name="educ_duration[]" value="<?= isset($educ['duration']) ? $educ['duration'] : '' ?>" />
                                <input type="checkbox" class="present_check">
                                <label><small class="text-muted">Present</small></label>
                            </div>
                        <?php } ?>
                        <!-- Education Field Adder Button -->
                        <div class="container text-center mt-2" id="educ_btn">
                            <button class="btn btn-secondary btn-sm" type="button" onclick="fieldAdder('education_field','educ_btn');" title="add education field">Add</button>
                        </div>
                    </div>

                    <!-- Personal Projects -->
                    <h5 class="mt-2">Personal Projects</h5>
                    <div class="form-group" id="project">
                        <?php if (empty($projects)) { ?>
                            <div class="project_field mt-3">
                                <!-- Projects Field Remover Button -->
                                <button class="btn btn-outline-danger btn-sm float-end remove_field mb-2" type="button" title="remove project field">Remove</button>
                                <label>Project Title</label>
                                <input type="text" class="form-control form-control-sm" name="proj_title[]" />
                                <label>Project Description</label>
                                <textarea class="form-control form-control-sm" name="proj_desc[]"></textarea>
                            </div>
                        <?php } ?>
                        <?php foreach ($projects as $proj) { ?>
                            <input type="hidden" name="proj_id[]" value="<?= $proj['id'] ?>" />
                            <div class="project_field mt-3">
                                <!-- Projects Field Remover Button -->
                                <button class="btn btn-outline-danger btn-sm float-end remove_field mb-2" type="button" title="remove project field">Remove</button>
                                <label>Project Title</label>
                                <input type="text" class="form-control form-control-sm" name="proj_title[]" value="<?= isset($proj['title']) ? $proj['title'] : '' ?>" />
                                <label>Project Description</label>
                                <textarea class="form-control form-control-sm" name="proj_desc[]"><?= isset($proj['description']) ? $proj['description'] : '' ?></textarea>
                            </div>
                        <?php } ?>

                        <!-- Projects Field Adder Button -->
                        <div class="container text-center mt-2" id="proj_btn">
                            <button class="btn btn-secondary btn-sm" type="button" onclick="fieldAdder('project_field','proj_btn');" title="add project field">Add</button>
                        </div>
                    </div>

                    <!-- Certificates -->
                    <h5 class="mt-2">Certificates</h5>
                    <div class="form-group" id="certificate">

                        <?php if (empty($certificates)) { ?>
                            <div class="certificate_field mt-3">
                                <!-- Certificates Field Remover Button -->
                                <button class="btn btn-outline-danger btn-sm float-end remove_field mb-2" type="button" title="remove certificate field">Remove</button>
                                <label>Certificate Title</label>
                                <input type="text"  class="form-control form-control-sm" name="cert_title[]" />
                                <label>Description</label>
                                <input type="text" class="form-control form-control-sm" name="cert_desc[]" />
                                <label>Date Of Achievement</label>
                                <input type="date" class="form-control form-control-sm" name="cert_date[]" />
                            </div>
                        <?php } ?>
                        <?php foreach ($certificates as $cert) { ?>
                            <input type="hidden" name="cert_id[]" value="<?= $cert['id'] ?>" />
                            <div class="certificate_field mt-3">
                                <!-- Certificates Field Remover Button -->
                                <button class="btn btn-outline-danger btn-sm float-end remove_field mb-2" type="button" title="remove certificate field">Remove</button>
                                <label>Certificate Title</label>
                                <input type="text" class="form-control form-control-sm" name="cert_title[]" value="<?= isset($cert['title']) ? $cert['title'] : '' ?>" />
                                <label>Description</label>
                                <input type="text" class="form-control form-control-sm" name="cert_desc[]" value="<?= isset($cert['description']) ? $cert['description'] : '' ?>" />
                                <label>Date Of Achievement</label>
                                <input type="date" class="form-control form-control-sm" name="cert_date[]" value="<?= isset($cert['date']) ? $cert['date'] : '' ?>" />
                            </div>
                        <?php } ?>

                        <!-- Certificates Field Adder Button -->
                        <div class="container text-center mt-2" id="cert_btn">
                            <button class="btn btn-secondary btn-sm" type="button" onclick="fieldAdder('certificate_field','cert_btn');" title="add certificate field">Add</button>
                        </div>
                    </div>
                </div>

            </div>
            <div class="container text-center m-5">
                <button class="btn btn-primary" name="submit" type="submit">Generate Resume</button>
            </div>
        </form>
    </div>

    <!-- script -->
    <script src="../js/script.js"></script>
</body>

</html>