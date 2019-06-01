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
//include 'curl_url_include.php';
//$log_path = "Logs/eecee.log";
include '../lib/php-lib/sec.php';
include 'prop_topo.php';
include '../lib/php-lib/session_exp.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$logfile->logfile_writeline("getting inside Validate_email PHP");

if (is_ajax()) {
    
    $myArray = [];
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
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

    $session_val= is_session_valid();

    
    if($session_val==0){
        
       if($email == $re_email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $logfile->logfile_writeline("It is a valid email ID");

            $body = "Dear New Tarantoo User,<br>
            Thank you for registering in Tarantoo.<br><br>
            
            Your registration is pending email verification.<br><br>
            ==============<br><br>
            This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
            Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your Tarantoo system administrator.";
                                    // $body="click on the below link to activate your account <br/>". "http://localhost/iot_app/iot_home/home.php?";
                                    $subject = "Tarantoo    New User Request Email Verification";
                                    $reciever = $email;
                                    $sender = "sensennium.kushal4@gmail.com";
                                    
                                    $smtp_name = "smtp.gmail.com";
                                    $smtp_port = 587;
                                    
                                    $mail = new PHPMailer();
                                    
                                    $mail->isSMTP();
                                    
                                    // $mail->SMTPDebug = 2;//to enable dibug in mailsender
                                    $mail->SMTPAuth = true;
                                    
                                    $mail->Host = $smtp_name;
                                    // Set the SMTP port number - likely to be 25, 465 or 587
                                    $mail->Port = (int) $smtp_port;
                                    $mail->SMTPSecure = 'tls';
                                    // Whether to use SMTP authentication
                                    
                                    // Username to use for SMTP authentication
                                    $mail->Username = $sender;
                                    // $mail->Username = 'bhattacharya.kushal45@yahoo.com';
                                    // Password to use for SMTP authentication
                                    $mail->Password = '05051993pom';
                                    // Set who the message is to be sent from
                                    $mail->setFrom($sender, 'Kus_yahoo');
                                    // Set an alternative reply-to address
                                    $mail->addReplyTo('sensennium.kushal4@gmail.com', 'Kus_gmail');
                                    // Set who the message is to be sent to
                                    $mail->addAddress($reciever, 'Kus_bhatt');
                                    // Set the subject line
                                    $mail->Subject = $subject;
                                    
                                    $mail->MsgHTML($body);
                                    
                                    if (! $mail->send()) {
                                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                                    } else {
                                        
                                        // echo nl2br("\n Registered Successfully!!");
                                      //header("Location:" .$sign_success_url);
                                      $logfile->logfile_writeline("email successfull");
                                    }

                                    $raw_json["ret_code"] = 0;
                                    //$raw_json["ret_msg"] = "It is not a valid email ID";
                                    $raw_json_encoce=json_encode($raw_json);
                                    echo $raw_json_encoce;

        } 
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

        

    }


    else{      
    }
    $conn->close();
    
}
$logfile->logfile_close();
?>