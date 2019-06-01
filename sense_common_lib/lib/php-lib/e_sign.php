<?php
require_once ('PHPMailer.php');
require_once ('Exception.php');
require_once ('SMTP.php');
require_once ('POP3.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

include $sense_common_php_lib_path.'reg_func.php';

function generate_view($fname_lbl,$lname_lbl,$email_lbl, $rep_email_lbl, $f_name,$l_name,$e_id, $rep_e_id, $notification_lbl, $notification_lbl2, $sign_success_url){
    
    echo "<! DOCTYPE html>
    <html>
    <head>
    
    
    <script src='../ext_lib/js-lib/jquery-3.2.1.js'></script>
    <script src='lib/js-lib/login.js?".time()."'></script>
    <link rel='stylesheet' type='text/css' href='../themes/sign.css?".time()."'>".
    "</head>
    <body>
      
    <form action='".$_SERVER["PHP_SELF"]."' method='POST'>
    <div class='container'>
    <h1>Sign Up</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>
       <span class='error_styles'>* required field.</span><br> <br>
    <label><b>First Name *</b></label>
    <input type='text' placeholder='Enter First Name' name='fname' value='$f_name'>
    <span class='error_styles'>$fname_lbl</span><br>
    
    <label><b>Last Name *</b></label>
    <input type='text' placeholder='Enter Last Name' name='lname' value='$l_name'>
    <span class='error_styles'>$lname_lbl</span><br>


    <label><b>Email *</b></label>
    <input id='email_id' type='text' placeholder='Enter Email' name='email' value='$e_id'>
    <span class='error_styles'>$email_lbl</span><br>

    <label><b>Repeat Email *</b></label>
    <input type='text' placeholder='Repeat Email' name='rep_email' value='$rep_e_id'>
    <span class='error_styles'>$rep_email_lbl</span><br>
    
    
    <div class='clearfix'>
    
    <button type='submit' class='signupbtn'>Sign Up</button>
    </div>
    <div id='notification_lbl' class='error_styles'>$notification_lbl</div>
    <div id='notification_lbl2' class='error_styles2'>$notification_lbl2</div>
    </div>
    </form>
    
    
    </body>
    </html>";
    
}
/*
function RandomStringGenerator($n) { 
    $generated_string = ""; 
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$&"; 
    $len = strlen($domain); 
    for ($i = 0; $i < $n; $i++) 
    { 
        $index = rand(0, $len - 1); 
        $generated_string = $generated_string . $domain[$index]; 
    } 
    return $generated_string; 
} 
*/

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    generate_view("","","","","","","","","","",$sign_success_url);
    
}

/* for get request */

?>


