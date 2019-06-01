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

$logfile->logfile_writeline("getting inside create_role_n_category");
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
    
    $parent_id="";
    $node_name="";
    $node_type="";
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="parent_id"){
            $parent_id = $value;
        }

        if ($key=="node_name"){
            $node_name = $value;
        }
        if ($key=="node_type"){
            $node_type = $value;
        }
    }
    //echo $node_id;
    $logfile->logfile_writeline("the encoded node ID is".$parent_id);
    $logfile->logfile_writeline("the node name is".$node_name);
    $logfile->logfile_writeline("the node type is".$node_type);


    if($parent_id == "PROP_ROOT"){
        $parent_id_mapped = "PROP_ROOT";
    }else{

        $curr_map = sec_get_map("role_sig_map");
        $logfile->logfile_writeline(__FILE__."create role n category ********************************* Dumping role_sig_map MAP: Begin");
                foreach($curr_map as $key => $value)
                    {
                        $logfile->logfile_writeline($key." : ".$value);
                    }
        $logfile->logfile_writeline(__FILE__."create role n category ********************************* Dumping role_sig_map MAP: End");
    


        $parent_id_mapped = sec_get_map_val ("role_sig_map", $parent_id);
        $logfile->logfile_writeline("the encoded node ID is".$parent_id);
        $logfile->logfile_writeline("the decoded node ID is".$parent_id_mapped);
    }
    

    $actlCreateRoleCatCurlURL = $actl_urls->actlCreateRoleCatCurlURL;
    //echo "the node SIG is:: ".$node_id_mapped."\n";

    $role_sig = $SENSESSION->get_val("role_sig");
    $prop_id = $SENSESSION->get_val("prop_id");
    if($node_type == "rolecat"){
        //echo "here";
        $createMethod = "create_role_cat";
    }else if($node_type == "role"){
        //echo "here";
        $createMethod = "create_role";
    }
    
    $createRole = "create_role";

    $name = $node_name;
    $parent_sig = $parent_id_mapped;

    
    if($node_type == "rolecat"){
        $logfile->logfile_writeline("getting inside rolecat");
        //$createMethod = "create_role_cat";
        $data=array("param"=>array("method"=>$createMethod, "key"=>$projectID, "client_id"=>$prop_id, "client_role"=>$role_sig, "name"=>$name, "description"=>$name, "parent_sig"=>$parent_sig));
    }else if($node_type == "role"){
        $logfile->logfile_writeline("getting inside role");
        $createMethod = "create_role";
        $data=array("param"=>array("method"=>$createMethod, "key"=>$projectID, "client_id"=>$prop_id, "client_role"=>$role_sig, "name"=>$name, "description"=>$name, "parent_sig"=>$role_sig, "cat_sig"=>$parent_sig));
    }

    
    
    //print_r($data);
    $logfile->logfile_writeline("here 1");
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $actlRetObj = CurlSendPostJson($actlCreateRoleCatCurlURL, $data_str, $logfile); //what we get from the ACTL
    $logfile->logfile_writeline("From API cURL");

    $actlRetObj_str = var_export($actlRetObj, true);
    $logfile->logfile_writeline("The actlRetObj_str is :: ".$actlRetObj_str);
    //print_r($actlRetObj);

    $raw_json["ret_code"] = 0;
    
    $raw_json_encode=json_encode($raw_json);
    echo $raw_json_encode;
    
}
$logfile->logfile_close();
?>