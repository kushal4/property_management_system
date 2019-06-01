<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
$log_path = "Logs/eecee.log";
require_once '../lib/php-lib/Log.php';
include 'lib/php-lib/eecee_constants.php';
include 'lib/php-lib/eecee_include.php';
//include 'lib/php-lib/eecee_lib.php';
include 'lib/php-lib/common_functions.php';
include '../lib/php-lib/sec.php';
include '../lib/php-lib/actl_lib.php';
//include 'lib/php-lib/eecee_sec_map.php';
//include 'curl_url_include.php';

include '../lib/php-lib/session_exp.php';


$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}



function get_role_tree($role_sig, $role_arr, $user_role_arr, &$sec_map_arr, $logfile){
    $rand_id = gen_unique_sec_id($sec_map_arr); 
    $sec_map_arr[$rand_id]=$role_sig;

    $role_tree_obj = array();
    $role_tree_obj["children"] = array();

    $curr_role = get_role_by_sig($role_sig, $role_arr, $logfile);
    $role_tree_obj["id"] = $rand_id; //sec-mapped node_id
    $role_tree_obj["text"] = $curr_role["name"];
    
    if ($curr_role["type"]=="rolecat") {
        $role_tree_obj["type"] = "rolecat";
        if ($curr_role["reserved"]==1) {
            $role_tree_obj["type"] = "cat_res";
        }
        if ($curr_role["active"]==0) {
            $role_tree_obj["type"] = "cat_deact";
        }

    }
    if ($curr_role["type"]=="role") {
        $role_tree_obj["type"] = "role";
        if ($curr_role["reserved"]==1) {
            $role_tree_obj["type"] = "role_res";
        }
        if ($curr_role["decommissioned"]==1) {
            $role_tree_obj["type"] = "role_dcm";
        }
        if ($curr_role["active"]==0) {
            $role_tree_obj["type"] = "role_deact";
        }
        if ($curr_role["linked_sig"]!=NULL) {
            $role_tree_obj["type"] = "role_link";
        }

    }


    $children = get_role_child($role_sig, $role_arr, $logfile); //Find children

    foreach ($children as $child){  
        $child_sig = $child["sig"];
        $ret_child =  get_role_tree($child_sig, $role_arr, $sec_map_arr, $logfile);
        array_push($role_tree_obj["children"], $ret_child);
    }
   //echo __FUNCTION__."::role_tree_obj=".$role_tree_obj."<br><br>";
   //print_r($role_tree_obj);

   return $role_tree_obj;
   
}





if (is_ajax()) {
    

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];

    $logfile->logfile_writeline("AJAX Parameter:BEGIN"); 
    $logfile->logfile_writeline($raw_json_str); 
    $logfile->logfile_writeline("AJAX Parameter:END"); 

    $json_decoded = json_decode($raw_json_str, true);
    $prop_id=""; 
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="prop_id"){
            $prop_id = $value;
        }
        
    }

    $logfile->logfile_writeline("property id is::: ".$prop_id); 

    
    
    
    if($prop_id == "0"){

        $logfile->logfile_writeline("=====================getting inside this==================");

        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = "Please Select a Property";

        $role_tree = array();
        
        $obj = array(
            'ret_code'=>0,
            'root'=>$tree_root_obj,
            'role'=>$role_tree 
        );

        $obj_json = json_encode($obj);
        $logfile->logfile_writeline("RoleTree:BEGIN"); 
        $logfile->logfile_writeline($obj_json); 
        $logfile->logfile_writeline("RoleTree:END"); 


    echo $obj_json;

    }else{

        $logfile->logfile_writeline("############################getting inside this###################################");

        
        $prop_id_decoded = sec_get_map_val ("prop_id_map", $prop_id);
        $logfile->logfile_writeline("the encoded prop ID is".$prop_id);
        $logfile->logfile_writeline("the decoded prop ID is".$prop_id_decoded);

        $user_role_sql = "SELECT * FROM properties WHERE id = ?";
        $user_role_stmt = $conn->prepare($user_role_sql);
        $user_role_stmt->bind_param("i",$prop_id_decoded);
        $user_role_stmt->execute();
        $user_role_result = $user_role_stmt->get_result();
        $user_role_row = $user_role_result->fetch_assoc();
        $property_name = $user_role_row["setup_name"];

        $logfile->logfile_writeline("-----------property name----------".$property_name);

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
        $roles = $perm_array["roles"];

        $sec_map_array =  array();
        $role_tree = get_role_tree($root_role_sig, $roles, $role_sig_arr, $sec_map_array, $logfile);
        sec_push_map ("role_sig_map", $sec_map_array);

        //$property_name = find_property_name($prop_id_decoded);

        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = $property_name;

        
        $obj = array(
            'ret_code'=>0,
            'root'=>$tree_root_obj,
            'role'=>$role_tree 
        );
        
        $obj_json = json_encode($obj);
        $logfile->logfile_writeline("RoleTree:BEGIN"); 
        $logfile->logfile_writeline($obj_json); 
        $logfile->logfile_writeline("RoleTree:END"); 


    echo $obj_json;
    }

    

    

    
    

    //print_r($role_tree);

    
    
    
}
$logfile->logfile_close();
?>