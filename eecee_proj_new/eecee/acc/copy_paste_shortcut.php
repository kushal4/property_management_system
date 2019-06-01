<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';

include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'sec.php';

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
    
    $moved_node_id="";
    $node_parent_id="";
    
    foreach ($json_decoded as $key => $value) {      
        if ($key=="moved_node_id"){
            $moved_node_id = $value;
        }
        if ($key=="node_parent_id"){
            $node_parent_id = $value;
        }
    }
    //echo $node_id;

    $arr_of_obj = array();

    $decoded_moved_node_id = sec_get_map_val ("role_sig_map", $moved_node_id);
    $decoded_node_parent_id = sec_get_map_val ("role_sig_map", $node_parent_id);

    $actlCreateRoleCatCurlURL = $actl_urls->actlCreateRoleCatCurlURL;
    //echo "the decoded moved node ID ia::".$decoded_moved_node_id."\n";
    //echo "the decoded parent node ID ia::".$decoded_node_parent_id."\n";
    //$logfile->logfile_writeline("the encoded node ID is".$sig);
    //$logfile->logfile_writeline("the decoded node ID is".$decoded_sig);

    /*
    $curr_map = sec_get_map("role_sig_map");
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map MAP: Begin");
            foreach($curr_map as $key => $value)
                {
                    $logfile->logfile_writeline($key." : ".$value);
                }
    $logfile->logfile_writeline(__FILE__."********************************* Dumping role_sig_map MAP: End");
    */
    //echo "the node SIG is:: ".$node_id_mapped."\n";

    
    $role_sig = $SENSESSION->get_val("role_sig");
    $prop_id = $SENSESSION->get_val("prop_id");
    $method = "copy_role_shortcut";
     
    $data=array("param"=>array("method"=>$method, "key"=>$projectID, "client_id"=>$prop_id, "client_role"=>$role_sig, "role_id_sig"=>$decoded_moved_node_id, "cat_id_sig"=>$decoded_node_parent_id));
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $logfile->logfile_writeline("============================================================================================");
    $logfile->logfile_writeline("To CURL".$data_str);

    //print_r($data_str);

    //echo "\n";

    
    
    $actlRetObj = CurlSendPostJson($actlCreateRoleCatCurlURL, $data_str); //what we get from the ACTL
    //$logfile->logfile_writeline("From CURL".$actlRetObj);
    //print_r($actlRetObj);

    $logfile->logfile_writeline("from curl".$actlRetObj); 
    $logfile->logfile_writeline("============================================================================================");

    $perm_data_json_decode = json_decode($actlRetObj, true);
    $data_value = $perm_data_json_decode["d"];
    

    $data_json_decode = json_decode($data_value, true);
    $status_value = $data_json_decode["status"];
    $message = $data_json_decode["message"];
    $logfile->logfile_writeline("status_value=".$status_value); 
   

    if($status_value == 0){
        $raw_json["ret_code"] = 0;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }else{
        $raw_json["ret_code"] = 1;
        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }

   
    
}
$logfile->logfile_close();
?>