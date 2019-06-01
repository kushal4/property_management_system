<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
include '../prop_topo.php';
$log_path = $eecee_log_path."prop_def.log";
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

if (is_ajax()) {
    
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $del_feat_id="";
    
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="del_feat_id"){
            $del_feat_id = $value;
        }
    }
    $del_feat_id_mapped = sec_get_map_val ("feat_list_tbl_sig_map", $del_feat_id);
    $logfile->logfile_writeline("the encoded del_feat_id is".$del_feat_id);
    $logfile->logfile_writeline("the decoded del_feat_id is".$del_feat_id_mapped);

    $prop_id = $SENSESSION->get_val("prop_id");
    $unit_type_id = $SENSESSION->get_val("unit_type_id");

    $logfile->logfile_writeline("the prop_id is".$prop_id);
    $logfile->logfile_writeline("the unit_type_id is".$unit_type_id);

    
    $sql_delete = $conn->prepare("DELETE from unit_type_fea where prop_id = ? and unit_type_id = ? and unit_fea_id = ?");
    $sql_delete->bind_param("iii", $prop_id, $unit_type_id, $del_feat_id_mapped);
    $sql_delete->execute();
    $sql_delete_res = $sql_delete->get_result();


    $sql_delete1 = $conn->prepare("DELETE from unit_fea_attrib where unit_type_id = ? and unit_fea_id = ?");
    $sql_delete1->bind_param("ii", $unit_type_id, $del_feat_id_mapped);
    $sql_delete1->execute();
    $sql_delete1_res = $sql_delete1->get_result();

    
    $raw_json["ret_code"] = 0;
    $raw_json_encode=json_encode($raw_json);
    echo $raw_json_encode;
    
    /*
    if($status_value == 0){
        $raw_json["ret_code"] = 0;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }else{
        $raw_json["ret_code"] = 1;
        $raw_json["roles"] = $role_arr;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }
    */   
}
$logfile->logfile_close();
?>