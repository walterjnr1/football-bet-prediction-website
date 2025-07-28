<?php 
session_start();
error_reporting(1);
include('database/connect.php'); 
include('activity_log_function.php'); 

//set time
date_default_timezone_set('Africa/Lagos');
$current_date = date('Y-m-d H:i:s');

// Define the current month and year
$current_month = date('m');
$current_year = date('Y');

//fetch user data
$user_id = $_SESSION["user_id"];
$stmt = $dbh->query("SELECT * FROM users where id='$user_id'");
$row_user = $stmt->fetch();
$role = $row_user['role'];

//website settings
$stmt = $dbh->query("SELECT * FROM website_settings ");
$row_website = $stmt->fetch();
$app_name= $row_website['site_name'] ;
$app_email = $row_website['site_email'] ;
$app_logo = $row_website['logo'] ;
$app_url = $row_website['site_url'] ;

?>