<?php
include_once('db_connection.php');
include_once('session.php');
include_once('redirect.php');

$userName = $_REQUEST['user_name'];
$email = $_REQUEST['email'];
$password = $_REQUEST['password'];

$sql = "INSERT INTO resume_users (user_name, email, password) VALUES('$userName', '$email', '$password')";

$result = $conn->query($sql);

if($conn->error){
    die("Error" . $conn->error);
}

//getting current registered user data
if($result){
    $sql = "SELECT user_name, id FROM resume_users WHERE email='$email'AND password='$password'";
    $result = $conn->query($sql);
    if($conn->error){
        die("Error" . $conn->error);
    }
    $result = $result->fetch_assoc();
    $_SESSION['user_id'] = $result['id'];
    $_SESSION['user_name'] = $result['user_name'];
    
    //Redirecting to resume form page
    Redirect('http://localhost/Resume_Builder/php/myAccount.php', false);
}