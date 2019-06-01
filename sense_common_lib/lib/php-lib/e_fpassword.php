<?php 
require_once ('PHPMailer.php');
require_once ('Exception.php');
require_once ('SMTP.php');
require_once ('POP3.php');
//require_once ('curl_url_include.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../../../eecee_proj_new/eecee/lib/php-lib/eecee_constants.php';
use PHPMailer\PHPMailer\PHPMailer;

function generate_view(){
    echo "<! DOCTYPE html>
<html>
<head>
<title>forgot password</title>

<link rel='stylesheet' type='text/css' href='../../../eecee_proj_new/eecee/themes/fpass.css?".time()."'>".

"</head>
        
<body>
        
<form action='e_fpassword.php' method='POST'>
<div class='container'>
        
<h2>Can't log in? Forgot your password</h2>
    	<p>Enter your email address below and we will send you password reset</p>
    	<br>
    	<br>
    	<label><b>Enter your email ID</b></label>
    	<input type='text' placeholder='Enter Email' class='email_textbox' name='email' required>
    	<br>
    	<br>
    	<div>
    			 <button type='submit' class='signupbtn'>Submit</button>
    	</div>
   </div>
</form>
</body>
</html>";
  
}

function generate_view2($view){
    
    if ($view=="success") {
        echo"<! DOCTYPE html>
<html>
            
   <head>
      <title>forgot password email is sent</title>
    <link rel='stylesheet' type='text/css' href='../../themes/fpass.css'>
   </head>
            
   <body>
    <div class='container'>
      <span class='forgot_password_styles' >An email has been sent to your account. Please follow the instructions there to complete resetting your password.<br><br></span>

     </div>
   </body>
            
</html>";
    } else if ($view=="email_mismatch") {
        echo"<! DOCTYPE html>
<html>
            
   <head>
      <title>email not found in the database</title>
    <link rel='stylesheet' type='text/css' href='../../themes/fpass.css'>
   </head>
            
   <body class='container'>
    <div>
      <span class='forgot_password_styles'>your email ID was not found in the database<br><br></span>
     </div>
   </body>
            
</html>";
        
    }
}

function RandomStringGenerator($n) 
{ 
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

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    generate_view("");
    
}

?>
<?php 

if(isset($_POST) & !empty($_POST)){
    
    
    // create connection
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    $n = 8;
    $pass = RandomStringGenerator($n);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // echo "Connected successfully!";
    
    $email=$_POST["email"];
   
    $sql = "SELECT * FROM reg_user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $res = $stmt->get_result();
    $count = $res->num_rows;
    if($count == 1){ 
        $row = $res->fetch_assoc();
        $userid= $row['user_id'];
        $status = 0;
        //$last_id = $conn->insert_id;
        
        $sql_reg_user_update = "UPDATE reg_user SET password=?, status=? WHERE email=?";
        $sql_reg_user_update_stmt=$conn->prepare($sql_reg_user_update);
        $sql_reg_user_update_stmt->bind_param("sis", $pass, $status, $email);
        $sql_reg_user_update_stmt->execute();
        
        // send mail here
        
        $body = "Dear user,<br><br>
You requested help with your Tarantoo account password. Please click the link below to set your new password <br><br>
Your temporary password is: $pass<br>
Please login using the above password. 
<span class='f_psw'><a href='http://192.168.0.25/opt/eecee/eecee_login.php'>Click here to Login IN</a></span><br><br>
Please ignore this email if it was not you who requested help with your password. Your current password will remain unchanged. <br><br><br>
Regards,<br>
Tarantoo Admin";
        // $body="click on the below link to activate your account <br/>". "http://localhost/iot_app/iot_home/home.php?";
        $subject = "Tarantoo- Reset password request";
        $reciever = $email;
        $sender = "sensennium.kushal4@gmail.com";
        
        $smtp_name = "smtp.gmail.com";
        $smtp_port = 587;
        
        $mail = new PHPMailer();
        
        $mail->isSMTP();
        
        // $mail->SMTPDebug = 2;//to enable debug in mailsender
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
        } else {//email found in database
            
             //echo nl2br("An email has been sent to your account. Please follow the instructions there to complete resetting your password.");
            //header("Location: welcome.php");
            //generate_view("success");
             generate_view2("success");
        }
        
        
    }else{//email not found in database
        generate_view2("email_mismatch");
        //echo nl2br("blah");
    }
}

?>
