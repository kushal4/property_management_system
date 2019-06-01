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

class out{
    public $key="";
     public $value="";
 }

$logfile = new \Sense\Log($log_path, __FILE__);
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

function get_role_tree($role_sig, $role_arr, $logfile){
    $logfile->logfile_writeline("get_role_tree::BEGIN::role_sig=".$role_sig); 
    //$rand_id = gen_unique_sec_id($sec_map_arr); 
    //$sec_map_arr[$rand_id]=$role_sig;
    $role_tree_obj = array();
    $role_tree_obj["children"] = array();
    $curr_role = get_role_by_sig($role_sig, $role_arr, $logfile);
    $curr_role_str = var_export($curr_role, true);
    $logfile->logfile_writeline("get_role_tree::curr_role=".$curr_role_str);

    $role_tree_obj["id"] = $curr_role["sig"]; //sec-mapped node_id
    $role_tree_obj["text"] = $curr_role["name"];
    $state_arr = array();
    if (array_key_exists("checked",$curr_role)){
        $state_arr["selected"] = $curr_role["checked"];
    }
    if (array_key_exists("disabled",$curr_role)){
        $state_arr["disabled"] = $curr_role["disabled"];
        $role_tree_obj["type"] = "role_dis";   
    }
    $role_tree_obj["state"]=$state_arr;
    $role_tree_obj["class"] = "sel_prop_class";
    if ($curr_role["type"]=="rolecat") {
        $role_tree_obj["class"] = "rolecat_no_check";
        $role_tree_obj["type"] = "rolecat";
        $role_tree_obj["data"] = "rolecat";
        if ($curr_role["reserved"]==1) {
            $role_tree_obj["type"] = "cat_res";
        }
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

        if ($curr_role["linked_sig"]!=NULL) {
            $role_tree_obj["type"] = "role_link";
        }
    }
    if ($curr_role["type"]=="linkedrole") {
        $role_tree_obj["type"] = "role_link";
    }
    $children = get_role_child($role_sig, $role_arr, $logfile); //Find children
    $children_str = var_export($children, true);
    //$logfile->logfile_writeline("get_role_tree->get_role_child::children=".$children_str); 
    foreach ($children as $child){  
        //$logfile->logfile_writeline("get_role_tree::role_sig=".$role_sig); 
        $child_sig = $child["sig"];
        $ret_child =  get_role_tree($child_sig, $role_arr, $logfile);
        array_push($role_tree_obj["children"], $ret_child);
    }
   return $role_tree_obj;
}



/////////////////////////////   End of function   /////////////////////////////



