<?php
   
$server_name = "localhost";
$user_name = "root";
$password = "1234";
$dbname ="learning";
error_reporting( E_ALL );
$conn=new \mysqli($server_name,$user_name,$password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";

$e_id = $pass = "";

   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
       $myusername = mysqli_real_escape_string($conn,$_POST["email"]);
       $mypassword = mysqli_real_escape_string($conn,$_POST["psw"]); 
      
      $sql = "SELECT * FROM temp_reg_user WHERE email = '$myusername' and password = '$mypassword'";
      $result = mysqli_query($conn,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      $active = $row['active'];
      
      $count = mysqli_num_rows($result);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($count == 1) {
         
         session_start("myusername");
         $_SESSION["login_user"] = $myusername;
        
            //echo "welcome";
         header("Location: welcome.php");
        
      }
      else {
         //$error = "Your Login Name or Password is invalid";
         //echo nl2br("\nYour Login Name or Password is invalid");
         header("Location: please_signup.php");
      }
      
      
   }
?>