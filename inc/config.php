<?php 
session_start();
error_reporting(1);
include('../database/connect.php'); 
include('activity_log_function.php'); 
include('pagination_config.php'); 

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
$stmt = $dbh->query("SELECT * FROM website_settings");
$row_website = $stmt->fetch();
$app_name= $row_website['site_name'] ;
$app_email = $row_website['site_email'] ;
$app_logo = $row_website['logo'] ;
$app_url = $row_website['site_url'] ;

$paystack_secret_key = $row_website['paystack_secret_key'] ;
$paystack_public_key = $row_website['paystack_public_key'] ;


//no of vip users 
$stmt = $dbh->query("SELECT COUNT(*) as total FROM users WHERE role='vip' ");
$no_vip = $stmt->fetch();

//no of predictions
$stmt = $dbh->query("SELECT COUNT(*) as total FROM predictions");
$no_predictions = $stmt->fetch();

//no of predictions won
$stmt = $dbh->query("SELECT COUNT(*) as total FROM predictions where result='won'");
$no_predictions_won = $stmt->fetch();


//get expired date
 $sql = "SELECT u.id AS user_id, u.full_name, p.name AS plan_name, p.amount,p.duration,s.start_date,s.end_date FROM subscriptions s  INNER JOIN ( SELECT user_id, MAX(id) AS latest_sub_id FROM subscriptions GROUP BY user_id  ) latest ON s.id = latest.latest_sub_id INNER JOIN users u ON s.user_id = u.id INNER JOIN plans p ON s.plan_id = p.id     ";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $expiry_date = $subscriptions['end_date'];

?>