if (is_ajax()) {
    
    $prop_id = $SENSESSION->get_val("prop_id");
    $logfile->logfile_writeline("AJAX Parameter:Prop_id=".$prop_id); 
    $conn = new \mysqli($server_name, $user_name, $password, $dbname);
    $logfile->logfile_writeline("the conn is :: ".$prop_id); 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $raw_json_str = $_POST["k"];

    $logfile->logfile_writeline("AJAX Parameter:BEGIN"); 
    $logfile->logfile_writeline($raw_json_str); 
    $logfile->logfile_writeline("AJAX Parameter:END"); 

    $json_decoded = json_decode($raw_json_str, true);
    $user_id=""; 
    $op=""; 
    $m="";
    $m_node="";
    $s="";
    foreach ($json_decoded as $key => $value) {
        
        if ($key=="user_id"){
            $user_id = $value;
        }
        if ($key=="op"){
            $op = $value;
        }
        if ($key=="m"){
            $m = $value;
        }
        if ($key=="m_node"){
            $m_node = $value;
        }
        if ($key=="s"){
            $s = $value;
        }
        
    }

    $logfile->logfile_writeline("AJAX Parameter:user_id=".$user_id); 

    if($user_id == "0"){
        
        $m = array();
        $logfile->logfile_writeline("=====================getting inside this================== user Id is 0");
        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["data"] = $m;
        $tree_root_obj["text"] = "Please select an user name from the first fold";
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

    }else if($json_decoded == NULL){
        
        $data_arr = array();
        $data_arr["sp"] = 1;
        $logfile->logfile_writeline("=====================getting inside this================== user Id is 0");
        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["data"] = $data_arr;
        $tree_root_obj["text"] = "Please select an user name from the first fold";
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
        $logfile->logfile_writeline("=====================getting inside this================== user Id is not 0");
        $logfile->logfile_writeline("the OP is :: ".$op);
        
        $logged_in_role_sig = $SENSESSION->get_val("role_sig");
        $logfile->logfile_writeline("AJAX Parameter:logged_in_role_sig=".$logged_in_role_sig); 
        $prop_id = $SENSESSION->get_val("prop_id");
        
        // Step 01 :: start
        $ctx_id = sec_get_map_val ("ctx_id_map", $user_id);
        $logfile->logfile_writeline("the encoded user ID is".$user_id);
        $logfile->logfile_writeline("the decoded context ID is".$ctx_id);
        // Step 01 :: end

        // Step 02.1 :: start 
        

        if( $op == "s"){
            $logfile->logfile_writeline("the property ID from session is :: ".$prop_id);
            $arg = array();
            $arg["proj_key"] = $projectID;
            $arg["prop_id"] = $prop_id;
            $arg["dbconn"] = $conn;
            $arg["actl_urls"] = $actl_urls;
            $arg["logged_role"] = $logged_in_role_sig;
            $arg["ctx_id"] = $ctx_id;
            $arg["logfile"] = $logfile;

            $arg_str = var_export($arg, true);
            $logfile->logfile_writeline("arg_array ::::: ".$arg_str);
            $local_collection = create_local_collection($arg);

            $local_collection_str = var_export($local_collection, true);
            $logfile->logfile_writeline("step 02.1 :: local_collection_str ".$local_collection_str);
        }else{
            $assign_role_sig_map = sec_get_map("assign_role_sig_map");
            $arg2["ob_manifest"] = $m;
            $arg2["char_set"] = $char_set;
            $arg2["sec_map"] = $assign_role_sig_map;
            $arg2["logfile"] = $logfile;
            $local_collection = restore_manifest($arg2);
            $unsec_local_collection = unsec_local_collection($local_collection, $assign_role_sig_map, $logfile);
            //$unsec_local_collection_str = var_export($unsec_local_collection, true);
            //$logfile->logfile_writeline("unsec_local_collection_str :: ".$unsec_local_collection_str);

            $loc_col_str = var_export($local_collection, true);
            $logfile->logfile_writeline("else part :: local_collection ::::: ".$loc_col_str);
            
            
            if ($op=="c") { //a new role have been selected
                $logfile->logfile_writeline("the m_node is :: ".$m_node);
                $m_node_decoded = sec_get_map_val ("assign_role_sig_map", $m_node);
                $logfile->logfile_writeline("the decoded m_node ID is".$m_node_decoded);
                //$checked_role = get_role_by_sig($m_node_decoded, $local_collection["r"], $logfile);
                //$checked_role["checked"] = true;
                //$loc_col_sel_role_str = var_export($local_collection["u"], true);
                //$logfile->logfile_writeline("loc_col_sel_role_str :: ".$loc_col_sel_role_str);
                //array_push($local_collection["s"],$checked_role);

                $checked_role = &get_role_ref_by_sig($m_node_decoded, $local_collection["r"], $logfile); 
                $checked_role_node_str = var_export($checked_role, true);
                $logfile->logfile_writeline("checked_role node :: ".$checked_role_node_str);
                $checked_role_node_str_after = var_export($checked_role, true);
                $logfile->logfile_writeline("checked_role node :: after :: ".$checked_role_node_str_after);
                array_push($local_collection["u"],$checked_role);

                $loc_col_sel_role_str = var_export($local_collection["u"], true);
                $logfile->logfile_writeline("op=c ::loc_col_sel_role_str :: ".$loc_col_sel_role_str); 
            }
            
            if ($op=="u") { //a role have been deselected
                $logfile->logfile_writeline("********************************getting inside op = u ************************ ".$m_node);
                $logfile->logfile_writeline("uncheck :: the m_node is :: ".$m_node);
                $m_node_decoded = sec_get_map_val ("assign_role_sig_map", $m_node);
                $logfile->logfile_writeline("uncheck :: the decoded m_node ID is".$m_node_decoded);
                //$checked_role = &get_role_ref_by_sig($m_node_decoded, $local_collection["r"], $logfile);
                $loc_col_sel_role_str = var_export($local_collection["u"], true);
                $logfile->logfile_writeline("uncheck ::loc_col_sel_role_str :: ".$loc_col_sel_role_str); 
                
                $res_array = array();
                foreach($local_collection["u"] as $node){
                    if($node["sig"] == $m_node_decoded){
                        $logfile->logfile_writeline("uncheck : matched ".$node["sig"]);
                    }else{
                        
                        $logfile->logfile_writeline("uncheck : did not matched ".$node["sig"]);
                        array_push($res_array,$node);
                    }
                }
                
                $local_collection["u"] = $res_array;
            }
            if ($op=="w") { //save in database
                //Do saving routine

                $logfile->logfile_writeline(" op=w :: the op is ::  ".$op);
                $s_str = var_export($s, true);
                $logfile->logfile_writeline("s_str (from ajax) :: ".$s_str);

                $loc_col_str = var_export($local_collection, true);
                $logfile->logfile_writeline("w :: local_collection ::::: ".$loc_col_str);

                $loc_col_sel_role_str = var_export($local_collection["u"], true);
                $logfile->logfile_writeline("op=w ::loc_col_sel_role_str :: ".$loc_col_sel_role_str);
                
                /*
                $assign_role_sig_map = sec_get_map("assign_role_sig_map");
                $arg2["ob_manifest"] = $m;
                $arg2["char_set"] = $char_set;
                $arg2["sec_map"] = $assign_role_sig_map;
                $arg2["logfile"] = $logfile;
                $local_collection = restore_manifest($arg2);
                $unsec_local_collection = unsec_local_collection($local_collection, $assign_role_sig_map, $logfile);
                */

                // op = w :: step 01 :: start
                
                $sql_delete = $conn->prepare("DELETE from context_role where ctx_id = ?");
                $sql_delete->bind_param("i", $ctx_id);
                $sql_delete->execute();
                $sql_delete_res = $sql_delete->get_result();
                
                // op = w :: step 01 :: end

                // op = w :: step 02 :: start
                foreach($local_collection["u"] as $role){
                    $logfile->logfile_writeline("the role_sig is:: ".$role["sig"]);

                    $arg["proj_key"] = $projectID;
                    $arg["prop_id"] = $prop_id;
                    $arg["actl_urls"] = $actl_urls;
                    //$arg["char_set"] = $char_set;
                    //$arg["sec_map"] = $assign_role_sig_map;
                    $arg["role_sig"] = $role["sig"];
                    $arg["logfile"] = $logfile;

                    $is_res_ret = is_resident($arg);
                    $is_res_ret_str = var_export($is_res_ret, true);
                    $logfile->logfile_writeline("w :: is_res_ret_str ::::: ".$is_res_ret_str);

                    if($is_res_ret_str == "true"){
                        $logfile->logfile_writeline("getting inside true");
                        $res_1 = 1;
                        $user_role_sql = "INSERT INTO context_role(ctx_id, role_sig, resident) VALUES (?, ?, ?)";
                        $user_role_temp = $conn->prepare($user_role_sql);
                        if($user_role_temp){
                            //$logfile->logfile_writeline("context_role :: insert :: prepare success");
                            $bind = $user_role_temp->bind_param("isi",$ctx_id, $role["sig"], $res_1);
                            if($bind){
                                //$logfile->logfile_writeline("context_role :: insert :: bind success");
                                $execute= $user_role_temp->execute();
                                if($execute){
                                    //$logfile->logfile_writeline("context_role :: insert :: execute success");
                                }else{
                                    //$logfile->logfile_writeline("context_role :: insert :: execute failure");
                                }
                            }else{
                                //$logfile->logfile_writeline("context_role :: insert :: bind failure");
                            }
                            
                        }else{
                            //$logfile->logfile_writeline("context_role :: insert :: prepare failure");
                        }
                    
                    }else{
                        $logfile->logfile_writeline("getting inside false");
                        $res_0 = 0;
                        $user_role_sql = "INSERT INTO context_role(ctx_id, role_sig, resident) VALUES (?, ?, ?)";
                        $user_role_temp = $conn->prepare($user_role_sql);
                        if($user_role_temp){
                            //$logfile->logfile_writeline("context_role :: insert :: prepare success");
                            $bind = $user_role_temp->bind_param("isi",$ctx_id, $role["sig"], $res_0);
                            if($bind){
                                //$logfile->logfile_writeline("context_role :: insert :: bind success");
                                $execute= $user_role_temp->execute();
                                if($execute){
                                    //$logfile->logfile_writeline("context_role :: insert :: execute success");
                                }else{
                                    //$logfile->logfile_writeline("context_role :: insert :: execute failure");
                                }
                            }else{
                                //$logfile->logfile_writeline("context_role :: insert :: bind failure");
                            }
                            
                        }else{
                            //$logfile->logfile_writeline("context_role :: insert :: prepare failure");
                        }
                    
                    }
                    
                    
                }
                // op = w :: step 02 :: end

                // op = w :: step 03 :: start
                $arg = array();
                $arg["proj_key"] = $projectID;
                $arg["prop_id"] = $prop_id;
                $arg["dbconn"] = $conn;
                $arg["actl_urls"] = $actl_urls;
                $arg["logged_role"] = $logged_in_role_sig;
                $arg["ctx_id"] = $ctx_id;
                $arg["logfile"] = $logfile;

                $arg_str = var_export($arg, true);
                $logfile->logfile_writeline("op = w :: step 03 arg_array :: ".$arg_str);
                $local_collection = create_local_collection($arg);

                $local_collection_str = var_export($local_collection, true);
                $logfile->logfile_writeline("op = w :: step 03 :: local_collection ::::: ".$local_collection_str);
                // op = w :: step 03 :: end
                //Create local collection
                /*
                $arg = array();
                $arg["proj_key"] = $projectID;
                $arg["prop_id"] = $prop_id;
                $arg["dbconn"] = $conn;
                $arg["actl_urls"] = $actl_urls;
                $arg["logged_role"] = $logged_in_role_sig;
                $arg["user_id"] = $user_id_decoded;
                $arg["logfile"] = $logfile;
        
                $arg_str = var_export($arg, true);
                $logfile->logfile_writeline("arg_array ::::: ".$arg_str);
        
                $local_collection = create_local_collection($arg);
                */
            }
        }
        
        $local_collection_str = var_export($local_collection, true);
        $logfile->logfile_writeline("before sec :: create_local_collection ::::: ".$local_collection_str);

        // Step 02.1 :: end

        // Step 02.2 :: start
        $role_sec_map = array();
        $role_sec_map_inv = array(); //INverted sec map
        sec_local_collection($local_collection, $role_sec_map, $role_sec_map_inv, $logfile);
        sec_push_map("assign_role_sig_map", $role_sec_map);

        /*
        $role_sec_map_inv = array();
        foreach ($role_sec_map as $k=>$v){
            $role_sec_map_inv[$v] = $k;
        }
        */

        $local_collection_str = var_export($local_collection, true);
        $logfile->logfile_writeline("Step 02.2 :: after sec :: create_local_collection ::::: ".$local_collection_str);
        // Step 02.2 :: end

        // ****************************** TEST MANIFEST FUNCTION :: start ******************************
        // test :: STEP 01 :: start
        /*
        $arg["local_coll"] = $local_collection;
        $arg["char_set"] = $char_set;
        $arg["front_end_admin_cat_sig"] = $role_sec_map_inv["FRONTENDADMINCATEGORY"];
        $arg["sec_map"] = $role_sec_map_inv;
        $arg["logfile"] = $logfile;

        $mnfs = create_manifest($arg);
        $mnfs_str = var_export($mnfs, true);
        $logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 01 :: ********** START **********");
        $logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 01 ".$mnfs_str);
        $logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 01 :: ********** END **********");
        */
        // test :: STEP 01 :: end

        // test :: STEP 02 :: start 
        /*
        $arg2["ob_manifest"] = $mnfs;
        $arg2["char_set"] = $char_set;
        $arg2["sec_map"] = $role_sec_map;
        $arg2["logfile"] = $logfile;

        $local_coll2 = restore_manifest($arg2);

        $local_coll2_str = var_export($local_coll2, true);
        $logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 02 :: ********** START **********");
        $logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 02 ".$local_coll2_str);
        $logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 02 :: ********** END **********");
        */
        // test :: STEP 02 :: end
        // ****************************** TEST MANIFEST FUNCTION :: end ******************************


        // Step 03 :: start 
        $roles1 = array();
        $local_collection_s = $local_collection["s"];
        foreach ($local_collection_s as $r) {
            $r["disabled"] = false;
            $r["checked"] = false;
            array_push($roles1,$r);
        }

        $roles1_str = var_export($roles1, true);
        $logfile->logfile_writeline("step 03 ::: roles1 ".$roles1_str);
        // Step 03 :: end 

        // step 04 :: start 

        // step 04.1 :: start
        $l_role = $local_collection["l"];
        $l_role_str = var_export($l_role, true);
        $logfile->logfile_writeline("step 04.1 ::: l_role ".$l_role_str);
        foreach ($l_role as $r) {
            $r["editable"] = true;
            array_push($roles1,$r);
        }

        $roles1_str = var_export($roles1, true);
        $logfile->logfile_writeline("step 04.1 ::: roles1 ".$roles1_str);

        //array_push($roles1,$l_role);
        // step 04.1 :: end

        // step 04.2 :: start
        $local_collection_r = $local_collection["r"];
        $local_collection_l = $local_collection["l"];

        $local_collection_r_str = var_export($local_collection_r, true);
        $logfile->logfile_writeline("step 04.2 ::: local_collection_r_str ".$local_collection_r_str);

        $local_collection_l_str = var_export($local_collection_l, true);
        $logfile->logfile_writeline("step 04.2 ::: local_collection_l ".$local_collection_l_str);

        $local_collection_l_role = $local_collection_l[0];
        $local_collection_l_role_str = var_export($local_collection_l_role, true);
        $logfile->logfile_writeline("step 04.2 ::: local_collection_l_role ".$local_collection_l_role_str);
        
        
        $logged_in_role_sig_sec = $local_collection_l_role["sig"];
        $logfile->logfile_writeline("step 04.2 ::: logged_in_role_sig_sec ".$logged_in_role_sig_sec);

        $logged_desc_role_tree = get_descendent_role_tree($logged_in_role_sig_sec, $local_collection_r, $logfile);

        $logged_desc_role_tree_str = var_export($logged_desc_role_tree, true);
        $logfile->logfile_writeline("step 04.2 ::: logged_desc_role_tree ".$logged_desc_role_tree_str);

        foreach ($logged_desc_role_tree as $r) {
            if (array_key_exists("editable",$r))
            {
                if($r["editable"] == true){
                    array_push($roles1,$r);
                }
            }
        }
        $roles1_str = var_export($roles1, true);
        $logfile->logfile_writeline("step 04.2 ::: roles1 ".$roles1_str);
        // step 04 :: end

        // step 05 :: start 
        $local_collection_u = $local_collection["u"];
        $local_collection_u_str = var_export($local_collection_u, true);
        $logfile->logfile_writeline("step 05 ::: local_collection_u ".$local_collection_u_str);

        foreach ($local_collection_u as $r) {
            $ur_sig = $r["sig"];
            $f = &get_role_ref_by_sig($ur_sig, $roles1, $logfile); 
            $f_str = var_export($f, true);
            $logfile->logfile_writeline("step 05 ::: f_str ".$f_str);
            if($f != NULL) {
                $f["checked"] = true;
            }

            $ur_desc_tree = get_descendent_role_tree($ur_sig, $local_collection_r, $logfile);

            $ur_desc_tree_str = var_export($ur_desc_tree, true);
            $logfile->logfile_writeline("step 05 ::: get_descendent_role_tree ".$ur_desc_tree_str);

            foreach ($ur_desc_tree as $d) {
                $f = &get_role_ref_by_sig($d["sig"], $roles1, $logfile); 
                if ($f != NULL) {
                    $f["disabled"] = true;
                    $f["checked"] = true;
                }
            }  
        }
        $roles1_str = var_export($roles1, true);
        $logfile->logfile_writeline("step 05 ::: roles1 ".$roles1_str);
        // step 05 :: end 
        
        // step 06 :: start 
        foreach ($local_collection_r as $entry) {
            if($entry["type"] == "rolecat") {
                $entry["has_child"] = false;
                array_push($roles1, $entry); // 
            }
        }
        $roles1_str = var_export($roles1, true);
        $logfile->logfile_writeline("step 6:: tree_roles_str :: ".$roles1_str);   
        // step 06 :: end 

        // step 07 :: start 

        // step 07.1 :: start 
        foreach ($roles1 as $entry) {
            if ($entry["type"] == "role") {
                mark_role_cat_chain_to_root_by_sig($roles1, $entry["sig"], $logfile);
            }
        }
            
        // step 07.1 :: end

        // step 07.2 :: start
        $tree_roles = array();
        
        foreach($roles1 as $entry) {
            if($entry["type"] == "role") {
                array_push($tree_roles, $entry);
            }
            if(($entry["type"] == "rolecat") && ($entry["has_child"] == true) ) {
                $logfile->logfile_writeline("entering tree_roles array");
                array_push($tree_roles, $entry);
            }
        }

        // step 07.2 :: end
        $tree_roles_str = var_export($tree_roles, true);
        $logfile->logfile_writeline("step 7 :: tree_roles_str ::  ".$tree_roles_str);
        // step 07 :: end


        $sec_map_str = dump_sec_map("assign_role_sig_map","\n");
        $logfile->logfile_writeline("step 7 :: sec_map_str ::  ".$sec_map_str);

        $root_role_sig_sec = $role_sec_map_inv[$root_role_sig];

        // step 8 :: start 
        //$sec_map_array =  array();
        $role_tree = get_role_tree($root_role_sig_sec, $tree_roles, $logfile);
        $role_tree_str = var_export($role_tree, true);
        $logfile->logfile_writeline("step 8 :: role_tree ::  ".$role_tree_str);
        /*
        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["text"] = "Please select an user name from the first fold";
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
        */
        // step 8 :: end

        // step 9 :: start 
        $arg["local_coll"] = $local_collection;
        $arg["char_set"] = $char_set;
        $arg["front_end_admin_cat_sig"] = $role_sec_map_inv["FRONTENDADMINCATEGORY"];
        $arg["sec_map"] = $role_sec_map_inv;
        $arg["logfile"] = $logfile;

        $mnfs = create_manifest($arg);
        $mnfs_str = var_export($mnfs, true);
        //$logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 01 :: ********** START **********");
        //$logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 01 ".$mnfs_str);
        //$logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 01 :: ********** END **********");

        $arg2["ob_manifest"] = $mnfs;
        $arg2["char_set"] = $char_set;
        $arg2["sec_map"] = $role_sec_map;
        $arg2["logfile"] = $logfile;

        /*
        $local_coll2 = restore_manifest($arg2);

        $local_coll2_str = var_export($local_coll2, true);
        $logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 02 :: ********** START **********");
        $logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 02 ".$local_coll2_str);
        $logfile->logfile_writeline("TEST MANIFEST FUNCTION :: Step 02 :: ********** END **********");
        */
        $data_arr = array();
        $data_arr["m"] = $mnfs;
        $data_arr["sp"] = 0;
        $data_arr["user_id"] = $user_id;
        $tree_root_obj = array();
        $tree_root_obj["children"] = array();
        $tree_root_obj["id"] = "PROP_ROOT";
        $tree_root_obj["data"] = $data_arr;
        $tree_root_obj["text"] = "Property";
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
        // step 9 :: end

        
    }
        
}
    
$logfile->logfile_close();
?>
