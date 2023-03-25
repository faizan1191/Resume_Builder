<?php
include_once('db_connection.php');
include_once('session.php');
include_once('redirect.php');

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : null;
$name = isset($_REQUEST['name']) ? mysqli_real_escape_string($conn, $_REQUEST['name']) : null;
$email = isset($_REQUEST['email']) ? mysqli_real_escape_string($conn, $_REQUEST['email']) : null;
$phone = isset($_REQUEST['contact']) ? $_REQUEST['contact'] : null;
$address = isset($_REQUEST['address']) ? mysqli_real_escape_string($conn, $_REQUEST['address']) : null;
$linkedin = isset($_REQUEST['linkedin']) ? mysqli_real_escape_string($conn, $_REQUEST['linkedin']) : null;
$github = isset($_REQUEST['github']) ? mysqli_real_escape_string($conn, $_REQUEST['github']) : null;
$instagram = isset($_REQUEST['instagram']) ? mysqli_real_escape_string($conn, $_REQUEST['instagram']) : null;
$objective = isset($_REQUEST['objective']) ? mysqli_real_escape_string($conn, $_REQUEST['objective']) : null;
$language = isset($_REQUEST['language']) ? mysqli_real_escape_string($conn, $_REQUEST['language']) : null;
$interest = isset($_REQUEST['interest']) ? mysqli_real_escape_string($conn, $_REQUEST['interest']) : null;
$skill = isset($_REQUEST['skill']) ? mysqli_real_escape_string($conn, $_REQUEST['skill']) : null;
$title = isset($_REQUEST['title']) ? mysqli_real_escape_string($conn, $_REQUEST['title']) : null;
$exprId = isset($_REQUEST['expr_id']) ? $_REQUEST['expr_id'] : [];
$educId = isset($_REQUEST['educ_id']) ? $_REQUEST['educ_id'] : [];
$projId = isset($_REQUEST['proj_id']) ? $_REQUEST['proj_id'] : [];
$certId = isset($_REQUEST['cert_id']) ? $_REQUEST['cert_id'] : [];
$exprPositions = isset($_REQUEST['expr_position']) ? $_REQUEST['expr_position'] : [];
$exprCompanies = isset($_REQUEST['expr_company']) ? $_REQUEST['expr_company'] : [];
$exprDurations = isset($_REQUEST['expr_duration']) ? $_REQUEST['expr_duration'] : [];
$educCourses = isset($_REQUEST['educ_course']) ? $_REQUEST['educ_course'] : [];
$educColleges = isset($_REQUEST['educ_college']) ? $_REQUEST['educ_college'] : [];
$educPercentages = isset($_REQUEST['educ_percentage']) ? $_REQUEST['educ_percentage'] : [];
$educDurations = isset($_REQUEST['educ_duration']) ? $_REQUEST['educ_duration'] : [];
$projTitles = isset($_REQUEST['proj_title']) ? $_REQUEST['proj_title'] : [];
$projDecriptions = isset($_REQUEST['proj_desc']) ? $_REQUEST['proj_desc'] : [];
$certTitles = isset($_REQUEST['cert_title']) ? $_REQUEST['cert_title'] : [];
$certDescriptions = isset($_REQUEST['cert_desc']) ? $_REQUEST['cert_desc'] : [];
$certDates = isset($_REQUEST['cert_date']) ? $_REQUEST['cert_date'] : [];


