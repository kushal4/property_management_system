<?php

require_once ('PHPMailer.php');
require_once ('Exception.php');
require_once ('SMTP.php');
require_once ('POP3.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

//echo "getting inside reg_func"."\n";

function RandomStringGenerator($n) { 
    $generated_string = ""; 
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$"; 
    $len = strlen($domain); 
    for ($i = 0; $i < $n; $i++) 
    { 
        $index = rand(0, $len - 1); 
        $generated_string = $generated_string . $domain[$index]; 
    } 
    return $generated_string; 
} 

function RandomStringGenerator2($n) { 
    $generated_string = ""; 
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
    $len = strlen($domain); 
    for ($i = 0; $i < $n; $i++) 
    { 
        $index = rand(0, $len - 1); 
        $generated_string = $generated_string . $domain[$index]; 
    } 
    return $generated_string; 
} 

function user_invite($conn, $pri_key, $email, $user_id, $node_id, $status, &$pin, &$vcode, $type, $mod_code, $mod_by, $direct = 0) {
    //echo "getting inside user_invite.php";
    $pin = RandomStringGenerator(8);
    $vcode = RandomStringGenerator2(64);
  
    //echo "getting inside this function"."\n";
    //echo "the node is is:: (reg_func.php)".$node_id."\n";
    //$usr_invite_update = "UPDATE usr_invite_tbl SET pin=?, vcode=?  WHERE id=?";
    //$usr_invite_update_stmt=$conn->prepare($usr_invite_update);
    //$usr_invite_update_stmt->bind_param("ssiisi", $pin, $vcode, $pri_key, $status);
    //$usr_invite_update_stmt->execute();

    if ($direct == 0){

        $latest_0 = 0;
        $sql_user_prop = "UPDATE usr_invite_tbl SET latest=?  WHERE id=?";
        $user_prop_stmt = $conn->prepare($sql_user_prop);
        $user_prop_stmt->bind_param("ii",$latest_0, $pri_key);
        $user_prop_stmt->execute();
    }
    $latest_1 = 1;
    $mod_upd = "upd";

    //$userID = "";



    //echo "***** the user ID is::: ******".$user_id;
    $usr_invite = "INSERT INTO usr_invite_tbl(email_id, user_id, node_id, status, pin, vcode, type, mod_code, mod_by, latest) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $usr_invite_temp = $conn->prepare($usr_invite);
    if($usr_invite_temp){
        //echo "prepare successful"."\n";
        $bind = $usr_invite_temp->bind_param("siiissisii",$email, $user_id, $node_id, $status, $pin, $vcode, $type, $mod_upd, $mod_by, $latest_1);
        if($bind){
            //echo "bind successful"."\n";
            $execute = $usr_invite_temp->execute();
            //echo "exec error= ".$usr_invite_temp->error."\n";
            if($execute){
                //echo "execution successful"."\n";
            }else{
                //echo "execution failed"."\n";
            }
        }else{
            //echo "bind failed"."\n";
        }
    }else{
        //echo "prepare failed"."\n";
    }

    

    
    /*
    $mod_new = "new";



    $lat_0 = 0;
    $sta_1 = 1;
    $sql_user_prop = "UPDATE usr_invite_tbl SET latest=?  WHERE node_id=? and status = ? and mod_code = ?";
    $user_prop_stmt = $conn->prepare($sql_user_prop);
    $user_prop_stmt->bind_param("iiis",$lat_0, $node_id, $sta_1, $mod_new);
    $user_prop_stmt->execute();
    */
 
}



function user_reg_step1 ($dbconn, $fname, $lname, $email, $rep_email, $flow){
    $ret_obj=[];
    $retcode = "";
    //Blank checks
    if (($fname == "") && ($flow=="direct") ) {
        //return 1; //Error: First Name blank
        $ret_obj["retcode"] = 1;//Error: First Name blank
        return $ret_obj;
    } else if ( ($lname == "") && ($flow=="direct") ){
        //return 2; //Error: Last Name blank
        $ret_obj["retcode"] = 2;
        return $ret_obj;//Error: Last Name blank
    } else if ($email == ""){
        //return 3; //Error: Email blank
        $ret_obj["retcode"] = 3;
        return $ret_obj;//Error: Email blank
    } else if ($rep_email == ""){
        //return 4; //Error: Repeat Email blank
        $ret_obj["retcode"] = 4;
        return $ret_obj;//Error: Repeat Email blank
    }
    //Email format validity check
    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //return 5; //Error: Email format not valid
        $ret_obj["retcode"] = 5;
        return $ret_obj;//Error: Email format not valid
    }
    if (! filter_var($rep_email, FILTER_VALIDATE_EMAIL)) {
        //return 6; //Error: Repeat Email format not valid
        $ret_obj["retcode"] = 6;
        return $ret_obj;//Error: Repeat Email format not valid
    }
    //Check if email and Repeat Email matches
    if ($email != $rep_email) {
       // return 7; //Error: Email and Repeat Email do not match
        $ret_obj["retcode"] = 7;
        return $ret_obj;//Error: Email and Repeat Email do not match
    }

    //search temp_reg_user starts
    $chk_usr_exst_str = "SELECT * FROM reg_user WHERE email= ?";
    $sql_usr_qury=$dbconn->prepare($chk_usr_exst_str);
    $sql_usr_qury->bind_param("s",$email);
    $sql_usr_qury->execute();
    $sql_usr_qury_res = $sql_usr_qury->get_result();
    $sql_usr_qury_res_row = $sql_usr_qury_res->fetch_assoc();
    $reg_user_id = $sql_usr_qury_res_row['id'];
     //search temp_reg_user ends

    //search temp_reg_user starts
    $chk_usr_exst_temp = "SELECT * FROM temp_reg_user WHERE email= ?";
    $sql_usr_temp_qury=$dbconn->prepare($chk_usr_exst_temp);
    $sql_usr_temp_qury->bind_param("s",$email);
    $sql_usr_temp_qury->execute();
    $sql_usr_temp_qury_res = $sql_usr_temp_qury->get_result();
    $sql_usr_temp_row = $sql_usr_temp_qury_res->fetch_assoc();
    $last_modi_date = $sql_usr_temp_row['last_modify_date'];
    $temp_pri_id = $sql_usr_temp_row['id'];
    
    
    //search temp_reg_user ends

    if (mysqli_num_rows($sql_usr_qury_res) > 0) { //If result has returned at least a row
        //return 8; //Error: This email is already registered
        $ret_obj["retcode"] = 8;
        $ret_obj["reg_user_id"] = $reg_user_id;
        $ret_obj["reg_user_arr"] = $sql_usr_qury_res_row;
        return $ret_obj;//Error: This email is already registered
    }

    if (mysqli_num_rows($sql_usr_temp_qury_res) > 0) { //If result has returned at least a row in temp_reg_user
        //return 15; //Error: This email already requested for registration
        
        //Error: This email already requested for registration

        /*
        $curr_time = time();
        $curr_time_as=date("H:i:s", $curr_time);
        $splitTimeStamp = explode(" ",$last_modi_date);
        $last_date = $splitTimeStamp[0];
        $last_time = $splitTimeStamp[1];
        */

        //$l_date = '"'.$last_modi_date.'"';
        //echo "the l date is".$l_date."<br>";
        //$add_48 = $last_modi_date + "48 hours";
        //$abc_unix_time = strtotime($add_48);
        //echo "the abc date is:: ".date("Y-m-d H:i:s", $abc_unix_time)."<br>";
        //echo "the abc unix timestamp is:: ".$abc_unix_time."<br>";

        $curr_time = strtotime("now");
        //echo "the current time is:: ".$curr_time."<br>"; 
        //$last_date = strtotime($l_date);
        //echo "the last modify date:: ".$last_modi_date."<br>";
        $last_date = strtotime($last_modi_date);
        //echo "the last date modified date unix timestamp is:: ".$last_date."<br>";

        $extra_hr = 48 * 60 * 60; //48 hours
        //echo "the extra hour is:: ".$extra_hr;
        $exp_date = $last_date + $extra_hr;
        //echo "the expiry  date is:: ".date("Y-m-d H:i:s", $exp_date)."<br>";
        //echo "the expiry date unix timestamp is:: ".$exp_date."<br>";

        $diff = $exp_date - $curr_time;
        //echo "the difference is::".$diff."\n";

        $hr_min_sc = gmdate("H:i:s", $diff);

        $splitTimeStamp = explode(":",$hr_min_sc);
        $rem_hr = $splitTimeStamp[0];
        $rem_min = $splitTimeStamp[1];
        $rem_sec = $splitTimeStamp[2];
        //echo "the remaining hours are ::".$rem_hr."\n";
        //echo "the remaining mins are ::".$rem_min."\n";
        //echo "the remaining secs are ::".$rem_sec."\n";

        $final_rem_time = $rem_hr."Hr"." ".$rem_min."Mins"."\n";
        //echo "the final time is:: ".$final_rem_time;

        $hours_left = $diff / ( 60 * 60 );

        $mins_left = $diff * 60;
        
        $rounded_hours = round($hours_left, 1);

        
        if($mins_left <= 0 ){
            $ret_obj["expd_flag"] = 1; //expired
            $ret_obj["retcode"] = 16;

            $sql_del  =  "DELETE FROM temp_reg_user WHERE id = ?";
            $sql_del_temp = $dbconn->prepare($sql_del);
            $sql_del_temp->bind_param("i", $temp_pri_id);
            $sql_del_temp->execute();
            $sql_del_temp->close();

        }else if($mins_left > 0){
            $ret_obj["retcode"] = 15;
            $ret_obj["hours"] = $final_rem_time;
            $ret_obj["expd_flag"] = 0; //not expired
            return $ret_obj;
        }    

    }else{

        $ret_obj["retcode"] = 0;
        
    }


    if($ret_obj["retcode"] == 16 || $ret_obj["retcode"] == 0){
        //echo "getting inside this ******"."\n";
        $pass = RandomStringGenerator(8);
        $sql_insrt="INSERT INTO temp_reg_user(first_name, last_name, email, password, last_modify_date) VALUES (?, ?, ?, ?, now())";
        $sql_temp = $dbconn->prepare($sql_insrt);
    
        if ($sql_temp) {
            
            if ($sql_temp->bind_param("ssss", $fname, $lname, $email, $pass) ) {
                
                if ($sql_temp->execute()) {
                    
                    $last_id = $dbconn->insert_id;
                    $reg_id  = $last_id;
                    //echo "last insert id of the row is".$last_id;
                    $md5_input = $last_id . $email;
                    $md5_str = md5($md5_input);
                    $md5_input = $email . $last_id;
                    $md5_str .= md5($md5_input);
                    $token = $md5_str;
                    //echo "the token is:: ".$md5_str;
                    //echo "the ID is:: ".$last_id;    
    
                    $sql_update="UPDATE temp_reg_user SET token=? where id=?";
                    $sql_up_stmt=$dbconn->prepare($sql_update);
    
                    if ($sql_up_stmt) {
                        
                        if ($sql_up_stmt->bind_param("si", $md5_str, $last_id)) {
                            if ($sql_up_stmt->execute()) {
                                //return 0;  
                                $ret_obj["retcode"] = 0;
                                $ret_obj["reg_id"] = $reg_id;
                                $ret_obj["token"] = $token;
                                return $ret_obj;//Error: Update Execute failed   
                            } else {
                                //DELETE with last insert ID
                                //return 14; //Error: Update Execute failed   
                                $ret_obj["retcode"] = 14;
                                return $ret_obj;//Error: Update Execute failed                            
                            }
                        } else {
                            //DELETE with last insert ID
                            //return 13; //Error: Update bind Param failed    
                            $ret_obj["retcode"] = 13;
                            return $ret_obj;//Error: Update bind Param failed 
                        }
                        
                    } else {
                        //DELETE with last insert ID
                        //return 12; //Error: Update Prepare failed  
                        $ret_obj["retcode"] = 12;
                        return $ret_obj;//Error: Update Prepare failed     
                    }
                    
                } else {
                    //return 11; //Error: Insert Execute failed  
                    $ret_obj["retcode"] = 11;
                    return $ret_obj;//Error: Insert Execute failed     
                }
                
            } else {
                //return 10; //Error: Insert Bind Param failed  
                $ret_obj["retcode"] = 10;
                return $ret_obj;//Error: Insert Bind Param failed  
            }
            
        } else {
            //return 9; //Error: the Insert Prepare statement failed
            $ret_obj["retcode"] = 9;
            return $ret_obj;//Error: the Insert Prepare statement failed
        }
    }

    //Generate a random password
    //TODO: need a rule based password generator
    
} //function user_reg_step1




