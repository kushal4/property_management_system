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
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

include '../prop_topo.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'reg_func.php';

$logfile = new \Sense\Log($log_path, __FILE__);
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
    $first_name="";
    $last_name="";
    $op_code="";

    $sess_user_id = $SENSESSION->get_val("user_id");
    
    //echo "the user is is::: ".$sess_user_id."\n";

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
        if ($key=="first_name"){
            $first_name = $value;
        }
        if ($key=="last_name"){
            $last_name = $value;
        }
        if ($key=="op_code"){
            $op_code = $value;
        }
    }
    //echo $node_id;
    $unit_id_mapped = sec_get_map_val ("prop_topo_map", $unit_id);
    $logfile->logfile_writeline("the encoded node ID is".$unit_id);
    $logfile->logfile_writeline("the decoded node ID is".$unit_id_mapped); 
    //echo "the decoded node ID is".$unit_id_mapped;
    if ($op_code == "addtenant"){
        $SENSESSION->token("unit_id_sess", $unit_id_mapped);
    }
    

    $session_val= is_session_valid();

    //$registration_id = "";
    //$registration_token = "";
    $reg_id = "";
    $token = "";
    $r_code = "";
    $result_array = user_reg_step1 ($conn, $first_name, $last_name, $email, $re_email, "indirect");
    $r_code = $result_array["retcode"];
   
    //echo "the user id is";
    //echo "the ret code from user_reg_step1 is:: ".$r_code."\n";

    /*
    $check_reg_user = "SELECT * FROM reg_user WHERE email = ?";
    $check_reg_user_temp = $conn->prepare($check_reg_user);
    $check_reg_user_temp->bind_param("s",$email);
    $check_reg_user_temp->execute();
    $check_reg_user_temp_result = $check_reg_user_temp->get_result();
    $reg_user_temp_row = $check_reg_user_temp_result->fetch_assoc();
    $reg_user_id = $reg_user_temp_row['id'];
    */
    
    //echo "the reg_user_id is::".$reg_user_id."\n";
    


    
    //echo "the ret code is::".$r_code;
    

    if($session_val==0){
        
        if ($r_code == 1){
            $raw_json["ret_code"] = 1;
            $raw_json["ret_msg"] = "ERROR: First Name can't be blank";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }

        if ($r_code == 2){
            $raw_json["ret_code"] = 2;
            $raw_json["ret_msg"] = "ERROR: Last Name can't be blank";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
        
        if ($r_code == 3){
            $raw_json["ret_code"] = 3;
            $raw_json["ret_msg"] = "ERROR: Email can't be blank";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
        if ($r_code == 4){
            $raw_json["ret_code"] = 4;
            $raw_json["ret_msg"] = "ERROR: Repeat Email can't be blank";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
    
        if ($r_code == 5){
            $raw_json["ret_code"] = 5;
            $raw_json["ret_msg"] = "ERROR: Email has invalid format";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
    
        if ($r_code == 6){
            $raw_json["ret_code"] = 6;
            $raw_json["ret_msg"] = "ERROR: Repeat Email has invalid format";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
    
        if ($r_code == 7){
            $raw_json["ret_code"] = 7;
            $raw_json["ret_msg"] = "ERROR: Email and Repeat Email are not same";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }
    
        if ($r_code == 8) {// registered user exists
            //generate_view("","","","",$f_name, $l_name, $e_id, $rep_e_id, "A user already exists with this email id.Please login with that email id <br> <a href='login.php'>Log In Here </a>",$sign_success_url);
            
            //$num_row = mysqli_num_rows($user_prop_result);
            $prop_id = $SENSESSION->get_val("prop_id");
            $user_id = $result_array["reg_user_arr"];

            print_r($reg_user_array);

            $first_name = $first_name;
            $last_name = $last_name;
            $user_id = $reg_user_array['id'];
            $user_type = 1;
            
            //echo "the user ID is:: ".$user_id;
            $sql_user_prop = "SELECT * FROM contexts WHERE user_id = ? and prop_id = ? and unit_id = ? and user_type = ?";
            $user_prop_stmt = $conn->prepare($sql_user_prop);
            $user_prop_stmt->bind_param("iiii",$user_id, $prop_id, $unit_id_mapped, $user_type);
            $user_prop_stmt->execute();
            $user_prop_result = $user_prop_stmt->get_result();
            $user_prop_row = $user_prop_result->fetch_assoc();

            $row_num = mysqli_num_rows($user_prop_result);

            if($row_num == NULL){ // registered user not associated with this unit

           

                $pin = "";
                $vcode = "";
                $status = 2;//invitation sent for existing registered user
                if($op_code == "addtenant"){
                    $type = 4;
                }else if($op_code == "adduser"){
                    $type = 1;
                }
                //$type = 1; // owner
                $direct_invite = 1;
                $mod_code = "new";
                $mod_by = $sess_user_id;

                user_invite($conn, $unit_id_mapped, $email, $user_id, $unit_id_mapped, $status, $pin, $vcode, $type, $mod_code, $sess_user_id, $direct_invite);
                //$usr_invite = "INSERT INTO usr_invite_tbl(email_id, node_id, status, pin, vcode, last_modify_date) VALUES (?, ?, ?, ?, ?, now())";

                /*
                $usr_invite = "INSERT INTO usr_invite_tbl(email_id, node_id, status, type, mod_code, mod_by, latest) VALUES (?, ?, ?, ?, ?, ?, ?)";
                //echo "usr_invite= ".$usr_invite."\n";
                $usr_invite_temp = $conn->prepare($usr_invite);
                $latest = 1;

                if ($usr_invite_temp){
                    if ($usr_invite_temp->bind_param("siiisii",$email, $unit_id_mapped, $status, $type, $mod_code, $sess_user_id, $latest)) {
                        //echo "bind param success"."\n";
                        $res = $usr_invite_temp->execute();
                        //echo "exec error= ".$usr_invite_temp->error."\n";

                        if ($res) {
                            $last_id = $conn->insert_id;
                            //echo "the last ID is:: ".$last_id;
                            user_invite($conn, $last_id, $email, $unit_id_mapped, $status, $pin, $vcode, $type, $mod_code, $sess_user_id);
                            //echo "the pin is".$pin;
                            //echo "the v code is".$vcode;
                            //echo "the vcode is:: ".$vcode."\n";
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
                */
                
                $raw_json["f_code"] = 8;
                $raw_json["f_msg"] = "A user already exists with this email id. We have sent you an email requesting you to add the unit. check your $email for further instruction.";
                $raw_json_encoce=json_encode($raw_json);
                echo $raw_json_encoce;

                //echo $email;
                /*
                $sql = "SELECT * FROM reg_user WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s",$email);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $first_name = $row['first_name'];
                $last_name = $row['last_name'];
                $user_id = $row['id'];
                echo "the first name is".$first_name."\n";
                echo "the last name is".$last_name."\n";
                echo "the user ID is:: ".$user_id."\n";
                */

                /*
                $prop_sql = "SELECT * FROM properties WHERE created_by = ?";
                $prop_stmt = $conn->prepare($prop_sql);
                $prop_stmt->bind_param("i",$sess_user_id);
                $prop_stmt->execute();
                $prop_stmt_result = $prop_stmt->get_result();
                $prop_row = $prop_stmt_result->fetch_assoc();
                $prop_id = $prop_row['id'];
                $prop_name = $prop_row['setup_name'];
                echo "the property ID is:: ".$prop_id."\n";
                echo "the property name is:: ".$prop_name."\n";
                echo "the unit ID is::".$unit_id_mapped."\n";
                */

                $prop_arr_return = get_properties_array($conn, $sess_user_id);
                $prop_arr_row = $prop_arr_return["prop_row"];
                $prop_name = $prop_arr_row['setup_name'];

                $prop_topo_arr_return = get_prop_topo_array($conn, $unit_id_mapped);
                $prop_topo_arr_row = $prop_topo_arr_return["prop_topo_row"];
                $unit_name = $prop_topo_arr_row['node_name'];

                echo "the autu link is".$agree;

                /*
                $prop_topo_sql = "SELECT * FROM prop_topo WHERE id = ?";
                $prop_topo_stmt = $conn->prepare($prop_topo_sql);
                $prop_topo_stmt->bind_param("i",$unit_id_mapped);
                $prop_topo_stmt->execute();
                $prop_topo_stmt_result = $prop_topo_stmt->get_result();
                $prop_topo_row = $prop_topo_stmt_result->fetch_assoc();
                $unit_name = $prop_topo_row['node_name'];
                echo "the flat name is:: ".$unit_name."\n";
                */

                if($op_code == "addtenant"){
                    $type = 4;
                    $user_type = "Tenant";
                    $email_subject = $appname ." - Request for Addition - $user_type";
                    $email_body = "Dear $first_name $last_name,<br>
                    Thank you for registering in $appname.<br><br>
                    
                    Owner of $unit_name of $$prop_name wants to add you as the $user_type of $unit_name.<br><br>

                    Please click on the link <a href='$eecee_client_hostname/opt/$agree?Vcode=$vcode'>I agree</a> if you want to be added as $user_type of #### $unit_name of $p_name.

                    Otherwise, please click on the link <a href='$eecee_client_hostname/opt/$dnt_agree?Vcode=$vcode'>I do not agree</a> <br>If you believe you recieved this email by mistake, or do not wish to be added as $user_type of $unit_name of $prop_name.<br>
                    
                    ==============<br><br>
                    This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
                    Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your $appname system administrator.";
                }else if($op_code == "adduser"){
                    echo "boom";
                    $type = 1;
                    $user_type = "owner";

                    $email_subject = $appname ." - Request for Addition - $user_type";
                    $email_body = "Dear $first_name $last_name,<br>
                    Thank you for registering in $appname.<br><br>
                    
                    Adminitrator of $prop_name wants to add you as the $user_type of $unit_name.<br><br>

                    Please click on the link <a href='$eecee_client_hostname/opt/$agree?Vcode=$vcode'>I agree</a> if you want to be added as owner of #### $unit_name of $prop_name.

                    Otherwise, please click on the link <a href='$eecee_client_hostname/opt/$dnt_agree?Vcode=$vcode'>I do not agree</a> <br>If you believe you recieved this email by mistake, or do not wish to be added as owner of $unit_name of $prop_name.<br>
                    
                    ==============<br><br>
                    This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
                    Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your $appname system administrator.";
                }


                //$email_subject = $appname ." - Request for Addition - $user_type";
                
                
                /*
                $email_body = "Dear $first_name $last_name,<br>
                Thank you for registering in $appname.<br><br>
                
                Adminitrator of $$p_name wants to add you as the owner of $unit_name.<br><br>

                Please click on the link <a href='$eecee_client_hostname/opt/$agree?Vcode=$vcode'>I agree</a> if you want to be added as owner of #### $unit_name of $prop_name.

                Otherwise, please click on the link <a href='$eecee_client_hostname/opt/$dnt_agree?Vcode=$vcode'>I do not agree</a> <br>If you believe you recieved this email by mistake, or do not wish to be added as owner of $unit_name of $prop_name.<br>
                
                ==============<br><br>
                This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
                Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your $appname system administrator.";
                */

                $email_ret_code = send_email ($smtp_server_url, $smtp_server_port, $new_reg_step1_email_sender, $new_reg_step1_email_sender_pass, $email, $email_subject, $email_body);
            }else{// registered user is already associated with this unit
                $raw_json["f_code"] = 16;
                $raw_json["f_msg"] = "This user is already associated with this Unit";
                $raw_json_encoce=json_encode($raw_json);
                echo $raw_json_encoce;
            }
        }

        if ($r_code == 15) {
            
            $exp_flag = $result_array["expd_flag"];
            $raw_json["ret_code"] = 15;
            $hrs = $result_array["hours"];
            $raw_json["ret_msg"] = "This email has already been used for registration request. check your $email for further instruction. Only $hrs left before the registration request expires.";
            $raw_json_encoce=json_encode($raw_json);
            echo $raw_json_encoce;
        }

        
       

        if ($r_code == 0) {

            $reg_id = $result_array["reg_id"];
            $token = $result_array["token"];

            $status = 1;

            //$type = 1;
            if($op_code == "addtenant"){
                $type = 4;
                echo "the type is::".$type;
            }else if($op_code == "adduser"){
                $type = 1;
                echo "the type is::".$type;
            }
            $mod_code = "new";
            $mod_by = $sess_user_id;
            $latest_1 = 1;
            $direct_invite = 1;
  
            /*
            $usr_invite = "INSERT INTO usr_invite_tbl(email_id, node_id, status, type, mod_code, mod_by, latest) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $usr_invite_temp = $conn->prepare($usr_invite);
            $usr_invite_temp->bind_param("siiisii",$email, $unit_id_mapped, $status, $type, $mod_code, $mod_by, $latest_1);
            $usr_invite_temp->execute();
            */
            $pin = "";
            $vcode = "";
            $u_id = "";

            echo "the confirm email is : ".$confirm_email;
            //echo "the node ID is:: ".$unit_id_mapped;
            //echo "the node ID is:: ".$unit_id;
            user_invite($conn, $unit_id_mapped, $email, $u_id, $unit_id_mapped, $status, $pin, $vcode, $type, $mod_code, $mod_by, $direct_invite);

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
            //echo "email_ret_code=".$email_ret_code."<br>";

            

            $raw_json["ret_code"] = 0;
            $raw_json["ret_msg"] = "New User Registration email has been sent to email $email. User needs to follow instructions in the email for verification.";
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