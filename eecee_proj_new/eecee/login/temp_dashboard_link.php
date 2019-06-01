<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$log_path = $eecee_log_path."eecee.log";
require_once $sense_common_php_lib_path.'Log.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$logfile->logfile_writeline("getting inside temp_dashboard_link PHP");

if (is_ajax()) {
    
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $node_id="";
    $node_name="";
    $prop_id="";
    

    foreach ($json_decoded as $key => $value) {
        
        if ($key=="node_id"){
            $node_id = $value;
        }
        if ($key=="node_name"){
            $node_name = $value;
        }
        if ($key=="prop_id"){
            $prop_id = $value;
        }
    }

    $node_id_decoded = sec_get_map_val ("role_sig_map", $node_id);
    $logfile->logfile_writeline("the encoded role sig is".$node_id);
    $logfile->logfile_writeline("the decoded role sig is".$node_id_decoded);

    $prop_id_decoded = sec_get_map_val ("prop_id_map", $prop_id);
    $logfile->logfile_writeline("the encoded prop ID is".$prop_id);
    $logfile->logfile_writeline("the decoded prop ID is".$prop_id_decoded);

    $sql_check_prop = "SELECT * FROM properties WHERE id = ?";
    $sql_check_prop_temp = $conn->prepare($sql_check_prop);
    $sql_check_prop_temp->bind_param("i",$prop_id_decoded);
    $sql_check_prop_temp->execute();
    $sql_check_prop_result = $sql_check_prop_temp->get_result();
    $sql_check_prop_row = $sql_check_prop_result->fetch_assoc();
    $prop_name = $sql_check_prop_row["setup_name"];
    $logfile->logfile_writeline("the property :: ".$prop_name);

    $SENSESSION->token("prop_name", $prop_name);
    $SENSESSION->token("Logged_in_role_name", $prop_id_decoded);
    $SENSESSION->token("logged_role_name", $node_name);
    $SENSESSION->token("role_sig", $node_id_decoded);

    $logfile->logfile_writeline("#################################the role sig is".$node_id_decoded);

    $raw_json["ret_code"] = 0;
    $raw_json_encode=json_encode($raw_json);
    echo $raw_json_encode;
}
?>