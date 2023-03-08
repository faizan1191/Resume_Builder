<?php
include_once('db_connection.php');
include_once('session.php');

$userId = null;
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    echo "User Account Not Found";
    die;
}

//fetching current user all resume data
$sql = "SELECT * FROM resume_data where user_id=$userId ORDER BY id DESC";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// Free result set
$result->free_result();


//fetching user details from resume_users table
$sql = "SELECT * FROM resume_users where id=$userId";
$result = $conn->query($sql);

if (!$result->num_rows > 0) {
    echo "User Account Not Found";
    die;
}
$userData = $result->fetch_all(MYSQLI_ASSOC)[0];

// Free result set
$result->free_result();

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account | Resume Builder</title>
    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
</head>

<body>
    <div id="user_info" class="container">
        <h1 class="text-center my-2"><?= "Hello " . $userData['user_name'] . "!" ?></h1>
        <div style="float: right; width: 80px; height: 80px;">
            <a href="logoutCode.php" class="btn btn-outline-danger" tabindex="-1" role="button" aria-disabled="true">Logout</a>
        </div>
        <div>
            <a href="resumeForm.php" target="_blank" class="btn btn-outline-success" tabindex="-1" role="button" aria-disabled="true">Add a Resume</a>
        </div>
    </div>
    <div id="success-msg" class="alert alert-success" role="alert" style="display:none; width: 30%; height:5rem; margin-left: 7rem;">
    <p></p>
    </div>
    <div id="error-msg" class="alert alert-danger" role="alert" style="display:none; width: 30%; height:5rem; margin-left: 7rem;">
    <p></p>
    </div>
    <div id="resume_container" class="container">
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Resume Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Title</th>
                    <th scope="col">View</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row) { ?>
                    <tr>
                        <th scope="row"><?= $row['id'] ?></th>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['phone'] ?></td>
                        <td><?= $row['title'] ?></td>
                        <td><a target="_blank" href="<?= "resumeTemplate.php?id=" . $row['id'] ?>">View</a></td>
                        <td><a target="_blank" href="<?= "resumeForm.php?id=" . $row['id'] ?>">Edit</a></td>
                        <td><button class="btn btn-danger" onclick="return deleteResume(<?= $row['id'] ?>);">Delete</button></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>

</html>
<script>
    function deleteResume($id) {
        if (confirm("Are you sure you want to delete this resume?") == true) {
            $.ajax({
                url: "delete.php?id=" + $id,
                success: function(result) {
                    if(result['error']==1){
                        $("#error-msg").html("<p>"+result['msg']+"</p>");
                        $("#error-msg").css('display','block');
                    }else{
                        $("#success-msg").html("<p>"+result['msg']+"</p>");
                        $("#success-msg").css('display','block');
                    }
                    setTimeout(()=>{
                        location.reload();
                    },3000);
                },
                dataType: "json"
            });
        }
    }
</script>