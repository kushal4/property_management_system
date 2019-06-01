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

function get_role_by_sig_local($role_sig, $role_arr, $logfile){
    foreach ($role_arr as $value){  
        if($value["sig"] == $role_sig){
            return $value;
        }
    }
}

function get_role_tree($role_sig, $role_arr, &$sec_map_arr, $logfile){
    $rand_id = gen_unique_sec_id($sec_map_arr); 
    $sec_map_arr[$rand_id]=$role_sig;

    $role_tree_obj = array();
    $role_tree_obj["children"] = array();

    $curr_role = get_role_by_sig($role_sig, $role_arr, $logfile);
    $role_tree_obj["id"] = $rand_id; //sec-mapped node_id
    $role_tree_obj["text"] = $curr_role["name"];
    //$role_tree_obj["reserved"] = $curr_role["reserved"];
    
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
        /*
        if ($curr_role["active"]==0) {
            $role_tree_obj["type"] = "role_deact";
        }*/
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
    
    $raw_json_str = $_POST["k"];
    $logfile->logfile_writeline("AJAX Parameter:BEGIN"); 
    $logfile->logfile_writeline($raw_json_str); 
    $logfile->logfile_writeline("AJAX Parameter:END"); 

    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $prop_id = $SENSESSION->get_val("prop_id");
    $feature_sig = "ACC_MAN_USR";
    $enable_false = "true";
    $hierarchy_true = "true";

    $actlRoleTreeByPermCurlURL = $actl_urls->actlRoleTreeByPermCurlURL;
    $actlGetRoleTreeCurlURL = $actl_urls->actlGetRoleTreeCurlURL;

    $perm = array();
    array_push($perm, array("sig"=>$feature_sig, "enable"=>$enable_false ));

    $vd_str = var_export($feature_sig, true);
        //$logfile->logfile_writeline("get_prop_topo::AJAX:: topo_tree ".$vd_str);

    //echo "the prop ID is:: ".$prop_id;
    $logfile->logfile_writeline("the prop ID is:: ".$prop_id); 
    $logfile->logfile_writeline("the feature sig is:: ".$feature_sig); 
    
    //$data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "perm"=>array("sig"=>$feature_sig, "enable"=>$enable_false )));
    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "perm"=>$perm));

    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $actlRetObj = CurlSendPostJson($actlRoleTreeByPermCurlURL, $data_str, $logfile); //what we get from the ACTL

    
    $decodedJson = json_decode($actlRetObj, true);
    $jsonDataSTR = $decodedJson['d']."\n";
    $data_json_decode = json_decode($jsonDataSTR, true);
    $perm_array = $data_json_decode["p"];
    $SENSESSION->token("perm_obj", $perm_array);
    $roles_01 = $perm_array["roles"]; //=================== role 01 
    $roles_01_str = var_export($roles_01, true);
    $logfile->logfile_writeline("role 1 ::  ".$roles_01_str);


    ////////////////////////////////////////  Role 02 Beginning /////////////////////////////////////
    $role_sig = $SENSESSION->get_val("role_sig");
    $logfile->logfile_writeline("the role sig is::  ".$role_sig); 
    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "role_sig"=>$role_sig));
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $actlRetObj = CurlSendPostJson($actlGetRoleTreeCurlURL, $data_str, $logfile); //what we get from the ACTL
    
    $decodedJson = json_decode($actlRetObj, true);
    $jsonDataSTR = $decodedJson['d']."\n";
    $data_json_decode = json_decode($jsonDataSTR, true);
    $perm_array = $data_json_decode["p"];
    $SENSESSION->token("perm_obj", $perm_array);
    $roles_02 = $perm_array["roles"];//=================== role 02

    $roles_02_str = var_export($roles_02, true);
    $logfile->logfile_writeline("role 2 ::  ".$roles_02_str);
    ////////////////////////////////////////  Role 02 Ends /////////////////////////////////////

    $role_array = array();
    foreach ($roles_02 as $value){  
        if($value["type"] == "rolecat"){
            array_push($role_array, $value);
        }
    }

    $step1_role_arr_str = var_export($role_array, true);
    $logfile->logfile_writeline("Step 1: role array :: ".$step1_role_arr_str);
    

    foreach ($roles_01 as $value){  
        $role_01_sig = $value["sig"];
        $role_by_sig = get_role_by_sig_local($role_01_sig, $roles_02, $logfile);
        if (array_key_exists("editable",$role_by_sig))
        {
            if($role_by_sig["editable"] == true){
                array_push($role_array, $role_by_sig);
            }
        }
    }

    $step2_role_arr_str = var_export($role_array, true);
    $logfile->logfile_writeline("Step 2: role array :: ".$step2_role_arr_str);

    $sec_map_array =  array();
    $role_tree = get_role_tree($root_role_sig, $role_array, $sec_map_array, $logfile);
    sec_push_map ("role_sig_by_perm_map", $sec_map_array);

    $tree_root_obj = array();
    $tree_root_obj["children"] = array();
    $tree_root_obj["id"] = "PROP_ROOT";
    $tree_root_obj["text"] = "Prop Name";

    
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
$logfile->logfile_close();
?>