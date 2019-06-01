<?php 
//session_start();
//include '../../tarantoo/lib/php-lib/db_connection.php';
//echo "the val is ".$url;
///echo "the val is ".$server_name;

//echo "the server name is".$server_name;
//echo "<br>self path=<br>";
//echo $_SERVER["PHP_SELF"];
//echo "1 - login.php::css is ".$login_css."<br>";
//echo "the success location is".$login_success_url;
function generate_view($email_lbl,$password_lbl, $username, $l_css, $login_forgot_pass_url, $login_signup_url){
    //echo "login.php generate view::css is ".$l_css."<br>";

    echo $_SERVER["PHP_SELF"];
    echo "<br>";
    echo $_SERVER['REQUEST_URI'];
    echo "<! DOCTYPE html>
       <html>
        <head>
            <link rel='stylesheet' type='text/css' href='".$l_css."'>
        </head>

        <body>

        <form action='".$_SERVER['REQUEST_URI']."' method='POST'>
            <div class='container'>
            <h1>Sign In</h1>
            <p>Please fill in this form to log in</p>
               <hr>

    
            <label><b>Email</b></label>
            <input class='input_style' type='text' placeholder='Enter Email' name='user_email' value='$username'>
            <span class='error_styles'>$email_lbl</span><br>

            <label><b>Password</b></label>
            <input class='input_style' type='password' placeholder='Enter Password' name='user_psw'>
            <span class='error_styles'>$password_lbl</span><br>

            <label>
            <input type='checkbox' checked='checked' name='remember'> Remember me
            </label>
    
    

    <div class='clearfix'>
      
      <button type='submit' class='signupbtn'>Submit</button>

    </div>
    <div style='float:left'><span class='f_psw'> <a href='".$login_forgot_pass_url."'>Forgot password</a></span></div>
    <div style='float:right'><span class='f_psw'> <a href='".$login_signup_url."'>Sign Up</a></span></div>
    <br/>
  </div>
</form>

</body>
</html>";
} //end function

//echo "2 - login.php::css is ".$login_css."<br>";

if($_SERVER["REQUEST_METHOD"] == "GET") {
    //echo "3 - login.php::css is ".$login_css."<br>";
    generate_view("","","", $login_css, $login_forgot_pass_url, $login_signup_url);
    //echo "inside get";

}
?>

<?php 
$e_id = $pass = "";



if($_SERVER["REQUEST_METHOD"] == "POST") {
    //echo "4 - login.php::css is ".$login_css."<br>";
    //echo "inside post";
    // create connection
   echo "server name :-". $server_name;
   echo "user name :-". $user_name;
   echo "password :- ".$password;
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed..: " . $conn->connect_error);
    }
    // echo "Connected successfully!";
    // username and password sent from form
    
    $myusername = mysqli_real_escape_string($conn,$_POST["user_email"]);
    $mypassword = mysqli_real_escape_string($conn,$_POST["user_psw"]);
    
    if ($myusername == "") {
        generate_view("","Email Can't be blank", $myusername, $login_css, $login_forgot_pass_url, $login_signup_url);
    } else if ($mypassword == ""){
        generate_view("","Password Can't be blank", $myusername, $login_css, $login_forgot_pass_url, $login_signup_url);
    } else {
        
        $sql = "SELECT * FROM reg_user WHERE email = ? and password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss",$myusername, $mypassword);
        $stmt->execute();
        //Use Prepared statement for database access
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        //echo $row;
        $active = $row['active'];

        $status = $row["status"];
        echo "the status is::".$status."<br>";
       // echo $row['first_name'];
       // echo $row['last_name'];
       
        $count = $result->num_rows;
        
        // If result matched $myusername and $mypassword, table row must be 1 row
        
        if($count == 1) {
            
            
            //$_SESSION["login_user"] = $myusername;
            $SENSESSION->token("login_user", $myusername);
            //$_SESSION["first_name"] = $row['first_name'];
            $SENSESSION->token("first_name", $row['first_name']);
            //$_SESSION["last_name"] = $row['last_name'];
            $SENSESSION->token("last_name", $row['last_name']);
            //$_SESSION["user_id"] = $row["id"];
            $SENSESSION->token("user_id", $row['id']);
            //echo "hhhhhhhh".$row["id"];
            echo "the first name is :: ".$row['first_name'];
            //$_SESSION["logged_role_name"] = "";
            $SENSESSION->token("logged_role_name", "");

            $search_str="user_id=".$row["id"];
            $fname = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_FN", $row["id"], "user_id=".$row["id"]);
            $lname = get_data_tbl_fld_value_from_db_by_fld_sig ($conn, "USRPROF_LN", $row["id"], "user_id=".$row["id"]);
            $userName = $fname["val"]." ".$lname["val"];
            echo "the user name is :: ".$userName;
            //$_SESSION["user_name"] = $userName;
            $SENSESSION->token("user_name", $userName);

            //$_SESSION["acc_id"] = $_SESSION["user_id"] ;
            //$_SESSION["proxy_user"] = $_SESSION["login_user"];
            $SENSESSION->token("proxy_user", $SENSESSION->get_val("login_user"));
            //$_SESSION["proxy_first_name"] = $_SESSION["first_name"];
            $SENSESSION->token("proxy_first_name", $SENSESSION->get_val("first_name"));
            //$_SESSION["proxy_last_name"] = $_SESSION["last_name"];
            $SENSESSION->token("proxy_last_name", $SENSESSION->get_val("last_name"));
            
            //$_SESSION["admin"] = 0;
            $SENSESSION->token("admin", 0);

           // echo "oh my god";
           if($status == 0){ //first login after registration or password reset
            //$_SESSION["user_id"] = $row["id"];
            $SENSESSION->token("user_id", $row["id"]);
            //echo "the user id is".$_SESSION["user_id"]."<br>";
            header("Location: ".$login_success_url_change_pass); // change password cause status

           }else{
            //echo $_SESSION["user_id"];
            header("Location: ".$login_success_url);
           }
            

            
        }
        else {
            //$error = "Your Login Name or Password is invalid";
            //echo nl2br("\nYour Login Name or Password is invalid");
            generate_view("","Email or Password is invalid", $myusername, $login_css, $login_forgot_pass_url, $login_signup_url);
            
            
        }
    }
       
}
?>
