<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'sec.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside get_prop_topo.php";

$logfile->logfile_writeline("getting inside rename node");

if (is_ajax()) {
    
    $myArray = [];
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $node_id="";
    $node_name="";

    foreach ($json_decoded as $key => $value) {
        //echo $key. "=>>>>>" .$value;
        //$log_str.="\n $key =>>>>> $value \n";
        
        if ($key=="node_id"){
            $node_id = $value;
        }
        if ($key=="node_name"){
            $node_name = $value;
        }
    }
    //echo $node_id;
    $node_id_mapped = sec_get_map_val ("prop_topo_map", $node_id);
    $logfile->logfile_writeline("the encoded node ID is".$node_id);
    $logfile->logfile_writeline("the decoded node ID is".$node_id_mapped);
    $logfile->logfile_writeline("the new node name is".$node_name);
    echo "the node id is:: ".$node_id;
    echo "the decoded node ID".$node_id_mapped;
    $session_val= is_session_valid();

    if($session_val==0){
        $prop_id = $SENSESSION->get_val("prop_id");
        $sql_prop_topo_update = "UPDATE prop_topo SET node_name=? WHERE id=?";
        $sql_prop_topo_update_stmt=$conn->prepare($sql_prop_topo_update);
        $sql_prop_topo_update_stmt->bind_param("si", $node_name, $node_id_mapped);
        $sql_prop_topo_update_stmt->execute();

        //$raw_json["ret_code"] = 0;
        //$raw_json_encoce=json_encode($raw_json);
       // echo $raw_json_encoce;
    }


    else{      
    }
    $conn->close();
}
$logfile->logfile_close();
?>