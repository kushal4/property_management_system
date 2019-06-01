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
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
//include 'curl_url_include.php';
//$log_path = "Logs/eecee.log";
include '../lib/php-lib/sec.php';
include 'prop_topo.php';
include '../lib/php-lib/session_exp.php';
include '../lib/php-lib/reg_func.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");
?>

<?php 
if($_SERVER["REQUEST_METHOD"] == "GET"){
   
    $vcode=$_GET["Vcode"];
    $latest_1 = 1;
    //echo $vcode;
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);

    $sql = "SELECT * FROM usr_invite_tbl WHERE vcode = ? and latest = ?";
    
    $stmt = $conn->prepare($sql);
    if($stmt){
        //echo "prepare success"."</br>";
        //echo $vcode."</br>";
        $bind = $stmt->bind_param("si",$vcode, $latest_1);
        if($bind){
            //echo "bind is successful"."</br>";
            $exe = $stmt->execute();
            if($exe){
                //echo "execution is successful"."</br>";
                $result = $stmt->get_result();
                if($result){
                    //echo "result successful"."</br>";

                    $invt_num_row = mysqli_num_rows($result);
                    //echo "the num row is::".$num_row."</br>";
                    $row = $result->fetch_assoc();

                    $node_id = $row['node_id'];
                    $status = $row['status'];
                    $pin = $row['pin'];
                    $email = $row['email_id'];
                    $type = $row['type'];
                    $mod_by = $row['mod_by'];
                    $user_id = $row['user_id'];
                    
                    //echo "the node ID is:: ".$node_id."</br>";
                }else{
                    //echo "result failed"."</br>";
                }
            }else{
                //echo "execution failed"."</br>";
            }
        }else{
            //echo "bind failed"."</br>";
        }
    }else{
        //echo "prepare failed"."</br>";
    }

    if($status == 4){
        echo "<! DOCTYPE html>
        <html>
            <head>
                <title>dnautu </title>
                <link rel='stylesheet' type='text/css' href='themes/dnautu.css?".time()."'>".
            "</head>
            <body>
                <div class='span_cont_style'>
                    <span class='span_style'>You have declined the invitation already</span>
                </div>
            </body>
        </html>";
    }
    else if($status == 2){

        echo "<! DOCTYPE html>
        <html>
            <head>
                <title>dnautu </title>
                <link rel='stylesheet' type='text/css' href='themes/dnautu.css?".time()."'>".
            "</head>
             <body>
                <div class='span_cont_style'>
                    <span class='span_style'>The unit was not added</span>
                </div>
             </body>
        </html>";

        /*
        $sql_del  =  "DELETE FROM usr_invite_tbl WHERE vcode = ?";
        $sql_del_temp = $conn->prepare($sql_del);
        $sql_del_temp->bind_param("s", $vcode);
        $sql_del_temp->execute();
        $sql_del_temp->close();
        */

        /*
        $status = 4;
        $sql_usr_invite_update = "UPDATE usr_invite_tbl SET status=? WHERE vcode=?";
        $usr_invite_stmt=$conn->prepare($sql_usr_invite_update);
        $usr_invite_stmt->bind_param("is", $status, $vcode);
        $usr_invite_stmt->execute();
        */


        $mod_code = "del";
        $status_3 = 4;

        $usr_invite = "INSERT INTO usr_invite_tbl(email_id, user_id, node_id, status, pin, vcode, type, mod_code, mod_by, latest) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $usr_invite_temp = $conn->prepare($usr_invite);
        $usr_invite_temp->bind_param("siiissisii",$email, $user_id, $node_id, $status_3, $pin, $vcode, $type, $mod_code, $mod_by, $latest_1);
        $usr_invite_temp->execute();


        $latest_0 = 0;
        $status_2 = 2;
        $mod_code_upd = "upd";
        $sql_user_prop = "UPDATE usr_invite_tbl SET latest=?  WHERE node_id=? and status = ? and mod_code = ?";
        $user_prop_stmt = $conn->prepare($sql_user_prop);
        $user_prop_stmt->bind_param("iiis",$latest_0, $node_id, $status_2, $mod_code_upd);
        $user_prop_stmt->execute();

    }else if($status == 3){
        echo "<! DOCTYPE html>
        <html>
            <head>
                <title>dnautu </title>
                <link rel='stylesheet' type='text/css' href='themes/dnautu.css?".time()."'>".
            "</head>
             <body>
                <div class='span_cont_style'>
                    <span class='span_style'>This invitation have been processed already</span>
                </div>
             </body>
        </html>";
    }

}