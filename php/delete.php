<?php
include_once('db_connection.php');
include_once('session.php');

$delResult = ['error'=>1, 'msg'=>"Error occured"];

if(!isset($_SESSION['user_id'])){
    $delResult['msg'] = "User not authorized to perform this action";
    echo json_encode($delResult);
    return;
}

$id = null;
if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
}else{
    $delResult['msg'] = "Resume Not Found";
    echo json_encode($delResult);
    return;
}

// fetching user id of resume record to check if 
// current user is authorized to perfrom the delete opreration
$sql = "SELECT user_id from resume_data where id=$id";
$result = $conn->query($sql);

if(!$result->num_rows>0){
    $delResult['msg'] = "Resume Not Found";
    echo json_encode($delResult);
    return;
}
$userId = $result->fetch_all(MYSQLI_ASSOC)[0]['user_id'];

// Free result set
$result -> free_result();

if($userId!=$_SESSION['user_id']){
    $delResult['msg'] = "User not authorized to perform this action";
    echo json_encode($delResult);
    return;
}else{
    //deleting given record data from experiences table (if any)
    $sql = "DELETE from resume_experiences where resume_id=$id";
    $conn->query($sql);
    
    
    //deleting given record data from education table (if any)
    $sql = "DELETE from resume_education where resume_id=$id";
    $conn->query($sql);
    
    //deleting given record data from projects table (if any)
    $sql = "DELETE from resume_projects where resume_id=$id";
    $conn->query($sql);
    
    //deleting given record data from certificates table (if any)
    $sql = "DELETE from resume_certificates where resume_id=$id";
    $conn->query($sql);
    
    //deleting the record of given id from resume_data
    $sql = "DELETE from resume_data where id=$id";
    $conn->query($sql);

    $delResult['error'] =  0;
    $delResult['msg'] =  "Resume Successfully Deleted!";
    echo json_encode($delResult);
    return;
}