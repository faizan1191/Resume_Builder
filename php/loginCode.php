<?php
include_once('db_connection.php');
include_once('session.php');
include_once('redirect.php');

$email = $_REQUEST['email'];
$password = $_REQUEST['password'];

$sql = "SELECT user_name, id FROM resume_users WHERE email='$email'AND password='$password'";

$result = $conn->query($sql);

if($conn->error){
    die("Error" . $conn->error);
}

$result = $result->fetch_all(MYSQLI_ASSOC);
if(!empty($result) &&!empty($result[0])){
    $result = $result[0];
    $_SESSION['user_id'] = $result['id'];
    $_SESSION['user_name'] = $result['user_name'];
    
    //Redirecting to resume form page
    Redirect('myAccount.php', false);
}else{
    echo "User Account Not Found";
    die;
}