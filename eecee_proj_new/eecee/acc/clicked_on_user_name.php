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

//echo "************getting inside click_on_prop.php";

if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];
    $json_decoded = json_decode($raw_json_str, true);
    $user_id=""; 
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="user_id"){
            $user_id = $value;
        }
        
    }

    $user_id_decoded = sec_get_map_val ("user_id_map", $user_id);
    $logfile->logfile_writeline("the encoded user ID is".$user_id);
    $logfile->logfile_writeline("the decoded user ID is".$user_id_decoded); 

    //echo "the user ID is::".$user_id_decoded;

    $session_val= is_session_valid();

    if($session_val==0){
        
        $prop_id = $SENSESSION->get_val("prop_id");
        $user_role_sql = "SELECT * FROM context_role WHERE prop_id = ? AND user_id = ?";
        $user_role_stmt = $conn->prepare($user_role_sql);
        $user_role_stmt->bind_param("ii",$prop_id, $user_id_decoded);
        $user_role_stmt->execute();
        $user_role_result = $user_role_stmt->get_result();
        $user_role_row = $user_role_result->fetch_all();
        
        $role_sig_arr = array();
        foreach ($user_role_row as $value){  
            foreach ($value as $k => $v){
                $role_sig = $value["3"];
            }
            array_push($role_sig_arr,$role_sig);
        }

        //print_r($role_sig_arr);

        
        $role_sig_arr_json_encode=json_encode($role_sig_arr);

        $projectKey = $projectID;
        $hierarchy_true = "true";

        $data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id, "sig_arr"=>$role_sig_arr, "hierarchy"=>$hierarchy_true));
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);

        $logfile->logfile_writeline("To Curl: ".$data_str); 
        $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str); //what we get from the ACTL
        $logfile->logfile_writeline("From Curl: ".$actlRetObj); 
        
        $decodedJson = json_decode($actlRetObj, true);
        $jsonDataSTR = $decodedJson['d']."\n";

        $data_json_decode = json_decode($jsonDataSTR, true);

        $perm_array = $data_json_decode["p"];

        $roles = $perm_array["roles"];
        //$tree = $perm_array["Tree"];
        //print_r($roles);
        
        
        //print_r($role_tree);
        $obj = array(
            'ret_code'=>0,
            'role'=>$roles
            
        );
        
        $obj_json = json_encode($obj);
        echo $obj_json;
    }
    else{     
        $raw_json["ret_code"] = 4; //if session expires 
    }
}
$logfile->logfile_close();
?>