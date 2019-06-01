<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
//include 'db_connection.php';
include '../../eecee/lib/php-lib/eecee_constants.php';

function generate_view($email_lbl, $old_password_lbl, $password_lbl){
    
    echo"<! DOCTYPE html>
    <html>
    <head>
    <title>reset your password</title>
    <link rel='stylesheet' type='text/css' href='../../eecee/themes/change_pass.css?'.time()'>
    </head>
    <body>
    <form action='change_pass.php' method='POST'>
        <div class='container'>
            <h3 class='main_heading'>Change your password</h3>

            <label class='label_styles'><b>Your Email ID</b></label><br>
            <input class='input_style' type='text' placeholder='Enter Email' name='user_email' >
            <span class='error_styles'>$email_lbl</span><br>

            <label class='label_styles'><b>Old Password</b></label><br>
            <input class='input_style' type='password' placeholder='Enter Password' name='old_password'><br><br>
            <span class='error_styles'>$old_password_lbl</span><br>

            <label><b>Password *</b></label>
            <input type='password' placeholder='Enter Password' name='password' class='password_input_style'><br>
        
            <label><b>Repeat Password *</b></label>
            <input type='password' placeholder='Repeat Password' name='confirm_password' class='password_input_style'>
            <span class='error_styles'>$password_lbl</span>

            <div class='button_div'>
                <button type='submit' class='update_button'>Update</button>
            </div>
        </div>
    </form>
    </body>
    </html>";
      
}


if ($_SERVER["REQUEST_METHOD"] == "GET") {
    
    generate_view("","","");
}

?>

<?php 

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $email_id = $_POST["user_email"];
    $old_password = $_POST["old_password"];
    //echo $password ;
           
        if($password == "" | $confirm_password == ""){
            generate_view("","","password can't be blank");
        }
        
        elseif ($password != $confirm_password) {
            generate_view("","","password does not match");  
        } 
        else{
            
                $sql_check_reg_user = "SELECT * FROM reg_user WHERE password = ? AND email = ?";
                $sql_check_reg_user_temp = $conn->prepare($sql_check_reg_user);
                $sql_check_reg_user_temp->bind_param("ss",$old_password, $email_id);
                $sql_check_reg_user_temp->execute();
                $sql_check_reg_user_temp_result = $sql_check_reg_user_temp->get_result();
                $sql_row = $sql_check_reg_user_temp_result->fetch_assoc();
                $pass = $sql_row["password"];
                $email = $sql_row["email"];
                //echo "the password is::".$pass;
               // echo "the email is::".$email;
                $count = mysqli_num_rows($sql_check_reg_user_temp_result);
               // echo "the count is::".$count;

                if($count == 1){
                      
                        $status = 1;
                        //echo "the password is:: ".$password;
                        //echo "the status is:: ".$status;
                        $sql_reg_user_update = "UPDATE reg_user SET password=?, status=? WHERE email=?";
                        $sql_reg_user_update_stmt=$conn->prepare($sql_reg_user_update);
                        $sql_reg_user_update_stmt->bind_param("sis", $password, $status, $email);
                        $sql_reg_user_update_stmt->execute();

                        echo "password successfully updated!";
                        header("Location: reset_pass_redirect.php");
                        
                    }
                    
                    else{
                        generate_view("There email and password doesn't match in the database","","");
                        echo "password could not be updated!";
                    }
            }
         
            
              
        }
    

?>