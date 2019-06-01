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



function find_roles_by_parent($role_arr, $parent_sig){
    $child_arr = array();
    foreach ($role_arr as $r){  
        if($r["parent"] == $parent_sig){
            array_push($child_arr, $r);
        }   
    }
    return $child_arr;
}

function get_role_by_sig_local($role_sig, $role_arr, $logfile){
    foreach ($role_arr as $value){  
        if($value->sig == $role_sig){
            return $value;
        }
    }
}

function get_role_child_local($role_sig, $role_arr, $logfile){
    $child_arr = array(); //array of objects

    foreach ($role_arr as $value){  
        $parent = $value->parent;
        //echo "the parent ID is:: ".$parent."\n";
        if($parent == $role_sig){
            array_push($child_arr, $value); 
        }
    }
    return $child_arr;
}

function get_role_tree_orig($role_sig, $role_arr, &$sec_map_arr, $logfile){
    $rand_id = gen_unique_sec_id($sec_map_arr); 
    $sec_map_arr[$rand_id]=$role_sig;
    $role_tree_obj = array();
    $role_tree_obj["children"] = array();
    /*
    $c_arr = find_roles_by_parent($role_arr, $role_sig);
    if($c_arr !=NULL){
        foreach ($c_arr as $r){  
            $role_obj = array();
            $role_obj["id"] = $r["sig"]; 
            $role_obj["text"] = $r["name"];
            array_push($role_tree_obj["children"], $role_obj);
        }
    }
    */
    $curr_role = get_role_by_sig($role_sig, $role_arr, $logfile);
    $role_tree_obj["id"] = $rand_id; //sec-mapped node_id
    $role_tree_obj["text"] = $curr_role["name"];
    $role_tree_obj["class"] = "sel_prop_class";
    
    if ($curr_role["type"]=="rolecat") {
        $role_tree_obj["type"] = "rolecat";
        $role_tree_obj["data"] = "rolecat";
        
        if ($curr_role["reserved"]==1) {
            $role_tree_obj["type"] = "cat_res";
        }
        /*
        if ($curr_role["active"]==0) {
            $role_tree_obj["type"] = "cat_deact";
        }
        */
    }
    if ($curr_role["type"]=="role") {
        $role_tree_obj["type"] = "role";
        $role_tree_obj["data"] = "role";
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
   return $role_tree_obj;
}

function get_role_tree($role_sig, $role_arr, &$sec_map_arr, $logfile){
    $rand_id = gen_unique_sec_id($sec_map_arr); 
    $sec_map_arr[$rand_id]=$role_sig;
    $role_tree_obj = new stdClass();
    $role_tree_obj->children = array();
    
    $curr_role = get_role_by_sig_local($role_sig, $role_arr, $logfile);
    $role_tree_obj->id = $rand_id; //sec-mapped node_id
    $role_tree_obj->text = $curr_role->name;
    $role_tree_obj->class = "sel_prop_class";
    
    if ($curr_role->type=="rolecat") {
        $role_tree_obj->type = "rolecat";
        $role_tree_obj->data = "rolecat";
        
        if ($curr_role->reserved==1) {
            $role_tree_obj->type = "cat_res";
        }
    }
    if ($curr_role->type=="role") {
        $role_tree_obj->type = "role";
        $role_tree_obj->data = "role";
        if ($curr_role->reserved==1) {
            $role_tree_obj->type = "role_res";
        }
        if ($curr_role->decommissioned==1) {
            $role_tree_obj->type = "role_dcm";
        }

        if ($curr_role->linked_sig!=NULL) {
            $role_tree_obj->type = "role_link";
        }
    }

    $children = get_role_child_local($role_sig, $role_arr, $logfile); //Find children

    foreach ($children as $child){
        $child_sig = $child->sig;
        $ret_child =  get_role_tree($child_sig, $role_arr, $sec_map_arr, $logfile);
        array_push($role_tree_obj->children, $ret_child);
    }
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

    //$role_sig = $_SESSION["role_sig"];
    $prop_id = $_SESSION["prop_id"];
    $role_sig = $_SESSION["role_sig"];
    $feature_sig = "ACC_MAN_USR";
    $feature_sig_02 = "ACC_MAN_ROLE";
    $enable_false = "true";
    $hierarchy_true = "true";
    $logfile->logfile_writeline("the the role sig is:::: ".$role_sig); 
    $sig_arr = array();
    array_push($sig_arr ,$role_sig);

    $perm = array();


    array_push($perm, array("sig"=>$feature_sig, "enable"=>$enable_false ));

    $vd_str = var_export($feature_sig, true);
        //$logfile->logfile_writeline("get_prop_topo::AJAX:: topo_tree ".$vd_str);

    //echo "the prop ID is:: ".$prop_id;
    $logfile->logfile_writeline("the prop ID is:: ".$prop_id); 
    $logfile->logfile_writeline("the feature sig is:: ".$feature_sig); 
    
    $actlGetRoleDetsCurlURL;

        $projectKey = $projectID;
        $data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id, "sig_arr"=>$sig_arr, "hierarchy"=>$hierarchy_true));
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
        $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL
        $decodedJson = json_decode($actlRetObj);
        $jsonDataSTR = $decodedJson->d;
        $data_json_decode = json_decode($jsonDataSTR);
        $perm_array = $data_json_decode->p;
        $roles = $perm_array->roles; 
        $tree = $perm_array->Tree;
        $concat_arr = array();


        foreach ($roles as $value){  
            array_push($concat_arr,$value);
        }

        foreach ($tree as $key=>$value){  
            foreach ($value as $val){ 
                $val->creator= $val->parent;
                $val->parent= $val->category;
                $val->type= "role";
                $val->reserved= "0";
                $val->active= NULL;
                $val->decommissioned="0";
                $val->editable=NULL;
                $val->linked_sig=NULL;
                $val->description=NULL;

                //$var_str = var_export($val, true);
                //$logfile->logfile_writeline("The val is:: ".$var_str);

                array_push($concat_arr,$val);
            }
           
        }
    
        $sec_map_array =  array();
        $role_tree = get_role_tree($root_role_sig, $concat_arr, $sec_map_array, $logfile);

        $role_str = var_export($role_tree, true);
        $logfile->logfile_writeline("The decodedJson is :: ".$role_str);
        
        sec_push_map ("role_sig_map", $sec_map_array);

        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = "prop name";

        
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