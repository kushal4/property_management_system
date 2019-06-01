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

//echo "getting inside delete role category";
if (is_ajax()) {
   
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    
    $node_name="";
    $node_id="";
    $description="";
    $type="";
    
    foreach ($json_decoded as $key => $value) {      
        if ($key=="node_name"){
            $node_name = $value;
        }

        if ($key=="node_id"){
            $node_id = $value;
        }

        if ($key=="description"){
            $description = $value;
        }

        if ($key=="type"){
            $type = $value;
        }
    }
    
    //echo "the node_id is:: ".$node_id."\n";
    $decoded_node_id = sec_get_map_val ("role_sig_map", $node_id);
    //echo "the decoded node_id is:: ".$decoded_node_id."\n";

    $actlCreateRoleCatCurlURL = $actl_urls->actlCreateRoleCatCurlURL;

    $role_sig = $SENSESSION->get_val("role_sig");
    $prop_id = $SENSESSION->get_val("prop_id");
    if($type == "role"){
        $method = "update_role";
    }else if($type == "rolecat"){
        $method = "update_role_cat";
    }
    
    $enable = "true";

    //$requestd_sig = "req_sig";
    $updated_description = $description;

     
    $data=array("param"=>array("method"=>$method, "key"=>$projectID, "client_id"=>$prop_id, "client_role"=>$role_sig, "name"=>$node_name, "description"=>$updated_description, "enable"=>$enable, "signature"=>$decoded_node_id));
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $logfile->logfile_writeline("************************************************************");
    $logfile->logfile_writeline("To CURL".$data_str);

    //print_r($data_str);

    //echo "\n";
    
    $actlRetObj = CurlSendPostJson($actlCreateRoleCatCurlURL, $data_str, $logfile); //what we get from the ACTL
    //$logfile->logfile_writeline("From CURL".$actlRetObj);

    //print_r($actlRetObj);

    $logfile->logfile_writeline("from curl".$actlRetObj); 
    $logfile->logfile_writeline("************************************************************");

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