<?php 
//$server_name = "localhost";

//$baseurl = $_SESSION["baseurl"];
$server_name = "192.168.0.25"; //Steffi
$user_name = "stef";
$password = "steffi";
$dbname = "eecee_db";
$appname = "EECEE";

$session_exp_val= 4;

//$url = "http://localhost";
$url = "";
$remote_account_url = 'http://192.168.0.12/Tarantoo_php/Tarantoo_network.php';

//variables for login.php
$login_success_url = $eecee_login_flow_path."sel_prop.php";
$login_success_url_change_pass = $eecee_php_lib_path."change_pass.php";


//$login_forgot_pass_url = "fpassword.php";
$login_forgot_pass_url = $sense_common_php_lib_path."e_fpassword.php";
$login_signup_url = "../reg/eecee_sign.php";
$login_css ="../themes/login.css";
//$login_css ="themes/login.js";

//variables for sign.php
$confirm_email = $eecee_reg_flow_path_comm."eecee_confirm_email.php"; // registration
$login_in_butt = $eecee_login_flow_path."eecee_login.php";
$logout_path = $eecee_login_flow_path."eecee_logout.php";
$sign_success_url = $sense_common_php_lib_path."verification.php";

//variables for Adding OR NOT ADDING UNIT TO USER
$agree = "../propdef/autu.php";
$dnt_agree = "../propdef/dnautu.php";

//echo "baseurl=".$baseurl."<br>";


if ($baseurl == "actl") {
   // echo "normal key taken <br>";
    $projectID = "24MPKCZOM4DGZYU0SRU4MJ8VIXNK5X6F1LJPFT2RH0WHZA0G3W3OX9MT60GWYPSZ78ONPFU8YCXVX06I7GQJ4SR29ZSONG31I7J1VM1YB4ZUL16F47YR9J4PQVOZWFVR";
    $projectID_eecee_test = "M1PUU5JCNVPTXR71BWE9EX4PFOENU8Q1SYOWV4FD445G6Z5TTDLEPXBZ69PF06SOFXB9CKFGRAFJ2XSPSV3RXLL4WZZVPAN7LQBJY6RJ7EXOS16IRAWFCA13MGNMUU1Q";
} if ($baseurl == "enactl") {
    //echo "encryption key taken <br>";
    $projectID = "iYmgcY9E9cGg1zY8tvAeaNPffJRBSBdhT5c6t9ekc2znQJElODriqykUIHATH8w78jN6WbPtRZBhYmPVEpwOU50eVIyl9KdeF1oq5IPwy7QQxC6f1pObLinCstlOnnuH";
}

$root_role_sig = "FRONTENDCATEGORY";
$swfm_root_role_sig = "RCAT_SWFM";

$ACC_MAN_ROLE = ""

?>


