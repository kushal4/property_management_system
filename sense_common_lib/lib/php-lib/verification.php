<?php 
///$server_name = "localhost";
//$user_name = "root";
//$password = "1234";
//$dbname = "learning";

error_reporting(E_ALL);

function generate_view($email){
    echo "<! DOCTYPE html>
    <html>
       <head>
          <title>Verification </title>
          <link rel='stylesheet' type='text/css' href='../../../eecee_proj_new/eecee/themes/sign.css?'.time()'>
       </head>
       <body>
          <h1 class='verification_heading_style'>A verification mail has been sent to your email ID $email<br>User needs to follow instructions in that email for further actions.</h1> 
       </body>
    </html>";
}

?>
<?php 
if($_SERVER["REQUEST_METHOD"] == "GET"){
   
    $email=$_GET["email"];
    //echo $email;

    generate_view($email);
}
?>
