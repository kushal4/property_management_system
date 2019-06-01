<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';

$log_path = $eecee_log_path."acc.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'sec.php';
require_once $sense_common_php_lib_path.'net.php';
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
    
    $node_id="";
    
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="node_id"){
            $node_id = $value;
        }
    }
    //echo $node_id;
    $node_id_mapped = sec_get_map_val ("role_sig_map", $node_id);
    $logfile->logfile_writeline("the encoded node ID is".$node_id);
    $logfile->logfile_writeline("the decoded node ID is".$node_id_mapped);

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
    //$deleteRoleCat = "delete_role_cat";
    //$name = $node_name;
    $role_cat_sig = $node_id_mapped;
    $method = "commission_role";

    $data=array("param"=>array("method"=>$method, "key"=>$projectID, "client_id"=>$prop_id, "client_role"=>$role_sig, "r_id"=>$node_id_mapped));
    //print_r($data);
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    
    $actlRetObj = CurlSendPostJson($actl_urls->actlCreateRoleCatCurlURL, $data_str,$logfile); //what we get from the ACTL

    //print_r($actlRetObj);

    //echo "\n";
    
    $perm_data_json_decode = json_decode($actlRetObj, true);
    //print_r($data_json_decode);
    $data_value = $perm_data_json_decode["d"];
    //print_r($data_value);
    //$perm_array = $data_json_decode["p"];
    //echo "\n";

    $data_json_decode = json_decode($data_value, true);
    $status_value = $data_json_decode["status"];
    $message = $data_json_decode["message"];
    //echo $message."\n";
    //echo $status_value."\n";

    
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

   
    
}
$logfile->logfile_close();
?>
