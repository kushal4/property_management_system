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
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
//include 'curl_url_include.php';
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
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
    //echo $vcode."</br>";

    echo "the vcode is:: (autu)".$vcode."</br>";

    echo "getting inside autu"."</br>";

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    $latest_1 = 1;

    $sess_user_id = $SENSESSION->get_val("acc_id");

    $sql = "SELECT * FROM usr_invite_tbl WHERE vcode = ? and latest = ?";
    
    $stmt = $conn->prepare($sql);
    if($stmt){
       // echo "prepare success"."</br>";
        //echo $vcode."</br>";
        $bind = $stmt->bind_param("si",$vcode, $latest_1);
        if($bind){
           // echo "bind is successful"."</br>";
            $exe = $stmt->execute();
            if($exe){
               // echo "execution is successful"."</br>";
                $result = $stmt->get_result();
                if($result){
                   // echo "result successful"."</br>";

                    $invt_num_row = mysqli_num_rows($result);
                   // echo "the num row is::".$invt_num_row."</br>";
                    $row = $result->fetch_assoc();

                    $node_id = $row['node_id'];
                    $invt_status = $row['status'];
                    $pin = $row['pin'];
                    $email = $row['email_id'];
                    $type = $row['type'];
                    //$mod_code = $row['mod_code'];
                    $mod_by = $row['mod_by'];
                    $latest = $row['mod_by'];
                    $user_id = $row['user_id'];
                   echo "the invite status is :: ".$invt_status."</br>";
                   echo "the  invt_num_row is :: ".$invt_num_row."</br>";
                   //echo "the user ID is :: ".$user_id."</br>";
                   //echo "the node ID is :: ".$node_id."</br>";
                }else{
                   // echo "result failed"."</br>";
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
    
    
    if($invt_num_row != "" && $invt_status == 2){

        echo "getting inside this<br>";
        $sql_unit = "SELECT * FROM prop_topo WHERE id = ?";
        $unit_stmt = $conn->prepare($sql_unit);
        $unit_stmt->bind_param("i",$node_id);
        $unit_stmt->execute();
        $unit_result = $unit_stmt->get_result();
        $unit_row = $unit_result->fetch_assoc();
        $node_name = $unit_row['node_name'];
        $prop_id = $unit_row['prop_id'];
    
    
        $sql_prop = "SELECT * FROM properties WHERE id = ?";
        $prop_stmt = $conn->prepare($sql_prop);
        $prop_stmt->bind_param("i",$prop_id);
        $prop_stmt->execute();
        $prop_result = $prop_stmt->get_result();
        $prop_row = $prop_result->fetch_assoc();
        $prop_name = $prop_row['setup_name'];
        //$user_id = $prop_row['created_by'];
    
    
        echo "the user ID is :: ".$user_id."<br>";
        echo "the prop ID is :: ".$prop_id."<br>";
        echo "the node ID is :: ".$node_id."<br>";
        $sql_user_prop = "SELECT * FROM contexts WHERE user_id = ? and prop_id = ? and unit_id = ?";
        $user_prop_stmt = $conn->prepare($sql_user_prop);
        $user_prop_stmt->bind_param("iii",$user_id, $prop_id, $node_id);
        $user_prop_stmt->execute();
        $user_prop_result = $user_prop_stmt->get_result();
        $num_row = mysqli_num_rows($user_prop_result);
        //echo "the num row is:: ".$num_row."</br>";
        //$mod_code = "add"
        if($num_row == 0){
            $active = 1;
            $suspended = 0;
            $user_type = 1;
            $prop_new_mod = "new";
            $sql_user_prop_update = "INSERT INTO contexts(user_id, user_type, prop_id, unit_id, active, suspended, mod_code, mod_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $sql_user_prop_update_stmt = $conn->prepare($sql_user_prop_update);
            if($sql_user_prop_update_stmt){
                echo "user_prop prepare success"."</br>";
                $user_prop_bind = $sql_user_prop_update_stmt->bind_param("iiiiiisi", $user_id, $user_type, $prop_id, $node_id, $active, $suspended, $prop_new_mod, $mod_by);
                if($user_prop_bind){
                    echo "user_prop bind success"."</br>";
                    $user_prop_exe = $sql_user_prop_update_stmt->execute();
                    echo "exec error= ".$sql_user_prop_update_stmt->error."</br>";
                    if($user_prop_exe){
                        echo "user_prop execution success"."</br>";
                        $last_id = $conn->insert_id;
                    }else{
                       echo "user_prop execution failed"."</br>";
                    }
                }else{
                   echo "user_prop bind failed"."</br>";
                }
            }else{
                echo "user_prop prepare failed"."</br>";
            }

            $resident_1 = 1;
            $role_sig = "OWNER";
            $sql_insert = $conn->prepare("INSERT INTO context_role(role_sig, ctx_id, resident) VALUES (?, ?, ?)");
            $sql_insert->bind_param("sii", $role_sig, $last_id, $resident_1);
            $sql_insert->execute();
            $sql_insert_res = $sql_insert->get_result();


            $status_3 = 3;

            /*
            $sql_usr_invite_update = "UPDATE usr_invite_tbl SET status=? WHERE vcode=?";
            $usr_invite_stmt=$conn->prepare($sql_usr_invite_update);
            $usr_invite_stmt->bind_param("is", $status, $vcode);
            $usr_invite_stmt->execute();
            */
            $mod_code = "add";

            $usr_invite = "INSERT INTO usr_invite_tbl(email_id, user_id, node_id, status, pin, vcode, type, mod_code, mod_by, latest) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $usr_invite_temp = $conn->prepare($usr_invite);
            if($usr_invite_temp){
                //echo "user_invite_tbl prepare success"."</br>";
                $user_invt_insert = $usr_invite_temp->bind_param("siiissisii",$email, $user_id, $node_id, $status_3, $pin, $vcode, $type, $mod_code, $mod_by, $latest_1);
                if($user_invt_insert){
                    //echo "user_invite_tbl bind success"."</br>";
                    $user_invt_insert_exe = $usr_invite_temp->execute();
                    //echo "user_invite_tbl exec error= ".$usr_invite_temp->error."</br>";
                    if($user_invt_insert_exe){
                        //echo "execution success"."</br>";
                    }else{
                        //echo "user_invite_tbl execution failed"."</br>";
                    }
                }else{
                    //echo "user_invite_tbl bind failed"."</br>";
                }
            }else{
                //echo "user_invite_tbl prepare failed"."</br>";

            }


        }
            
            
        /*
        $status_3 = 3;

        $mod_code = "add";

        $usr_invite = "INSERT INTO usr_invite_tbl(email_id, user_id, node_id, status, pin, vcode, type, mod_code, mod_by, latest) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $usr_invite_temp = $conn->prepare($usr_invite);
        if($usr_invite_temp){
            
            $user_invt_insert = $usr_invite_temp->bind_param("siiissisii",$email, $user_id, $node_id, $status_3, $pin, $vcode, $type, $mod_code, $mod_by, $latest_1);
            if($user_invt_insert){
                
                $user_invt_insert_exe = $usr_invite_temp->execute();
                
                if($user_invt_insert_exe){
                    
                }else{
                    
                }
            }else{
                
            }
        }else{
            
        }
        */
            
            


            $latest_0 = 0;
            $status_2 = 2;
            $mod_code_upd = "upd";
            $sql_user_prop = "UPDATE usr_invite_tbl SET latest=?  WHERE node_id=? and status = ? and mod_code = ?";
            $user_prop_stmt = $conn->prepare($sql_user_prop);
            $user_prop_stmt->bind_param("iiis",$latest_0, $node_id, $status_2, $mod_code_upd);
            $user_prop_stmt->execute();


            echo "<! DOCTYPE html>
            <html>
                <head>
                    <title>autu </title>
                    <link rel='stylesheet' type='text/css' href='themes/autu.css?".time()."'>".
                    "</head>
                <body>
                    <div class='span_cont_style'>
                        <span class='span_style'>You have been successfully added as owner of Unit $node_name of $prop_name </span>
                    </div>
                </body>
            </html>";

    }else if ($invt_num_row != "" && $invt_status == 4){
        echo "<! DOCTYPE html>
            <html>
                <head>
                <title>autu </title>
                <link rel='stylesheet' type='text/css' href='themes/autu.css?".time()."'>".
                "</head>
                <body>
                    <div class='span_cont_style'>
                        <span class='span_style'>You have declined the invitation already</span>
                    </div>
                </body>
            </html>";
    } else if($invt_num_row != "" && $invt_status == 3){
        echo "<! DOCTYPE html>
        <html>
            <head>
                <title>autu </title>
                <link rel='stylesheet' type='text/css' href='themes/autu.css?".time()."'>".
            "</head>
            <body>
                <div class='span_cont_style'>
                    <span class='span_style'>This invitation have been processed already</span>
                </div>
            </body>
        </html>";
     }
    

    
    
    

}