function send_email ($email_server, $email_port, $email_sender, $email_sender_pass, $to_email, $email_subject, $email_body){
    //Blank checks
    $smtp_name = $email_server;
    $smtp_port = $email_port;

    $subject = $email_subject;
    $sender = $email_sender;
    $reciever = $to_email;
    $body = $email_body;

    $mail = new PHPMailer();
    
    $mail->isSMTP();
    
    // $mail->SMTPDebug = 2;//to enable dibug in mailsender
    $mail->SMTPAuth = true;
    
    $mail->Host = $smtp_name;
    // Set the SMTP port number - likely to be 25, 465 or 587
    $mail->Port = (int) $smtp_port;
    $mail->SMTPSecure = 'tls';
    // Username to use for SMTP authentication
    $mail->Username = $sender;
    // Password to use for SMTP authentication
    $mail->Password = $email_sender_pass;
    // Set who the message is to be sent from
    //$mail->setFrom($sender, 'Kus_yahoo');
    $mail->setFrom($sender);
    // Set an alternative reply-to address
    //$mail->addReplyTo($sender, 'Kus_gmail');
    // Set who the message is to be sent to
    //$mail->addAddress($reciever, 'Kus_bhatt');
    $mail->addAddress($reciever);
    // Set the subject line
    $mail->Subject = $subject;
    
    $mail->MsgHTML($body);
    //$mail->send();
    
    if (! $mail->send()) {
        //DELETE with last insert ID
        return 1; //Error: Mailer Error                                
    } else {
        return 0;
    }
    
    //return 1;
} //function user_reg_step1

