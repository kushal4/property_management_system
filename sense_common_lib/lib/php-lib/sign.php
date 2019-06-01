<?php
require_once ('PHPMailer.php');
require_once ('Exception.php');
require_once ('SMTP.php');
require_once ('POP3.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
// echo "came before mysql connnection object";


function generate_view($fname_lbl,$lname_lbl,$email_lbl,$password_lbl,$f_name,$l_name,$e_id,$notification_lbl,$sign_success_url){
    
    echo "<! DOCTYPE html>
    <html>
    <head>
    <link rel='stylesheet' type='text/css' href='../styles/sign.css'>
    </head>
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
    <input type='text' placeholder='Enter Email' name='email' value='$e_id'>
    <span class='error_styles'>$email_lbl</span><br>
    
    <label><b>Password *</b></label>
    <input type='password' placeholder='Enter Password' name='psw'>
    
    <label><b>Repeat Password *</b></label>
    <input type='password' placeholder='Repeat Password' name='rep_psw'>
    <span class='error_styles'>$password_lbl</span>
       
    <div class='clearfix'>
    
    <button type='submit' class='signupbtn'>Sign Up</button>
    </div>
    <div class='error_styles'>$notification_lbl</div>
    </div>
    </form>
    
    
    </body>
    </html>";
    
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    generate_view("","","","","","","","",$sign_success_url);
    
}

/* for get request */

?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    // create connection
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
    }
     //echo "Connected successfully!";
    
    //$f_nameErr = $l_nameErr = $e_idErr = $passErr = $r_passErr = "";
    //$f_name = $l_name = $e_id = $pass = $r_pass = "";
    
     //echo "i am in post"; 
    $f_name = $_POST["fname"];
    $l_name = $_POST["lname"];
    $e_id = $_POST["email"];
    $pass = $_POST["psw"];
    $r_pass = $_POST["rep_psw"];
    
    
    if ($f_name == "") {
        generate_view("First name can't be blank","","","","","","","",$sign_success_url);
    } else if ($l_name == ""){
        generate_view("","Last name can't be blank","","","","","","",$sign_success_url);
    }
    else if ($e_id == ""){
        generate_view("","","Email can't be blank","","","","","",$sign_success_url);
    }
    else if ($pass == "" | $r_pass == ""){
        generate_view("","","","Password Can't be blank","",$sign_success_url);
    }

    $chk_usr_exst_str = "SELECT * FROM reg_user WHERE email= ?";
  

  $sql_usr_qury=$conn->prepare($chk_usr_exst_str);
  $sql_usr_qury->bind_param("s",$e_id);
  $sql_usr_qury->execute();
  $sql_usr_qury_res = $sql_usr_qury->get_result();


    //$slquery=$conn->prepare("select * from temp_reg_user where token= ?");
    //$slquery->bind_param("s",$e_id);
   // $slquery->execute();
   // $slquery_res = $slquery->get_result();
    
    $checking = false;
    $e_id = test_input($_POST["email"]);
    
    // check if e-mail address is well-formed
    
    if (empty($_POST["email"]) || ! filter_var($e_id, FILTER_VALIDATE_EMAIL)) {
        //$e_idErr = "Email is required or email is blank";
       // generate_view($e_idErr,"","","","","","");
    
    }    // check if password matches
    elseif ($pass != $r_pass) {
        //echo nl2br("\n password does not match");
        generate_view("","","","password does not match","","","","",$sign_success_url);
    
        
    }    // check if e-mail address is already there
    elseif (mysqli_num_rows($sql_usr_qury_res) > 0) {
        //$row = mysqli_fetch_assoc($selectresult);
        $row = $sql_usr_qury_res->fetch_assoc();
        if ($e_id == $row['email']) {
            
            generate_view("","","","","","","","A user already exists with this email id.Please login with that email id <br> <a href='login.php'>Log In Here </a>",$sign_success_url);
            //header("Location: fpassword.php");
        }
   
    
    } else {
           
 
            
            /*$sql_temp = "INSERT INTO temp_reg_user (first_name, last_name, email,password)
              VALUES ('$f_name', '$l_name', '$e_id','$pass')";*/
            $sql_insrt="INSERT INTO temp_reg_user(first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
            $sql_temp = $conn->prepare($sql_insrt);
            if($sql_temp){
                $sql_temp->bind_param("ssss", $f_name, $l_name, $e_id, $pass);
                $sql_temp->execute();
                //$_SESSION["first_name"] = $f_name;
                //$_SESSION["last_name"] = $f_name;
                
             //   if ($sql_temp === TRUE) {
                    
                    $last_id = $conn->insert_id;
                    echo "last insert id of the row is".$last_id;
                    $md5_input = $last_id . $e_id;
                    $md5_str = md5($md5_input);
                    
                    $sql_update="UPDATE temp_reg_user SET token=? where email=?";
                    $sql_up_stmt=$conn->prepare($sql_update);
                    $sql_up_stmt->bind_param("ss", $md5_str, $e_id);
                    $sql_up_stmt->execute();
                    echo $last_id;
                    echo $md5_str;
                    if ($sql_up_stmt) {
                        
                        // echo 'all success';
                        // Mailsender->send_mail(body,subject,)
                        /*
                         * try to send mail here '
                         * if mail sending successful show an success message
                         *
                         */
                        // send mail here
                        
                        $body = "Dear New Tarantoo User,<br>
Thank you for registering in Tarantoo.<br><br>

Your registration is pending email verification.<br><br>

Your registration ID is: $last_id <br><br>

Please click on the following link and it will take you to a confirmation page.<br><br>
" . "$tarantoo_client_hostname/opt/$confirm_email?id=$last_id&token=$md5_str<br>" . "--Tarantoo Administrator.<br><br>
==============<br><br>
This is an auto-generated email and is unmonitored. Do not reply to this email.<br>
Security advisory: If you have not requested for registration and this email is unexpected to you, please contact your Tarantoo system administrator.";
                        // $body="click on the below link to activate your account <br/>". "http://localhost/iot_app/iot_home/home.php?";
                        $subject = "Tarantoo    New User Request Email Verification";
                        $reciever = $e_id;
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
                          header("Location:" .$sign_success_url);
                        }
                    }
                    // echo "New record created successfully";
                //} //else {
                   // echo "Error: " . $sql_insrt . "<br>" . $conn->error;
               // }
            }else{
                echo "the query is not run";
            }
            
        
    }
    //echo $e_idErr;
}

    
    
   
    
    
    /*
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
     if (empty($_POST["email"])) {
     $e_idErr = "Email is required";
     }
     else {
     $e_id = test_input($_POST["email"]);
     
     // check if e-mail address is well-formed
     if (!filter_var($e_id, FILTER_VALIDATE_EMAIL)) {
     $e_idErr = "Invalid email format";
     }
     }
     }
    
    
    
}




?>
