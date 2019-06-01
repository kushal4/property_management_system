<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$logfile->logfile_writeline("getting inside move node PHP");

if (is_ajax()) {
    
    $myArray = [];
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $old_parent="";
    $parent="";
    $mov_typ_type="";
    $mov_node_id="";

    foreach ($json_decoded as $key => $value) {
        //echo $key. "=>>>>>" .$value;
        //$log_str.="\n $key =>>>>> $value \n";
        
        if ($key=="old_parent"){
            $old_parent = $value;
        }
        if ($key=="parent"){
            $parent = $value;
        }
        if ($key=="mov_typ_type"){
            $mov_typ_type = $value;
        }
        if ($key=="mov_node_id"){
            $mov_node_id = $value;
        }
    }
    //echo $node_id;
    $old_parent_mapped = sec_get_map_val ("prop_topo_map", $old_parent);
    $logfile->logfile_writeline("the encoded old parent ID is".$old_parent);
    $logfile->logfile_writeline("the decoded old parent ID is".$old_parent_mapped);

    $parent_mapped = sec_get_map_val ("prop_topo_map", $parent);
    $logfile->logfile_writeline("the encoded new parent ID is".$parent);
    $logfile->logfile_writeline("the decoded new parent ID is".$parent_mapped);

    $mov_node_id_mapped = sec_get_map_val ("prop_topo_map", $mov_node_id);
    $logfile->logfile_writeline("the encoded moving node ID is".$mov_node_id);
    $logfile->logfile_writeline("the decoded moving node ID is".$mov_node_id_mapped);

    $session_val= is_session_valid();

    if($session_val==0){

        $prop_id = $SENSESSION->get_val("prop_id");
        
        $sql = "SELECT * FROM prop_topo WHERE prop_id = ? AND parent_id = ? ";
        $sql_prep_stmt = $conn->prepare($sql);
        $sql_prep_stmt->bind_param("ii",$prop_id, $parent_mapped);
        $sql_prep_stmt->execute();
        $sql_exec_result = $sql_prep_stmt->get_result();
        $row = $sql_exec_result->fetch_assoc();

        $new_parent_type = $row["unit"];

        $logfile->logfile_writeline("the type code of the new parent is".$new_parent_type); /*1: unit 0: group */
        
        
        if ($mov_typ_type == "group" && $new_parent_type == 1){
            $raw_json["ret_code"] = 2;
            $raw_json["ret_msg"] = "group can't go under unit";
            $raw_json_encode=json_encode($raw_json);
            echo $raw_json_encode;
            $logfile->logfile_writeline("BREACH!!! Trying to put group under unit");
        }
        /*
        if ($mov_typ_type == "unit" && $new_parent_type == 0){
            $raw_json["ret_code"] = 3;
            $raw_json["ret_msg"] = "unit can't go under unit";
            $raw_json_encode=json_encode($raw_json);
            echo $raw_json_encode;
            $logfile->logfile_writeline("BREACH!!! Trying to put group under unit");
        }*/

        else{
            $sql_prop_topo_update = "UPDATE prop_topo SET parent_id=? WHERE id=?";
            $sql_prop_topo_update_stmt=$conn->prepare($sql_prop_topo_update);
            $sql_prop_topo_update_stmt->bind_param("ii", $parent_mapped, $mov_node_id_mapped);
            $sql_prop_topo_update_stmt->execute();
        }

    }


    else{      
    }
    $conn->close();
}
$logfile->logfile_close();
?>