// File upload path
$imageUploaded = false;
$targetDir = "../assets/img/";
$fileName = basename($_FILES["profile_pic"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
// Allow certain file formats
$allowTypes = array('jpg', 'png', 'jpeg', 'gif');
if (in_array($fileType, $allowTypes)) {
    if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
        $imageUploaded = true;
    } else {
        $statusMsg = "Sorry, there was an error uploading your file.";
        echo $statusMsg;
        die;
    }
}

//processing experience data
$experiences = [];
$count = count($exprId) > count($exprPositions) ? count($exprId) : count($exprPositions);
for ($i = 0; $i < $count; $i++) {
    if (!empty($exprPositions[$i])){
        $experiences[] = ['position' => $exprPositions[$i], 'company' => $exprCompanies[$i], 'duration' => $exprDurations[$i]];
    }
    if(isset($exprId[$i])){
        $experiences[$i]['id'] = $exprId[$i];
    }
}

//processing education data
$education = [];
$count = count($educId) > count($educCourses) ? count($educId) : count($educCourses);
for ($i = 0; $i < $count; $i++) {
    if (!empty($educCourses[$i])){
        $education[] = [
            'course' => $educCourses[$i], 'college' => $educColleges[$i],
            'percentage' => $educPercentages[$i], 'duration' => $educDurations[$i]
        ];
    }
    if(isset($educId[$i])){
        $education[$i]['id'] = $educId[$i];
    }
}

//processing project data
$projects = [];
$count = count($projId) > count($projTitles) ? count($projId) : count($projTitles);
for ($i = 0; $i < $count; $i++) {
    if (!empty($projTitles[$i])){
        $projects[] = ['title' => $projTitles[$i], 'description' => $projDecriptions[$i]];
    }
    if(isset($projId[$i])){
        $projects[$i]['id'] = $projId[$i];
    }
}

//processing certification data
$certifications = [];
$count = count($certId) > count($certTitles) ? count($certId) : count($certTitles);
for ($i = 0; $i < $count; $i++) {
    if (!empty($certTitles[$i])){
        $certifications[] = ['title' => $certTitles[$i], 'description' => $certDescriptions[$i], 'date' => $certDates[$i]];
    }
    if(isset($certId[$i])){
        $certifications[$i]['id'] = $certId[$i];
    }
}

if ($id) {
    $sql = "UPDATE resume_data SET name='$name', email='$email', phone='$phone', address='$address',
           linkedin='$linkedin', github='$github',instagram = '$instagram', career_objective='$objective',
           language='$language', interest='$interest', skill='$skill', title='$title' where id=$id";
} else {
    $sql = "INSERT INTO resume_data (name, email, phone, address, linkedin, github,
        instagram, career_objective, language, interest, skill, title) 
        VALUES('$name', '$email', '$phone', '$address', '$linkedin', '$github', '$instagram',
        '$objective', '$language', '$interest', '$skill', '$title')";
}
if ($conn->query($sql)) {

    if (!$id) {
        // getting last recorded inserted id
        $lastId = $conn->insert_id;
    } else {
        $lastId = $id;
    }


    //adding user id in record if user is logged in
    if (!$id && $userId) {
        $sql = "UPDATE resume_data SET user_id = $userId where id=$lastId";
        $conn->query($sql);
        if ($conn->error) {
            die('Error' . $conn->error);
        }
    }
    if ($imageUploaded) {
        $fileName = mysqli_real_escape_string($conn, $fileName);
        $sql = "UPDATE resume_data SET profile_pic = '$fileName' where id=$lastId";
        $conn->query($sql);
        if ($conn->error) {
            die('Error' . $conn->error);
        }
    }

    //inserting data in experiences table
    foreach ($experiences as $expr) {
        $pos = mysqli_real_escape_string($conn, $expr['position']);
        $dur = mysqli_real_escape_string($conn, $expr['duration']);
        $comp = mysqli_real_escape_string($conn, $expr['company']);
        if (isset($expr['id']) && isset($expr['position'])) {
            $x = $expr['id'];
            $sql = "UPDATE resume_experiences SET position='$pos', company='$comp', duration='$dur' where id=$x";
        } elseif(isset($expr['id']) && !isset($expr['position'])){
            $x = $expr['id'];
            $sql = "DELETE from resume_experiences where id=$x";
        } else {
            $sql = "INSERT INTO resume_experiences (resume_id, position, company, duration)
                VALUES($lastId, '$pos', '$comp', '$dur')";
        }
        $conn->query($sql);
        if ($conn->error) {
            die('Error' . $conn->error);
        }
    }

    //inserting data in education table
    foreach ($education as $educ) {
        $course = mysqli_real_escape_string($conn, $educ['course']);
        $clg = mysqli_real_escape_string($conn, $educ['college']);
        $prcnt = mysqli_real_escape_string($conn, $educ['percentage']);
        $dur = mysqli_real_escape_string($conn, $educ['duration']);
        if (isset($educ['id']) && isset($educ['course'])) {
            $x = $educ['id'];
            $sql = "UPDATE resume_education SET course='$course', college='$clg', percentage='$prcnt', duration='$dur' where id=$x";
        }elseif(isset($educ['id']) && !isset($educ['course'])){
            $x = $educ['id'];
            $sql = "DELETE from resume_education where id=$x";
        } else {
            $sql = "INSERT INTO resume_education (resume_id, course, college, percentage, duration)
                VALUES($lastId, '$course', '$clg', '$prcnt', '$dur')";
        }
        $conn->query($sql);
        if ($conn->error) {
            die('Error' . $conn->error);
        }
    }

    //inserting data in projects table
    foreach ($projects as $proj) {
        $title = mysqli_real_escape_string($conn, $proj['title']);
        $desc = mysqli_real_escape_string($conn, $proj['description']);
        if (isset($proj['id']) && isset($proj['title'])) {
            $x = $proj['id'];
            $sql = "UPDATE resume_projects SET title='$title', description='$desc' where id=$x";
        } elseif(isset($proj['id']) && !isset($expr['title'])){
            $x = $proj['id'];
            $sql = "DELETE from resume_projects where id=$x";
        } else {
            $sql = "INSERT INTO resume_projects (resume_id, title, description)
                VALUES($lastId, '$title', '$desc')";
        }
        $conn->query($sql);
        if ($conn->error) {
            die('Error' . $conn->error);
        }
    }

    //inserting data in certificates table
    foreach ($certifications as $cert) {
        $title = mysqli_real_escape_string($conn, $cert['title']);
        $desc = mysqli_real_escape_string($conn, $cert['description']);
        $date = mysqli_real_escape_string($conn, $cert['date']);
        if (isset($cert['id']) && isset($cert['title'])) {
            $x = $cert['id'];
            $sql = "UPDATE resume_certificates SET title='$title', description='$desc', date='$date' where id=$x";
        } elseif(isset($cert['id']) && !isset($cert['title'])){
            $x = $cert['id'];
            $sql = "DELETE from resume_certificates where id=$x";
        } else {
            $sql = "INSERT INTO resume_certificates (resume_id, title, description, date)
                VALUES($lastId, '$title', '$desc' ,'$date')";
        }
        $conn->query($sql);
        if ($conn->error) {
            die('Error' . $conn->error);
        }
    }
    //Redirecting to resume template page
    Redirect('resumeTemplate.php?id=' . $lastId, false);
} else {
    die('Error' . $conn->error);
}