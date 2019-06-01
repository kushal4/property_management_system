<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path.$SENSESSION->get_val("user_id").".log";
require_once $sense_common_php_lib_path.'Log.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

$logfile->logfile_writeline("getting inside init_sel_prop_role_tree.php"); 

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
    $role_sig="";
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="prop_id"){
            $prop_id = $value;
        }

        if ($key=="role_sig"){
            $role_sig = $value;
        }
        
    }

    $logfile->logfile_writeline("property id is::: ".$prop_id); 

       
    if($prop_id == "0" && $role_sig == ""){

        //$logfile->logfile_writeline("=====================getting inside this==================");

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

    }else if($prop_id != "0" && $role_sig == NULL){
        $logfile->logfile_writeline("getting inside this ::::  ");
        $prop_id_decoded = sec_get_map_val ("prop_id_map", $prop_id);
        $logfile->logfile_writeline("the encoded prop ID is".$prop_id);
        $logfile->logfile_writeline("the decoded prop ID is".$prop_id_decoded);
        $logfile->logfile_writeline("the role sig is".$role_sig);
        $prop_name = "";
        $sql_check_prop = "SELECT * FROM properties WHERE id = ?";
       
        $sql_check_prop_temp = $conn->prepare($sql_check_prop);
        if($sql_check_prop_temp){
            $logfile->logfile_writeline("prepare success");
            $bind = $sql_check_prop_temp->bind_param("i",$prop_id_decoded);
            if($bind){
                $logfile->logfile_writeline("bind success");
                $execute = $sql_check_prop_temp->execute();
                if($execute){
                    $logfile->logfile_writeline("execute success");
                    $sql_check_prop_result = $sql_check_prop_temp->get_result();
                    $sql_check_prop_row = $sql_check_prop_result->fetch_assoc();
                    $prop_name = $sql_check_prop_row["setup_name"];
                    $logfile->logfile_writeline("the property name is".$prop_name);
                }else{
                    $logfile->logfile_writeline("execute failed");
                }
                //$sql_check_prop_result = $sql_check_prop_temp->get_result();
                //$sql_check_prop_row = $sql_check_prop_result->fetch_assoc();
                //$prop_name = $sql_check_prop_row["setup_name"];
            }else{
                $logfile->logfile_writeline("bind failed");
            }
            
        }else{
            $logfile->logfile_writeline("prepare failed");
        }
        $logfile->logfile_writeline("the property name is :: line 265".$prop_name);
        $tree_root_obj = array();
        $role_tree = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = $prop_name;

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
    }else if($prop_id != "0" && $role_sig != NULL){

        $logfile->logfile_writeline("############################getting inside this###################################");
        $actlGetRoleDetsCurlURL = $actl_urls->actlGetRoleDetsCurlURL;
        $actlGetRoleTreeCurlURL = $actl_urls->actlGetRoleTreeCurlURL;
        
        $prop_id_decoded = sec_get_map_val ("prop_id_map", $prop_id);
        $logfile->logfile_writeline("the encoded prop ID is".$prop_id);
        $logfile->logfile_writeline("the decoded prop ID is".$prop_id_decoded);

        $role_sig_decoded = sec_get_map_val ("context_role_map", $role_sig);
        $logfile->logfile_writeline("the encoded role sig is".$role_sig);
        $logfile->logfile_writeline("the decoded role sig is".$role_sig_decoded);

        $SENSESSION->token("prop_id", $prop_id_decoded);

        $user_role_sql = "SELECT * FROM properties WHERE id = ?";
        $user_role_stmt = $conn->prepare($user_role_sql);
        $user_role_stmt->bind_param("i",$prop_id_decoded);
        $user_role_stmt->execute();
        $user_role_result = $user_role_stmt->get_result();
        $user_role_row = $user_role_result->fetch_assoc();
        $property_name = $user_role_row["setup_name"];

        $logfile->logfile_writeline("-----------property name----------".$property_name);

        /*
        $user_role_sql = "SELECT * FROM context_role WHERE prop_id = ? AND user_id = ?";
        $user_role_stmt = $conn->prepare($user_role_sql);
        $user_role_stmt->bind_param("ii",$prop_id_decoded, $sess_user_id);
        $user_role_stmt->execute();
        $user_role_result = $user_role_stmt->get_result();
        $user_role_row = $user_role_result->fetch_all();
        */
    
        /*
        $role_sig_arr = array();
        foreach ($user_role_row as $value){  
                $role_sig = $value["3"];
            array_push($role_sig_arr,$role_sig);
            
        }
        */
        $role_sig_arr = array();
        array_push($role_sig_arr,$role_sig_decoded);

        $role_sig_arr_json_encode=json_encode($role_sig_arr);

        //$actlGetRoleDetsCurlURL;

        $projectKey = $projectID;
        $hierarchy_true = "true";

        $logfile->logfile_writeline("the project key :: ".$projectKey);
        //$logfile->logfile_writeline("the client id is ::".$property_name);

        //$data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id_decoded, "sig_arr"=>$role_sig_arr));
        $data=array("param"=>array("key"=>$projectKey, "client_id"=>$prop_id_decoded, "sig_arr"=>$role_sig_arr, "hierarchy"=>$hierarchy_true));
        
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);

        //Fetch role tree
        $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL

        //$decodedJson = json_decode($actlRetObj, true);
        $decodedJson = json_decode($actlRetObj);

        //$decodedJson_str = var_export($decodedJson, true);
        //$logfile->logfile_writeline("The decodedJson is :: ".$decodedJson_str);

        //$jsonDataSTR = $decodedJson['d']."\n";
        $jsonDataSTR = $decodedJson->d;
        //$logfile->logfile_writeline("The jsonDataSTR :: ".$jsonDataSTR);

        //$data_json_decode = json_decode($jsonDataSTR, true);
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
                /*
                $val->type= "role";
                $val->reserved= "0";
                $val->active= NULL;
                $val->decommissioned="0";
                $val->editable=NULL;
                $val->linked_sig=NULL;
                $val->description=NULL;
                */

                //$var_str = var_export($val, true);
                //$logfile->logfile_writeline("The val is:: ".$var_str);

                array_push($concat_arr,$val);
            }
           
        }
    
        //$role_tree_str = var_export($roles, true);
        //$logfile->logfile_writeline("The role tree array is:: ".$role_tree_str);

        
        //$concat_arr_str = var_export($concat_arr, true);
        //$logfile->logfile_writeline("The concat array is:: ".$concat_arr_str);


        $sec_map_array =  array();
        $role_tree = get_role_tree($root_role_sig, $concat_arr, $sec_map_array, $logfile);
        //$logfile->logfile_writeline("");
        //$logfile->logfile_writeline("The Final role tree array::BEGIN");
        //$role_tree_str = var_export($role_tree, true);
        //$logfile->logfile_writeline($role_tree_str);
        //$logfile->logfile_writeline("The Final role tree array::END");

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
    
}
$logfile->logfile_close();
?>