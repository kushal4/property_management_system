<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
include 'lib/php-lib/common_functions.php';

include '../lib/php-lib/sec.php';
//include 'lib/php-lib/eecee_sec_map.php';
//include 'curl_url_include.php';
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include '../lib/php-lib/session_exp.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

//echo "getting inside click_on_prop.php";

if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $prop_id=""; 
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="prop_id"){
            $prop_id = $value;
        }
        
    }

    $prop_id_decoded = sec_get_map_val ("prop_id_map", $prop_id);
    $logfile->logfile_writeline("the encoded prop ID is".$prop_id);
    $logfile->logfile_writeline("the decoded prop ID is".$prop_id_decoded); 

    $session_val= is_session_valid();

    if($session_val==0){
        $_SESSION["prop_id"] = $prop_id_decoded;

        $sess_user_id = $_SESSION["user_id"];
        $user_role_sql = "SELECT * FROM context_role WHERE prop_id = ? AND user_id = ?";
        $user_role_stmt = $conn->prepare($user_role_sql);
        $user_role_stmt->bind_param("ii",$prop_id_decoded, $sess_user_id);
        $user_role_stmt->execute();
        $user_role_result = $user_role_stmt->get_result();
        $user_role_row = $user_role_result->fetch_all();
    
        $role_sig_arr = array();
        foreach ($user_role_row as $value){  
                $role_sig = $value["3"];
            array_push($role_sig_arr,$role_sig);
            
        }

        $role_sig_arr_json_encode=json_encode($role_sig_arr);

        $actlGetRoleDetsCurlURL;

        $projectKey = $projectID;
        $hierarchy_true = "false";

        $data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id_decoded, "sig_arr"=>$role_sig_arr));
        //$data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id_decoded, "sig_arr"=>$role_sig_arr, "hierarchy"=>$hierarchy_true));
        
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);

        //Fetch role tree
        $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL

        $decodedJson = json_decode($actlRetObj, true);
        $jsonDataSTR = $decodedJson['d']."\n";

        $data_json_decode = json_decode($jsonDataSTR, true);

        $perm_array = $data_json_decode["p"];
        /*
        $tree = $perm_array["Tree"];
        $tree_arr_str = var_export($tree, true);
        $logfile->logfile_writeline("the tree array".$tree_arr_str);
        */

        $role_array = array();
        foreach ($perm_array as $value){  
            foreach ($value as $v){
                $sig = $v["sig"];
                $name = $v["name"];
                $type = $v["type"];

                if($type == "role"){
                    $secedRoleSig = sec_push_val_single_entry ("role_sig_map", $sig);
                    $role_sub_array["role_name"] = $name;
                    $role_sub_array["role_sig"] = $secedRoleSig;
                    array_push($role_array,$role_sub_array);
                }
                
            }
            
        }
        
        $raw_json["ret_code"] = 0;
        $raw_json["roles"] = $role_array;

        $raw_json_encode=json_encode($raw_json);
        echo $raw_json_encode;
    }
    else{     
        $raw_json["ret_code"] = 4; //if session expires 
    }
}
$logfile->logfile_close();
?>