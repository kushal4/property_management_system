<?php 
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
include '../lib/php-lib/reg_func.php';

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax()) {

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
$raw_json_str = $_POST["k"];
$json_decoded = json_decode($raw_json_str, true);

$email="";

foreach ($json_decoded as $key => $value) {
    if ($key=="email"){
        $email = $value;
    }
}


//search temp_reg_user starts
$chk_usr_exst_temp = "SELECT * FROM temp_reg_user WHERE email= ?";
$sql_usr_temp_qury=$conn->prepare($chk_usr_exst_temp);
$sql_usr_temp_qury->bind_param("s",$email);
$sql_usr_temp_qury->execute();
$sql_usr_temp_qury_res = $sql_usr_temp_qury->get_result();
$sql_usr_temp_row = $sql_usr_temp_qury_res->fetch_assoc();
$last_modi_date = $sql_usr_temp_row['last_modify_date'];
$reg_id = $sql_usr_temp_row['id'];
$token = $sql_usr_temp_row['token'];
//echo $last_modi_date;
//search temp_reg_user ends

//$raw_json["ret_code"] = 0;
//$raw_json_encoce=json_encode($raw_json);
// echo $raw_json_encoce;


//$add_48 = $last_modi_date + "48 hours";
//$abc_unix_time = strtotime($add_48);

$curr_time = strtotime("now");

$last_date = strtotime($last_modi_date);

$extra_hr = 48 * 60 * 60; 

$exp_date = $last_date + $extra_hr;

$diff = $exp_date - $curr_time;

$hours = $diff / ( 60 * 60 );

$raw_json["ret_code"] = 0;
$raw_json["ret_msg"] = "we have sent you registration email again. You have $hours hours left to verify.";
$raw_json_encoce=json_encode($raw_json);
echo $raw_json_encoce;

$email_subject = $appname ." - New User Request Email Verification";
$email_body = "Dear New $appname User,<br>
Thank you for registering in $appname.<br><br>

Your registration is pending email verification.<br><br>

Your registration ID is: $reg_id <br><br>

Please click on the following link and it will take you to a confirmation page.<br><br>
" . "$eecee_client_hostname/opt/$confirm_email?id=$reg_id&token=$token<br>" . "--$appname Administrator.<br><br>
==============<br><br>
This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your $appname system administrator.";

$email_ret_code = send_email ($smtp_server_url, $smtp_server_port, $new_reg_step1_email_sender, $new_reg_step1_email_sender_pass, $email, $email_subject, $email_body);


}
?>