function get_properties_array($conn, $sess_user_id){
    //echo "getting inside get_properties_array function ";
    $prop_sql = "SELECT * FROM properties WHERE created_by = ?";
    $prop_stmt = $conn->prepare($prop_sql);
    $prop_stmt->bind_param("i",$sess_user_id);
    $prop_stmt->execute();
    $prop_stmt_result = $prop_stmt->get_result();
    $prop_row = $prop_stmt_result->fetch_assoc();

    $prop_obj["prop_row"] = $prop_row;
    return $prop_obj;
}

function get_properties_array_id($conn, $sess_user_id){
    //echo "getting inside get_properties_array function ";
    $prop_sql = "SELECT * FROM properties WHERE id = ?";
    $prop_stmt = $conn->prepare($prop_sql);
    $prop_stmt->bind_param("i",$sess_user_id);
    $prop_stmt->execute();
    $prop_stmt_result = $prop_stmt->get_result();
    $prop_row = $prop_stmt_result->fetch_assoc();

    $prop_obj["prop_row"] = $prop_row;
    return $prop_obj;
}

function get_prop_topo_array($conn, $unit_id_mapped){
    //echo "getting inside get_prop_topo_array function "."\n";
    //echo "the node ID is:: ".$unit_id_mapped."\n";
    $prop_topo_sql = "SELECT * FROM prop_topo WHERE id = ?";
    $prop_topo_stmt = $conn->prepare($prop_topo_sql);
    $prop_topo_stmt->bind_param("i",$unit_id_mapped);
    $prop_topo_stmt->execute();
    $prop_topo_stmt_result = $prop_topo_stmt->get_result();
    $prop_topo_row = $prop_topo_stmt_result->fetch_assoc();

    $prop_topo_obj["prop_topo_row"] = $prop_topo_row;
    return $prop_topo_obj;
}

