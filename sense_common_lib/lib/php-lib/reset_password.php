<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
//include 'db_connection.php';
include '../../eecee/lib/php-lib/eecee_constants.php';

function generate_view($pass_lbl,$fetched_id,$fetched_token){
    
    echo"<! DOCTYPE html>
<html>
<head>
<title>reset your password</title>
<link rel='stylesheet' type='text/css' href='../../themes/reset_password.css'>
</head>
<body>
<form action='reset_password.php' method='POST'>
<div class='container'>
<h3 class='main_heading'>Reset your password</h3>
<label class='label_styles'><b>Password</b></label><br>
<input class='input_style' type='password' placeholder='Enter Password' name='password'><br><br>
<label class='label_styles'><b>Confirm Password</b></label><br>
<input class='input_style' type='password' placeholder='Enter confirm Password' name='confirm_password'><br>
<input type='hidden' value='$fetched_id'  name='fetch_id' id='fetch_id'/>
<input type='hidden' value='$fetched_token'  name='fetch_token' id='fetch_token'/>
<span class='error_styles'>$pass_lbl</span><br>
<div class='button_div'>
<button type='submit' class='update_button'>Update</button>
</div>
</div>
</form>
</body>
</html>";
      
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id=$_REQUEST["id"];
    echo "the id is". $id;
    $token=$_GET["token"];
    echo "the token is". $token;
    //generate_view("");
    generate_view("",$id,$token);
    
}

?>

<?php 
/*
if ($_SERVER["REQUEST_METHOD"] == "GET") {
   echo 'second get';
   switch($_GET["view"]){
   }
   /*
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    */
   // $id=$_GET["id"];
   // echo "the id is". $id;
   // $token=$_GET["token"];
   // echo "the token is". $token;
    /*
    $sql= "SELECT * from reset_password where token='$token'";
    $res = $conn->query($sql);
    $count = $res->num_rows;
    
    if($count == 1){
        $row = $res->fetch_assoc();
        $userid= $row['user_id'];
        if($id == $userid){
            echo "ID matched";
            
        }
    }
    */
//}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    //echo "inside post";
    //$token = $_POST["fetch_token"];
    //echo "token is".$token;
    //$id = $_POST["fetch_id"];
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    //var_dump($_POST);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    
    $fetch_id = $_POST["fetch_id"];
    $fetch_token = $_POST["fetch_token"];
    
    
    
    //$sql= "SELECT * from reset_password where token='$token'";
    //$res = $conn->query($sql);
    //$count = $res->num_rows;
        
        if($password == "" | $confirm_password == ""){
            generate_view("password can't be blank",$fetch_id,$fetch_token);
        }
        
        elseif ($password != $confirm_password) {
            //echo nl2br("\n password does not match");
            generate_view("password does not match",$fetch_id,$fetch_token);  
        } 
        else{
            //echo "fetched id is".$fetch_id;
            //$sql_reset = "SELECT * from reset_password where user_id='$fetch_id'";
            echo $fetch_id;
            $sql_reset = "SELECT * FROM reset_password WHERE user_id = ?";
            $sql_reset_stmt = $conn->prepare($sql_reset);
            $sql_reset_stmt->bind_param("s",$fetch_id);
            $sql_reset_stmt->execute();
            $res_sql_reset = $sql_reset_stmt->get_result();
            //echo "update successful";
            //$res_sql_reset = $conn->query($sql_reset);
            if($res_sql_reset){
                
                
                $count = $res_sql_reset->num_rows;
                //echo "number of rows fetched".$count;
                if($count == 1){
                    
                    //echo "update successful";
                    $row = $res_sql_reset->fetch_assoc();
                    $token_reset = $row['token'];
                    $id_reset = $row['user_id'];
                    //echo "the token in the reset password table is:".$token_reset;
                    if($fetch_token == $token_reset & $fetch_id == $id_reset){
                        
                        $email_reset = $row['email'];
                        
                        
                        $sql_reg_user_update = "UPDATE reg_user SET password=? WHERE email=?";
                        $sql_reg_user_update_stmt=$conn->prepare($sql_reg_user_update);
                        $sql_reg_user_update_stmt->bind_param("ss", $password, $email_reset);
                        $sql_reg_user_update_stmt->execute();
                        //echo "the password is".$password;
                        
                        
                        $sql_delete_reset = "DELETE from reset_password where user_id= ?";
                        $sql_delete_reset_stmt = $conn->prepare($sql_delete_reset);
                        $sql_delete_reset_stmt->bind_param("s", $fetch_id);
                        $sql_delete_reset_stmt->execute();
                        echo "password updated successfully!!";
                        header("Location: reset_pass_redirect.php");
                        
                    }
                    
                    else{
                        
                        echo "password could not be updated!";
                    }
                    
                }
            }
         
            
            
            
        }
    
}
?>