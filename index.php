<?php
include_once('./php/redirect.php');
include_once('./php/session.php');

if(isset($_SESSION['user_id']) && $_SESSION['user_id']){
    //Redirecting to my account page
    Redirect('./php/myAccount.php', false);
}else{
    //Redirecting to login page
    Redirect('./html/login.html', false);
}