function unit_dialog($user_dets_parent_div){
    //echo "getting inside unit dialog";
    //$user_dets_dialog = insertPanel($dialog_parent, '{"id":"user_dets_dialog", "class":"user_dets_dialog_style", "title":"User Details "}',"");
    //$user_dets_parent_div = insertElement($user_dets_dialog,"div",'{"class":"user_dets_parent_div_style"}', "");

    /*
    $unit_name_span_cont = insertElement($user_dets_dialog,"div",'{"id": "unit_name_span_cont", "class":"unit_name_span_cont_style"}', "");
    $unit_name_span = insertElement($unit_name_span_cont,"span",'{"id":"unit_name_span", "class":"unit_name_span_style"}', "");
    */

    //$no_user_err_cont = insertElement($user_dets_parent_div,"div",'{"class":"no_user_err_cont_style"}', "");

    //$user_dets_dialog = insertPanel($user_dets_parent_div, '{"id":"user_dets_dialog", "class":"user_dets_dialog_style", "title":"User Details "}',"");

    $no_user_err_span_cont = insertElement($user_dets_parent_div,"div",'{"id":"no_user_err_span_cont","class":"no_user_err_span_cont_style"}', "");
    $no_user_err_span = insertElement($no_user_err_span_cont,"span",'{"id":"no_user_err_span", "class":"no_user_err_span_style"}', "");


    $add_new_user_parent_cont = insertElement($user_dets_parent_div,"div",'{"id":"add_new_user_parent_cont","class":"add_new_user_parent_cont_style"}', "");

    $add_new_user_span_cont = insertElement($add_new_user_parent_cont,"div",'{"id":"add_new_user_span_cont","class":"add_new_user_span_cont_style"}', "");
    $add_new_user_span = insertElement($add_new_user_span_cont,"span",'{"id":"add_new_user_span", "class":"add_new_user_span_style"}', "");

    $add_new_user_btns_cont = insertElement($add_new_user_parent_cont,"div",'{"id":"add_new_user_btns_cont","class":"add_new_user_btns_cont_style"}', "");
    $add_new_user_yes_btn = insertElement($add_new_user_btns_cont,"button",'{"id":"add_new_user_yes_btn", "class":"add_new_user_yes_btn_style"}', "Yes");
    $add_new_user_no_btn = insertElement($add_new_user_btns_cont,"button",'{"id":"add_new_user_no_btn", "class":"add_new_user_no_btn_style"}', "No");

    $add_email_parent_cont = insertElement($user_dets_parent_div,"div",'{"id":"add_email_parent_cont","class":"add_email_parent_cont_style"}', "");

    $first_name_parent_cont = insertElement($add_email_parent_cont,"div",'{"id":"first_name_parent_cont","class":"first_name_parent_cont_style"}', "");
    $first_name_cont = insertElement($first_name_parent_cont,"div",'{"id":"first_name_cont","class":"first_name_cont_style"}', "");
    $first_name_span = insertElement($first_name_cont,"span",'{"id":"first_name_span","class":"first_name_span_style"}', "First Name");
    $first_name_input_cont = insertElement($first_name_parent_cont,"div",'{"id":"first_name_input_cont","class":"first_name_input_cont_style"}', "");
    $first_name_input = insertElement($first_name_input_cont,"input",'{"id":"first_name_input","class":"first_name_input_style"}', "");


    $last_name_parent_cont = insertElement($add_email_parent_cont,"div",'{"id":"last_name_parent_cont","class":"last_name_parent_cont_style"}', "");
    $last_name_cont = insertElement($last_name_parent_cont,"div",'{"id":"last_name_cont","class":"last_name_cont_style"}', "");
    $last_name_span = insertElement($last_name_cont,"span",'{"id":"last_name_span","class":"last_name_span_style"}', "Last Name");
    $last_name_input_cont = insertElement($last_name_parent_cont,"div",'{"id":"last_name_input_cont","class":"last_name_input_cont_style"}', "");
    $last_name_input = insertElement($last_name_input_cont,"input",'{"id":"last_name_input","class":"last_name_input_style"}', "");


    $first_email_parent_cont = insertElement($add_email_parent_cont,"div",'{"id":"first_email_parent_cont","class":"first_email_parent_cont_style"}', "");
    $first_enter_email_cont = insertElement($first_email_parent_cont,"div",'{"id":"first_enter_email_cont","class":"first_enter_email_cont_style"}', "");
    $first_enter_email_span = insertElement($first_enter_email_cont,"span",'{"id":"first_enter_email_span","class":"first_enter_email_span_style"}', "Enter Valid Email ID");
    $first_email_input_cont = insertElement($first_email_parent_cont,"div",'{"id":"first_email_input_cont","class":"first_email_input_cont_style"}', "");
    $first_email_input = insertElement($first_email_input_cont,"input",'{"id":"first_email_input","class":"first_email_input_style"}', "");

    $second_email_parent_cont = insertElement($add_email_parent_cont,"div",'{"id":"second_email_parent_cont","class":"second_email_parent_cont_style"}', "");
    $second_enter_email_cont = insertElement($second_email_parent_cont,"div",'{"id":"second_enter_email_cont","class":"second_enter_email_cont_style"}', "");
    $second_enter_email_span = insertElement($second_enter_email_cont,"span",'{"id":"second_enter_email_span","class":"second_enter_email_span_style"}', "Re-enter Email");
    $second_email_input_cont = insertElement($second_email_parent_cont,"div",'{"id":"second_email_input_cont","class":"second_email_input_cont_style"}', "");
    $second_email_input = insertElement($second_email_input_cont,"input",'{"id":"second_email_input","class":"second_email_input_style"}', "");

    $validate_btn_cont = insertElement($add_email_parent_cont,"div",'{"id":"validate_btn_cont","class":"validate_btn_cont_style"}', "");
    $validate_btn = insertElement($validate_btn_cont,"button",'{"id":"validate_btn", "class":"validate_btn_style"}', "Validate");

    $validate_err_cont = insertElement($add_email_parent_cont,"div",'{"id":"validate_err_cont","class":"validate_err_cont_style"}', "");
    $validate_err = insertElement($validate_err_cont,"span",'{"id":"validate_err", "class":"validate_err_style"}', "");

    $validate_cls_btn_cont = insertElement($add_email_parent_cont,"div",'{"id":"validate_cls_btn_cont","class":"validate_cls_btn_cont_style"}', "");
    $validate_cls_btn = insertElement($validate_cls_btn_cont,"button",'{"id":"validate_cls_btn", "class":"validate_cls_btn_style"}', "close");
}



?>
