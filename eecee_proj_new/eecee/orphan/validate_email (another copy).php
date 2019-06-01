<?php
session_start();
require_once ('../lib/php-lib/PHPMailer.php');
require_once ('../lib/php-lib/Exception.php');
require_once ('../lib/php-lib/SMTP.php');
require_once ('../lib/php-lib/POP3.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
//include 'curl_url_include.php';
//$log_path = "Logs/eecee.log";
include '../lib/php-lib/sec.php';
include 'prop_topo.php';
include '../lib/php-lib/session_exp.php';
include '../lib/php-lib/reg_func.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$logfile->logfile_writeline("getting inside Validate_email PHP");

if (is_ajax()) {
    
    $myArray = [];
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);

    //echo "the database name is::".$dbname;
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $unit_id="";
    $email="";
    $re_email="";

    foreach ($json_decoded as $key => $value) {
        
        if ($key=="unit_id"){
            $unit_id = $value;
        }
        if ($key=="email"){
            $email = $value;
        }
        if ($key=="re_email"){
            $re_email = $value;
        }
    }
    //echo $node_id;
    $unit_id_mapped = sec_get_map_val ("prop_topo_map", $unit_id);
    $logfile->logfile_writeline("the encoded node ID is".$unit_id);
    $logfile->logfile_writeline("the decoded node ID is".$unit_id_mapped);
    //echo "the decoded node ID is".$unit_id_mapped;

    $session_val= is_session_valid();

    $registration_id = "";
    $registration_token = "";
    $new_reg_ret_code = user_reg_step1 ($conn, "", "", $email, $re_email, $registration_id , $registration_token, "indirect");

   // echo "coming here";

    
    if($session_val==0){
        
        if ($new_reg_ret_code == 3){
            $raw_json["ret_code"] = 3;
            $raw_json["ret_msg"] = "ERROR: Email can't be blank";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
        if ($new_reg_ret_code == 4){
            $raw_json["ret_code"] = 4;
            $raw_json["ret_msg"] = "ERROR: Repeat Email can't be blank";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
    
        if ($new_reg_ret_code == 5){
            $raw_json["ret_code"] = 5;
            $raw_json["ret_msg"] = "ERROR: Email has invalid format";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
    
        if ($new_reg_ret_code == 6){
            $raw_json["ret_code"] = 6;
            $raw_json["ret_msg"] = "ERROR: Repeat Email has invalid format";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
    
        if ($new_reg_ret_code == 7){
            $raw_json["ret_code"] = 7;
            $raw_json["ret_msg"] = "ERROR: Email and Repeat Email are not same";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
    
        if ($new_reg_ret_code == 8) {
            //generate_view("","","","",$f_name, $l_name, $e_id, $rep_e_id, "A user already exists with this email id.Please login with that email id <br> <a href='login.php'>Log In Here </a>",$sign_success_url);
            
            $pin = RandomStringGenerator(8);
            $vcode = RandomStringGenerator2(64);
            $status = 2;
            $usr_invite = "INSERT INTO usr_invite_tbl(email_id, node_id, status, pin, vcode, last_modify_date) VALUES (?, ?, ?, ?, ?, now())";
            //echo "usr_invite= ".$usr_invite."\n";
            $usr_invite_temp = $conn->prepare($usr_invite);
            if ($usr_invite_temp){
                if ($usr_invite_temp->bind_param("siiss",$email, $unit_id_mapped, $status, $pin, $vcode)) {
                    //echo "bind param success"."\n";
                    $res = $usr_invite_temp->execute();
                    //echo "exec error= ".$usr_invite_temp->error."\n";

                    if ($res) {
                    }else{
                        //echo "execution failed"."\n";
                        $error_code = 3;
                        $raw_json["ret_code"]= $error_code;
                        $raw_json["ret_msg"] = "Unfortunately we encountered an internal error with code $error_code. Please contact $appname Adiministrator with the error message for further assistance.";
                    }
                }
                else{
                   // echo "bind param failed"."\n";
                    $error_code = 2;
                    $raw_json["ret_code"]= $error_code;
                    $raw_json["ret_msg"] = "Unfortunately we encountered an internal error with code $error_code. Please contact $appname Adiministrator with the error message for further assistance.";
                }
            }else{
                //echo "prepared failed"."\n";
                $error_code = 1;
                $raw_json["ret_code"]= $error_code;

                $raw_json["ret_msg"] = "Unfortunately we encountered an internal error with code $error_code. Please contact $appname Adiministrator with the error message for further assistance.";
            }
           
            
            $raw_json["f_code"] = 8;
            $raw_json["f_msg"] = "A user already exists with this email id";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;


            //echo $email;
            $sql = "SELECT * FROM reg_user WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $user_id = $row['id'];
            //echo "the first name is".$first_name;
            //echo "the last name is".$last_name;
            //echo "the user ID is:: ".$user_id;

            $prop_sql = "SELECT * FROM properties WHERE user_id = ?";
            $prop_stmt = $conn->prepare($prop_sql);
            $prop_stmt->bind_param("i",$user_id);
            $prop_stmt->execute();
            $prop_stmt_result = $prop_stmt->get_result();
            $prop_row = $prop_stmt_result->fetch_assoc();
            $prop_id = $prop_row['id'];
            $prop_name = $prop_row['setup_name'];

            $prop_topo_sql = "SELECT * FROM prop_topo WHERE id = ?";
            $prop_topo_stmt = $conn->prepare($prop_topo_sql);
            $prop_topo_stmt->bind_param("i",$unit_id);
            $prop_topo_stmt->execute();
            $prop_topo_stmt_result = $prop_topo_stmt->get_result();
            $prop_topo_row = $prop_topo_stmt_result->fetch_assoc();
            $unit_name = $prop_topo_row['node_name'];

            $email_subject = $appname ." - Adding unit to user request";
            $email_body = "Dear $first_name $last_name,<br>
            Thank you for registering in $appname.<br><br>
            
            Adminitrator of <property name> added you as owner of <unit name>.<br><br>

            Please click on the link <a href='$eecee_client_hostname/opt/$agree?Vcode=$vcode'>I agree</a> if you want to be added as owner of $unit_name of $prop_name

            Otherwise, please click on the link <a href='$eecee_client_hostname/opt/$dnt_agree?Vcode=$vcode'>I do not agree</a> if you believe you recieved this email by mistake, or do not wish to be added as owner of <unit name>
            
            Your registration ID is: $registration_id <br><br>
            
            ==============<br><br>
            This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
            Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your $appname system administrator.";

            $email_ret_code = send_email ($smtp_server_url, $smtp_server_port, $new_reg_step1_email_sender, $new_reg_step1_email_sender_pass, $email, $email_subject, $email_body);

        }
       

        if ($new_reg_ret_code == 0) {


            $status = 1;
            $usr_invite = "INSERT INTO usr_invite_tbl(email_id, node_id, status, last_modify_date) VALUES (?, ?, ?, now())";
            $usr_invite_temp = $conn->prepare($usr_invite);
            $usr_invite_temp->bind_param("sii",$email, $unit_id_mapped, $status);
            $usr_invite_temp->execute();

            $email_subject = $appname ." - New User Request Email Verification";
            $email_body = "Dear New $appname User,<br>
            Thank you for registering in $appname.<br><br>
            
            Your registration is pending email verification.<br><br>
            
            Your registration ID is: $registration_id <br><br>
            
            Please click on the following link and it will take you to a confirmation page.<br><br>
            " . "$eecee_client_hostname/opt/$confirm_email?id=$registration_id&token=$registration_token<br>" . "--$appname Administrator.<br><br>
            ==============<br><br>
            This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
            Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your $appname system administrator.";

            $email_ret_code = send_email ($smtp_server_url, $smtp_server_port, $new_reg_step1_email_sender, $new_reg_step1_email_sender_pass, $email, $email_subject, $email_body);
            //echo "email_ret_code=".$email_ret_code."<br>";

            

            $raw_json["ret_code"] = 0;
            $raw_json["ret_msg"] = "New User Registration email has been sent to email $email. User need to follow instructions in the email for registration";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }

        //} 

        /*
        else {
            $logfile->logfile_writeline("It is not a valid email ID");
            $raw_json["ret_code"] = 2;
            $raw_json["ret_msg"] = "It is not a valid email ID";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
       }
       
       else{
        $raw_json["ret_code"] = 1;
        $raw_json["ret_msg"] = "the emails doesn't match";
        $raw_json_encoce=json_encode($raw_json);
        echo $raw_json_encoce;
       }
       */

        

    }


    else{      
    }
    $conn->close();
    
}
$logfile->logfile_close();
?>