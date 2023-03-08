<?php
include_once('../php/session.php');
include_once('../php/redirect.php');

// remove all session variables
session_unset();

// destroy the session
session_destroy();

//Redirecting to login page
Redirect('http://localhost/Resume_Builder/html/login.html', false);