<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // create connection
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
    }
     //echo "Connected successfully!";
    
     //echo "i am in post"; 
    $f_name = $_POST["fname"];
    $l_name = $_POST["lname"];
    $e_id = $_POST["email"];
    $rep_e_id = $_POST["rep_email"];

    //$curr_time = time();
    //$curr_time_as=date("H:i:s", $curr_time);
    //echo "the current time is:: ".$curr_time_as;

    //$now = new DateTime();
    //echo $now->format('Y-m-d H:i:s');

    //$t1 = StrToTime ( '2006-04-14 11:30:00' );
    //echo $t1. "<br>";
    //$abc = "2006-04-14 11:30:00";
    //$curr_time = strtotime("now");
    //$a = '"'.$abc.'"';
    //echo $a;
    

    //$curr_time = strtotime($abc );
    //echo "the time is 2".$curr_time;

    //echo(strtotime("now"));
    //(strtotime("now") . "<br>");
    //$abc = "2006-04-12 12:30:00";
    //$t2 = StrToTime ($abc );
   // echo "t2 is::".$t2;
    //$diff = $t1 - $t2;
    //$hours = $diff / ( 60 * 60 );

    //echo "f_name=".$f_name."<br>";
    //echo "l_name=".$l_name."<br>";
    //echo "e_id=".$e_id."<br>";
    //echo "rep_e_id=".$rep_e_id."<br>";


    $reg_id = "";
    $token = "";
    $r_code = "";
    $result_array = user_reg_step1 ($conn, $f_name, $l_name, $e_id, $rep_e_id, "direct");
    //echo "registration_id=".$registration_id."<br>";
    //echo "registration_token=".$registration_token."<br>";
    //var_dump($result_array)."</br>";

    $r_code = $result_array["retcode"];
    //echo "the retcode is".$result_array["retcode"];

    if ($r_code == 1) {
        generate_view("ERROR: First name can't be blank","","","", $f_name, $l_name, $e_id, $rep_e_id, "", "", $sign_success_url);
    }
    if ($r_code == 2){
        generate_view("","ERROR: Last name can't be blank","", "", $f_name, $l_name, $e_id, $rep_e_id, "", "", $sign_success_url);
    }
    if ($r_code == 3){
        generate_view("", "","ERROR: Email can't be blank","", $f_name, $l_name, $e_id, $rep_e_id, "", "", $sign_success_url);
    }
    if ($r_code == 4){
        generate_view("", "","","ERROR: Repeat Email can't be blank", $f_name, $l_name, $e_id, $rep_e_id, "", "", $sign_success_url);
    }

    if ($r_code == 5){
        generate_view("", "","ERROR: Email has invalid format","", $f_name, $l_name, $e_id, $rep_e_id, "", "", $sign_success_url);
    }

    if ($r_code == 6){
        generate_view("", "","","ERROR: Repeat Email has invalid format", $f_name, $l_name, $e_id, $rep_e_id, "", "", $sign_success_url);
    }

    if ($r_code == 7){
        generate_view("","","","", $f_name, $l_name, $e_id, $rep_e_id, "ERROR: Email and Repeat Email are not same", "", $sign_success_url);
    }

    if ($r_code == 8) {
        generate_view("","","","",$f_name, $l_name, $e_id, $rep_e_id, "A user already exists with this email id.Please login with that email id <br> <a href='login.php'>Log In Here </a>", "", $sign_success_url);
    }
   
    if ($r_code == 10) {
        generate_view("","","","",$f_name, $l_name, $e_id, $rep_e_id, "Illegal User input. Registration failed!!!<br><br>Please make sure there is no special characters in First Name and/or in Last Name.<br><br> Please ensure Email and Repeat email are of valid email format.", "", $sign_success_url);
    }

    if (($r_code == 9) || ($r_code == 11) || ($r_code == 12) || ($r_code == 13) || ($r_code == 14) ) {
        generate_view("","","","", $f_name, $l_name, $e_id, $rep_e_id,"Unfortunately we encountered an internal error with code <b>$r_code</b>. Please contact $appname Adiministrator with the error message for further assistance.", "", $sign_success_url);
    }
    //echo "new_reg_ret_code=".$new_reg_ret_code;

    if ($r_code == 0) {
    
        $reg_id = $result_array["reg_id"];
        $token = $result_array["token"];

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
        
        
        $email_ret_code = send_email ($smtp_server_url, $smtp_server_port, $new_reg_step1_email_sender, $new_reg_step1_email_sender_pass, $e_id, $email_subject, $email_body);
        //echo "email_ret_code=".$email_ret_code."<br>";

        if ($email_ret_code == 1) {
            generate_view("","","","", $f_name, $l_name, $e_id, $rep_e_id, "Unfortunately Email could not be send [Error Code: <b>$r_code</b>]. Please contact $appname Adiministrator with the error message for further assistance.", "", $sign_success_url);
        } else {
            header("Location:" .$sign_success_url.'?email='.$e_id);
        }
        
        

    }

    if ($r_code == 15) {
        $hrs = $result_array["hours"];
        
        //generate_view("ERROR: First name can't be blank","","","", $f_name, $l_name, $e_id, $rep_e_id, "", $sign_success_url);
        generate_view("","","","", $f_name, $l_name, $e_id, $rep_e_id,"This email already requested for registration. check your $e_id for further instruction. Only $hrs hours left to verify your registration <br><br> ", "Didn't get your email? click <a id='resend_email' class='resend_email_style2' value=$e_id >Here.</a>",$sign_success_url);
    }
}

