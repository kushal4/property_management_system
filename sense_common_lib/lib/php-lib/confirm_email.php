<?php 
session_start();
require_once ('PHPMailer.php');
require_once ('Exception.php');
require_once ('SMTP.php');
require_once ('POP3.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
include 'reg_func.php';
include 'composite_control_classes.php';
//include '../../eecee_proj/eecee/lib/eecee_constants.php';
//include '../../eecee/lib/php-lib/curl_url_include.php';

//include '../../tarantoo/lib/php-lib/db_connection.php';
//include '../../tarantoo/lib/php-lib/curl_url_include.php';
//echo "the script name is".$_SERVER['SCRIPT_NAME'];
//echo "the path name is".$login_in_butt;
function generate_view($view, $login_in_butt){
    //echo "the val is ".$url;
    //echo "the server name is ".$server_name;
    echo $view;
    if ($view=="success") {
        echo"<! DOCTYPE html>
<html>
            
   <head>
      <title>email Verification Succeeded</title>

    <link rel='stylesheet' type='text/css' href='../themes/sign.css?".time()."'>". 
   "</head>
<form action='$login_in_butt' method='GET'>

   <body>
    <div class='confirm_email_container'>
      <h1 class='confirm_email_heading_style'>Your email ID is confirmed.<br> Please find a Registration confirmation mail at your email address<br><br></h1>
<button class='login_button_style'>Click here to Log In!</button>
     </div>
   </body>
  </form>          
</html>";
    } else if ( ($view=="id_mismatch") | ($view=="token_missing") ) {
        echo"<! DOCTYPE html>
<html>
            
   <head>
      <title>email Verification Failed</title>
    <link rel='stylesheet' type='text/css' href='../../styles/sign.css'>
   </head>
            
   <body>
    <div>
      <h1 class='confirm_email_fail_heading_style'>Your email ID verification failed.<br> Your earlier request for registration has been invalidated. Please try to re-register<br><br></h1>
     </div>
   </body>
            
</html>";
        
    }
}
?>

<?php 


if($_SERVER["REQUEST_METHOD"] == "GET") {
    $id=$_GET["id"];
    $token=$_GET["token"];
    //echo $id;
    //echo $token;   
    // create connection
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
     //echo "Connected successfully!";
   
    $sql_fetch = "SELECT * from temp_reg_user WHERE token = ?";
    $stmt_fetch=$conn->prepare($sql_fetch);
    $stmt_fetch->bind_param("s",$token);
    $stmt_fetch->execute();
    $res = $stmt_fetch->get_result();
    $row=$res->fetch_assoc();
     $count = $res->num_rows;
     //echo "the number of rows 2::".$count."</br>";
     //$num_rows = mysqli_num_rows($res);
     //echo "the number of rows 2::".$num_rows."</br>";
     //echo $count;
    if($count == 1) { //token matched

        //echo "the id is::".$id."</br>";
        //echo "the id after selection is::".$row["id"]."</br>";
        if($id==$row["id"]){ //ID matched                       
            //echo "token matched";
            $temp_first_name=$row["first_name"];
            $temp_last_name=$row["last_name"];
            $temp_email=$row["email"];
            $temp_password=$row["password"];
            $status = "0";
            //$sql_insert= "INSERT INTO reg_user(first_name,last_name,email,password,last_modify_date) values 
            //('$temp_first_name','$temp_last_name','$temp_email', '$temp_password',now())";
            $sql_insert = $conn->prepare("INSERT INTO reg_user(email,password,last_modify_date, status) VALUES (?, ?, now(),?)");
            $sql_insert->bind_param("ssi", $temp_email, $temp_password, $status);
            $sql_insert->execute();
            $sql_insert_res = $sql_insert->get_result();
            $last_id = $conn->insert_id;
            
            //Create entry for this new user into user_profile data table 

            //$sql_insert_profile = $conn->prepare("INSERT INTO user_profile(first_name,last_name, user_id) VALUES (?, ?, ?)");
            //$sql_insert_profile->bind_param("ssi", $temp_first_name, $temp_last_name, $last_id);
            //$sql_insert_profile->execute();
            //$sql_insert_profile_res = $sql_insert_profile->get_result();

            $sql_insert_profile = $conn->prepare("INSERT INTO user_profile(user_id) VALUES (?)");
            $sql_insert_profile->bind_param("i", $last_id);
            $sql_insert_profile->execute();
            $sql_insert_profile_res = $sql_insert_profile->get_result();
            //$last_id = $conn->insert_id;

            $sql_delete = $conn->prepare("DELETE from temp_reg_user where id= ?");
            $sql_delete->bind_param("i", $id);
            $sql_delete->execute();
            $sql_delete_res = $sql_delete->get_result();
            //$body = "Please use this password to login " . $password;
            //echo "Saving First Name into Profile<br>";
            $op_ret_obj = update_data_tbl_fld_value_in_db_by_fld_sig ($conn, "USRPROF_FN", $temp_first_name, $last_id, $last_id, "user_id=".$last_id);
            if ($op_ret_obj["ret_code"] != 0) {
                echo "OP Code = ".$op_ret_obj["ret_code"]."; Op Msg=".$op_ret_obj["ret_msg"]."<br>";
            }
            $op_ret_obj = update_data_tbl_fld_value_in_db_by_fld_sig ($conn, "USRPROF_LN", $temp_last_name, $last_id, $last_id, "user_id=".$last_id);
            if ($op_ret_obj["ret_code"] != 0) {
                echo "OP Code = ".$op_ret_obj["ret_code"]."; Op Msg=".$op_ret_obj["ret_msg"]."<br>";
            }

            $op_ret_obj = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_FN", $last_id, "user_id=".$last_id);
            var_dump($op_ret_obj);


            $status_1 = 1;
            $status_2 = 2;
            $pin = "";
            $vcode = "";
            //echo "the email is::".$temp_email;
            //echo "the status is::".$status_1;
            $sql_user_invt = "SELECT * FROM usr_invite_tbl WHERE email_id = ? AND status = ?";
            $user_invt_stmt = $conn->prepare($sql_user_invt);
            $user_invt_stmt->bind_param("si",$temp_email, $status_1);
            $user_invt_stmt->execute();
            $user_invt_result = $user_invt_stmt->get_result();
            $user_invt_result_row = $user_invt_result->fetch_assoc();
            //var_dump($user_invt_result);
            //var_dump($user_invt_result_row);
            //foreach ($user_invt_result as $user_invt_result){
                $row_pri_key = $user_invt_result_row["id"];
                $node_id = $user_invt_result_row["node_id"];
                $type = $user_invt_result_row["type"];

                //echo "the type is:: ".$type;

                $mod_code = "new";
                $direct_invite = 0;

                
                //echo "the primary id is::".$row_pri_key."</br>";
                
                $sql = "SELECT * FROM reg_user WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s",$temp_email);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                //$first_name = $row['first_name'];
                //$last_name = $row['last_name'];
                $user_id = $row['id'];
                //echo "the user ID is:: ".$user_id."</br>";
                //$session_unit_id = $_SESSION["unit_id_sess"];
                $session_user_id = $_SESSION["user_id"];


                user_invite($conn, $row_pri_key, $temp_email, $user_id, $node_id, $status_2, $pin, $vcode,$type, $mod_code, $session_user_id, $direct_invite);
    
                $prop_sql = "SELECT * FROM properties WHERE created_by = ?";
                $prop_stmt = $conn->prepare($prop_sql);
                $prop_stmt->bind_param("i",$user_id);
                $prop_stmt->execute();
                $prop_stmt_result = $prop_stmt->get_result();
                $prop_row = $prop_stmt_result->fetch_assoc();
                $prop_id = $prop_row['id'];
                $prop_name = $prop_row['setup_name'];
 
                /*
                $prop_topo_sql = "SELECT * FROM prop_topo WHERE id = ?";
                $prop_topo_stmt = $conn->prepare($prop_topo_sql);
                $prop_topo_stmt->bind_param("i",$unit_id);
                $prop_topo_stmt->execute();
                $prop_topo_stmt_result = $prop_topo_stmt->get_result();
                $prop_topo_row = $prop_topo_stmt_result->fetch_assoc();
                $unit_name = $prop_topo_row['node_name'];
                */


                $prop_topo_arr_return = get_prop_topo_array($conn, $node_id);
                $prop_topo_arr_row = $prop_topo_arr_return["prop_topo_row"];
                $unit_name = $prop_topo_arr_row['node_name'];
                //echo "the unit ID is:: ".$node_id."</br>";
                //echo "the unit name is:: ".$unit_name."</br>";
    
                //$p_name = $_SESSION["prop_name"];

                $email_subject = $appname ." - Adding unit to user request";
                $email_body = "Dear,<br>
                Thank you for registering in $appname.<br><br>
                
                Adminitrator of $prop_name added you as owner of $unit_name.<br><br>
    
                Please click on the link <a href='$eecee_client_hostname/opt/$agree?Vcode=$vcode'>I agree</a> if you want to be added as owner of $unit_name of $prop_name
    
                Otherwise, please click on the link <a href='$eecee_client_hostname/opt/$dnt_agree?Vcode=$vcode'>I do not agree</a> if you believe you recieved this email by mistake, or do not wish to be added as owner of <unit name>
                
                ==============<br><br>
                This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
                Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your $appname system administrator.";
    
                $email_ret_code = send_email ($smtp_server_url, $smtp_server_port, $new_reg_step1_email_sender, $new_reg_step1_email_sender_pass, $temp_email, $email_subject, $email_body);

            //}
            //$num_row = mysqli_num_rows($user_invt_result);
            
            $body = "Dear $appname user, <br>
            You have been successfully registered in $appname.<br><br>
            Please note your User Name below.<br><br>
            User Name: $temp_email <br><br>
            Your temporary password is: $temp_password;
            This password is for one time use only. 
            Please log in to $appname using your username.<br> 
            Your ealier request with Registration Request ID: " . $id .  " has been deactivated and
            the verification link sent to you earlier cannot be used again.<br><br>
            Enjoy $appname,<br>
            --$appname Administrator.<br><br>
            ==============<br><br>
            This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
            Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your $appname system administrator.";
            // $body="click on the below link to activate your account <br/>". "http://localhost/iot_app/iot_home/home.php?";
            $subject = "$appname New User Registration Confirmation";
            $reciever = $temp_email;
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
                generate_view("success",$login_in_butt);
                //echo nl2br("An email has been sent to your account with your password.");
                //header("Location: welcome.php");
            }
            
            //echo "insert into main table success";
        } //ID matched
        else { //ID mismatch
            //Remove unverified registration info from tmp table 
            $sql_delete= "DELETE from temp_reg_user where token= ?";
            $sql_delete_stmt = $conn->prepare($sql_delete);
            $sql_delete_stmt->bind_param("s", $token);
            $sql_delete_stmt->execute();
            $sql_delete_res = $sql_delete_stmt->get_result();
            generate_view("id_mismatch","");
        }
    } //Token found
    else { //token not found
        generate_view("token_missing","");
        //echo "request id not found";
    }
    
}

?>

<?php
/**
 * Created by PhpStorm.
 * User: kus
 * Date: 15/2/18
 * Time: 12:12 PM
 */
//$remote_account_url = 'http://192.168.0.6/test_curl/curl-req.php';
$data = array("op" => "new_account", "account_id" => "$id", "num" => "10");
$data = json_encode($data);
// Send post data Json format
// send curl post


function CurlSendPostJson($url,$datajson){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $datajson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($datajson)));
    //curl_setopt($ch,CURLOPT_HEADER, true); //if you want headers
    return $result = curl_exec($ch);
}
$curl_debug = CurlSendPostJson($remote_account_url,$data);
//echo 'Curl: ', function_exists('curl_version') ? 'Enabled' : 'Disabled';
?>
