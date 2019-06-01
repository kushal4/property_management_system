<?php



function get_role_by_sig($role_sig, $role_arr, &$logfile){
    foreach ($role_arr as $value){  
        if($value["sig"] == $role_sig){
            $logfile->logfile_writeline("the role sig value mathced is::  ".print_r($value,true)); 
            return $value;
        }
    }
}

///https://stackoverflow.com/questions/7455627/return-by-reference-in-php
function &get_role_ref_by_sig($role_sig, &$role_arr, $logfile){
    //$logfile->logfile_writeline("getting inside get_role_ref_by_sig::". $role_sig);
    //echo ("getting inside get_role_ref_by_sig::". $role_sig);
    $ret_var_def = NULL;
    $a = &$ret_var_def;
    foreach ($role_arr as &$value){
        //$logfile->logfile_writeline("value sig::". $value["sig"]);
        //echo ("value sig::". $value["sig"]);
        if($value["sig"] == $role_sig){
            //$logfile->logfile_writeline("Found");
            //echo ("Found");
            return $value;
        }
    }
    return $a;
}

Function mark_role_cat_chain_to_root_by_sig(&$role_array, $sig, $logfile) {
    $logfile->logfile_writeline("mark_role_cat_chain_to_root_by_sig:: BEGIN::sig=".$sig);
    //$logfile->logfile_writeline("getting inside mark_role_cat_chain_to_root_by_sig");
    //find the role first
    
    //$roles1_str = var_export($role_array, true);
    //$logfile->logfile_writeline("the role_array is:: ".$roles1_str);

	$role = &get_role_ref_by_sig($sig, $role_array, $logfile);
	//Get the SIG of parent
    $rcat_sig = $role["parent"];
    $logfile->logfile_writeline("mark_role_cat_chain_to_root_by_sig:: rcat_sig=". $rcat_sig);
    
	while ($rcat_sig != "#") { //This is not the root node
		//get reference of the rcat role
		$rcat_role = &get_role_ref_by_sig($rcat_sig, $role_array, $logfile);
		//mark the role
		$rcat_role["has_child"] = true;
        $rcat_sig = $rcat_role["parent"];
        $logfile->logfile_writeline("mark_role_cat_chain_to_root_by_sig(inside While):: rcat_sig=". $rcat_sig);
    }
    $logfile->logfile_writeline("mark_role_cat_chain_to_root_by_sig:: END");
}


function get_role_child($role_sig, $role_arr, $logfile){
    $logfile->logfile_writeline("get_role_child:::: role_sig ".$role_sig);
    $child_arr = array();
    foreach ($role_arr as $value){  
        $name = $value["name"];
        $parent = $value["parent"];

        $logfile->logfile_writeline("the name is :: ".$name);
        $logfile->logfile_writeline("the parent is :: ".$parent);

        if($parent == $role_sig){
            $logfile->logfile_writeline("***parent and role_sig matched***".$parent);
            array_push($child_arr, $value); 
        }
    }
    return $child_arr;
}


function get_role_descendent($role_sig, $role_arr, $logfile){
    $logfile->logfile_writeline("get_role_descendent:::: role_sig ".$role_sig);

    $role_arr_str = var_export($role_arr, true);
    $logfile->logfile_writeline("get_role_descendent :: role_arr_str ".$role_arr_str);

    $child_arr = array();
    foreach ($role_arr as $value){  
        $name = $value["name"];
        //$parent = $value["parent"];
        if (array_key_exists("creator",$value)){
            $parent = $value["creator"];
            $logfile->logfile_writeline("get_role_descendent :: the name is :: ".$name);
            $logfile->logfile_writeline("get_role_descendent :: the parent is :: ".$parent);
            if($parent == $role_sig){
                $logfile->logfile_writeline("***parent and role_sig matched***");
                array_push($child_arr, $value); 
            }
        } 
    }
    return $child_arr;
}

function sec_local_collection(&$local_coll_ref, &$sec_map_ref, &$role_sec_map_inv_ref, $logfile){
    foreach($local_coll_ref["r"] as &$r_ref){ 
        $r_sig = $r_ref["sig"];
        $rand_id = gen_unique_sec_id($sec_map_ref);
        $sec_map_ref[$rand_id] = $r_sig;
        $r_ref["old_sig"] = $r_sig;
        $r_ref["sig"] = $rand_id;
    
        // logged role

        //$l_role = $local_coll_ref["l"];
        foreach ($local_coll_ref["l"] as &$l_role) {
            $f_ref = &get_role_ref_by_sig($r_sig, $local_coll_ref["l"], $logfile) ;
            if ($f_ref != NULL) {
                $f_ref["sig"] = $rand_id;
                $f_ref["old_sig"] = $r_sig;
            }
        }
        // scope role
        foreach ($local_coll_ref["s"] as &$s_role) {
            $f_ref = &get_role_ref_by_sig($r_sig, $local_coll_ref["s"], $logfile); //& is important
            if ($f_ref != NULL) {
                $f_ref["sig"] = $rand_id;
                $f_ref["old_sig"] = $r_sig;
            }
        }

        // user role
        foreach ($local_coll_ref["u"] as &$u_role) {
            $f_ref = &get_role_ref_by_sig($r_sig, $local_coll_ref["u"], $logfile); //& is important
            if ($f_ref != NULL) {
                $f_ref["sig"] = $rand_id;
                $f_ref["old_sig"] = $r_sig;
            }
        }
    }

    // step 2.1 :: start 
    foreach ($sec_map_ref as $k=>$v){
        $role_sec_map_inv_ref[$v] = $k;
    }
    // step 2.1 :: end

    // step 2.2 :: start 
    $local_coll_ref_str = var_export($local_coll_ref, true);
    $logfile->logfile_writeline("sec_local_collection :: step 2.2 :: local_coll_ref_str array :: before".$local_coll_ref_str);

    foreach($local_coll_ref as $key=>&$value){ 
        //$arr = array();
        foreach($value as &$node){
            if( array_key_exists("creator", $node)) {
                $node["creator"] = $role_sec_map_inv_ref[$node["creator"]];
            }
            if( array_key_exists("category", $node)) {
                $node["category"] = $role_sec_map_inv_ref[$node["category"]];
            }
            if( array_key_exists("parent", $node)) {
                if ($node["parent"] != "#") {
                    $node["parent"] = $role_sec_map_inv_ref[$node["parent"]];
                }
            }
        }
    }
    $local_coll_ref_str = var_export($local_coll_ref, true);
    $logfile->logfile_writeline("sec_local_collection :: step 2.2 :: local_coll_ref_str array :: after ".$local_coll_ref_str);
    // step 2.2 :: end
    
}


function unsec_local_collection(&$local_coll_ref, $sec_map_ref, $logfile){

    foreach($local_coll_ref as $key=>&$arr){ 
        foreach($arr as &$node){
            unset($node["old_sig"]);
            if( array_key_exists("sig", $node)) {
                $node["sig"] = $sec_map_ref[$node["sig"]];
            }
            if( array_key_exists("creator", $node)) {
                $node["creator"] = $sec_map_ref[$node["creator"]];
            }
            if( array_key_exists("category", $node)) {
                $node["category"] = $sec_map_ref[$node["category"]];
            }
            if( array_key_exists("parent", $node)) {
                if ($node["parent"] != "#") {
                    $node["parent"] = $sec_map_ref[$node["parent"]];
                }
            }
        }
    }
}


function get_descendent_role_tree($role_sig, $role_array, $logfile){
    $logfile->logfile_writeline("get_descendent:::: role_sig ".$role_sig);
    
    $role_array_str = var_export($role_array, true);
    $logfile->logfile_writeline("get_descendent_role_tree :: role_array".$role_array_str);

    $desc_array = array();
    $children = get_role_descendent($role_sig, $role_array, $logfile);

    $children_str = var_export($children, true);
    $logfile->logfile_writeline("get_descendent_role_tree :: children array ".$children_str);

    foreach($children as $child){
        array_push($desc_array, $child);

        //$desc_array_str = var_export($desc_array, true);
        //$logfile->logfile_writeline(" desc_array :: 01 ".$desc_array_str);
        $child_sig = $child["sig"];

        $logfile->logfile_writeline("the child sig is ::".$child_sig);

        $descendents = get_descendent_role_tree($child_sig, $role_array, $logfile);

        $logfile->logfile_writeline(" descendents :: START");
        $descendents_str = var_export($descendents, true);
        $logfile->logfile_writeline(" descendents :: 01 ".$descendents_str);
        $logfile->logfile_writeline(" descendents :: END");

        foreach($descendents as $descendent) {
            array_push($desc_array, $descendent);
        }

        //$desc_array_str = var_export($desc_array, true);
        //$logfile->logfile_writeline(" desc_array :: 02 ".$desc_array_str);
    }

    //$desc_array_str = var_export($desc_array, true);
    //$logfile->logfile_writeline(" desc_array :: 03 ".$desc_array_str);
    return $desc_array;
    
}

$actl_urls = new stdClass();
$actl_urls->actlCreateClientCurlURL = $actl_ip."/$baseurl/actlApi.aspx/create_client";
$actl_urls->actlGetRoleDetsCurlURL = $actl_ip."/$baseurl/actlApi.aspx/get_role_details";
$actl_urls->actlGetPermCurlURL = $actl_ip."/$baseurl/actlApi.aspx/get_perm";
$actl_urls->actlGetRoleTreeCurlURL = $actl_ip."/$baseurl/actlApi.aspx/get_role_tree";
$actl_urls->actlCreateRoleCatCurlURL = $actl_ip."/$baseurl/actlApi.aspx/role_man";
$actl_urls->actlSetPermCurlURL = $actl_ip."/$baseurl/actlApi.aspx/set_perm";
$actl_urls->actlRoleTreeByPermCurlURL = $actl_ip."/$baseurl/actlApi.aspx/get_role_tree_by_perm";




function create_local_collection($arg){
    $logfile = $arg["logfile"];
    $logfile->logfile_writeline("getting inside create_local_collection");

    $prop_id = $arg["prop_id"];
    $logged_in_role_sig = $arg["logged_role"];
    $conn = $arg["dbconn"];
    $projectID = $arg["proj_key"];
    $ctx_id = $arg["ctx_id"];
    $logfile->logfile_writeline("ctx_id is :: ".$ctx_id);
    $actl_urls = $arg["actl_urls"];

    $actlGetRoleDetsCurlURL = $actl_urls->actlGetRoleDetsCurlURL;
    $actlGetRoleTreeCurlURL = $actl_urls->actlGetRoleTreeCurlURL;

    $logfile->logfile_writeline("actlGetRoleDetsCurlURL".$actlGetRoleDetsCurlURL);
    
    // STEP 01 :: START
    $scope_role_details_tree = array();
    $assigned_role_details_tree = array();
    $logged_role_details_tree = array();

    $scope_roles = array();
    $logfile->logfile_writeline("the property ID is:: ".$prop_id);
    $logfile->logfile_writeline("the logged_in_role_sig is :: ". $logged_in_role_sig);
    $check_role_scp = "SELECT scope_role_sig FROM acc_man_role_scope WHERE prop_id = ? AND target_role_sig = ?";
    $role_scp_result_temp = $conn->prepare($check_role_scp);
    if($role_scp_result_temp){
        $logfile->logfile_writeline("prepare is successful");
        $bind = $role_scp_result_temp->bind_param("is",$prop_id, $logged_in_role_sig);
        if($bind){
            $logfile->logfile_writeline("bind is successful");
            $execute = $role_scp_result_temp->execute();
            if($execute){
                $logfile->logfile_writeline("execute is successful");
                $check_role_scp_result = $role_scp_result_temp->get_result();
                $scope_roles_array = $check_role_scp_result->fetch_all(); // scope_role_array
                $num_rows = mysqli_num_rows($check_role_scp_result);
                $logfile->logfile_writeline("refresh_scope_list -------- here step 02");
                $logfile->logfile_writeline("the number of row is".$num_rows);
            }else{
                $logfile->logfile_writeline("execute is failure");
            }  
        }else{
            $logfile->logfile_writeline("bind is failure");
        } 
    }else{
        $logfile->logfile_writeline("prepare is failure");
    }
    $logfile->logfile_writeline("database selection from scope_role_sig ends here");

    if($num_rows == 0){
        $logfile->logfile_writeline("getting inside num_rows = 0");
    }else{
        $logfile->logfile_writeline("getting inside num_rows != 0");
        $sig_arr_roles = array();
        foreach ($scope_roles_array as $value) {
            $role_scope_db_sig = $value[0];
            $logfile->logfile_writeline("role_scope_db_sig=".$role_scope_db_sig);
            array_push($sig_arr_roles,$role_scope_db_sig);
        }
        $logfile->logfile_writeline("STEP 01 :: I am here :: before API call");
        $hierarchy_true = "true";
        $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "sig_arr"=>$sig_arr_roles, "hierarchy"=>$hierarchy_true));
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
        $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL
        $scope_role_details = json_decode($actlRetObj, true);
        $jsonDataSTR = $scope_role_details['d']."\n";
        $data_json_decode = json_decode($jsonDataSTR, true);
        $perm_array = $data_json_decode["p"];
        $scope_role_details_roles = $perm_array["roles"];
        $scope_role_details_tree = $perm_array["Tree"];

        $logfile->logfile_writeline("STEP 01 :: I am here :: after API call");

        $scope_role_details_tree_str = var_export($scope_role_details_tree, true);
        $logfile->logfile_writeline("STEP 01 :: scope_role_details_tree_str :: ".$scope_role_details_tree_str);

        foreach ($scope_role_details_roles as $r) {
            if($r["type"] == "role") {
                array_push($scope_roles, $r);
            }
        }
    }   
    // STEP 01 :: END 

    // STEP 02 :: START

    $user_roles = array();

    $user_role_sql = "SELECT * FROM context_role WHERE ctx_id = ?";
    $user_role_stmt = $conn->prepare($user_role_sql);
    $user_role_stmt->bind_param("i",$ctx_id);
    $user_role_stmt->execute();
    $user_role_result = $user_role_stmt->get_result();
    $user_role_row = $user_role_result->fetch_all();
    $number_of_row = mysqli_num_rows($user_role_result);
    $logfile->logfile_writeline("local_collection :: step 02 :: the number of row is:: ".$number_of_row); 

    $has_user_role = true;
    if($number_of_row == 0){
        $has_user_role = false;
    }else{
        $assigned_role_array = array();
        foreach ($user_role_row as $value){  
            $role_sig = $value["1"];
            array_push($assigned_role_array,$role_sig); 
        }

        $hierarchy_true = "true";
    
        $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "sig_arr"=>$assigned_role_array, "hierarchy"=>$hierarchy_true));
        $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
        $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL
        $assigned_role_details = json_decode($actlRetObj, true);
        $jsonDataSTR = $assigned_role_details['d']."\n";
        $data_json_decode = json_decode($jsonDataSTR, true);
        $perm_array = $data_json_decode["p"];
        $assigned_role_details_role = $perm_array["roles"];
        $assigned_role_details_tree = $perm_array["Tree"];

        $assigned_role_details_role_str = var_export($assigned_role_details_role, true);
        $logfile->logfile_writeline("actl_lib :: local_creation :: STEP 02 :: assigned_role_details_role_str ".$assigned_role_details_role_str);

        $assigned_role_details_tree_str = var_export($assigned_role_details_tree, true);
        $logfile->logfile_writeline("actl_lib :: local_creation :: STEP 02 :: assigned_role_details_tree_str ".$assigned_role_details_tree_str);

        foreach ($assigned_role_details_role as $r) {
            if($r["type"] == "role") {
                array_push($user_roles, $r);
            }
        }
    }

    $user_roles_str = var_export($user_roles, true);
    $logfile->logfile_writeline("actl_lib :: local_creation :: STEP 02 :: user_roles ".$user_roles_str);
    // STEP 02 :: END 

    // STEP 03 :: START 
    $logfile->logfile_writeline("Step 04 ::: BEGIN "); 
    $logged_in_role_arr = array();
    array_push($logged_in_role_arr,$logged_in_role_sig);

    $logged_roles = array();
    $hierarchy_true = "true";

    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "sig_arr"=>$logged_in_role_arr, "hierarchy"=>$hierarchy_true));
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL
    $logged_role_details = json_decode($actlRetObj, true);
    $jsonDataSTR = $logged_role_details['d']."\n";
    $data_json_decode = json_decode($jsonDataSTR, true);
    $perm_array = $data_json_decode["p"];
    $logged_role_details_role = $perm_array["roles"];
    $logged_role_details_tree = $perm_array["Tree"];

    $logged_role_details_tree_str = var_export($logged_role_details_tree, true);
    $logfile->logfile_writeline("actl_lib :: local_creation :: STEP 02 :: logged_role_details_tree_str ".$logged_role_details_tree_str);

    foreach ($logged_role_details_role as $r) {
        if($r["type"] == "role") {
            array_push($logged_roles, $r);
        }
    }
    // STEP 03 :: END

    // STEP 04 :: START 
    $local_role_coll_hash = array();

    foreach($scope_role_details_tree as $key=>$value){
        foreach($value as $a) {
            if (array_key_exists($a["sig"],$local_role_coll_hash)){ 
            }else{
                $a["creator"]= $a["parent"];
                $a["parent"]= $a["category"];
                $local_role_coll_hash[$a["sig"]] = $a;
            }
        }
    }
    foreach($assigned_role_details_tree as $key=>$value){
        foreach($value as $a) {
            if (array_key_exists($a["sig"],$local_role_coll_hash)){
            }else{
                $a["creator"]= $a["parent"];
                $a["parent"]= $a["category"];
                $local_role_coll_hash[$a["sig"]] = $a;
            }
        }
    }
    foreach($logged_role_details_tree as $key=>$value){
        foreach($value as $a) {
            if (array_key_exists($a["sig"],$local_role_coll_hash)){
            }else{
                $a["creator"]= $a["parent"];
                $a["parent"]= $a["category"];
                $local_role_coll_hash[$a["sig"]] = $a;
            }
        }
    }

    foreach($scope_roles as $value){
        if (array_key_exists($value["sig"],$local_role_coll_hash)){
        }else{
            $local_role_coll_hash[$value["sig"]] = $value;
        }
    }
    foreach($user_roles as $value){
        if (array_key_exists($value["sig"],$local_role_coll_hash)){
        }else{
            $local_role_coll_hash[$value["sig"]] = $value;
        }
    }
    foreach($logged_roles as $value){
        if (array_key_exists($value["sig"],$local_role_coll_hash)){
        }else{
            $local_role_coll_hash[$value["sig"]] = $value;
        }
    }

    

    $local_role_coll_hash_str = var_export($local_role_coll_hash, true);
    $logfile->logfile_writeline("step 04 ::: local_role_coll_hash_str :: ".$local_role_coll_hash_str);
    // STEP 04 :: END

    // STEP 05 :: START 
    $local_role_coll_arr = array();
    foreach($local_role_coll_hash as $key=>$value){
            $value["disabled"] = false;
            $value["checked"] = false;
            array_push($local_role_coll_arr, $value);
    }
    // STEP 05 :: END 

    // STEP 06 :: START 
    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "role_sig"=>$logged_in_role_sig));
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $actlRetObj = CurlSendPostJson($actlGetRoleTreeCurlURL, $data_str, $logfile); //what we get from the ACTL
    $decodedJson = json_decode($actlRetObj, true);
    $jsonDataSTR = $decodedJson['d']."\n";
    $data_json_decode = json_decode($jsonDataSTR, true);
    $perm_array = $data_json_decode["p"];
    $_SESSION["perm_obj"] = $perm_array;
    $api_logged_role_tree = $perm_array["roles"];

    foreach ($api_logged_role_tree as $r){  
        if (array_key_exists("editable",$r))
        {
            if($r["editable"] == true){
                $f = &get_role_ref_by_sig($r["sig"], $local_role_coll_arr, $logfile); // & is important
                $f["editable"] = true;
            }
        }
    }
    // STEP 06 :: END

    // STEP 07 :: START 
    foreach ($api_logged_role_tree as $entry){  
        if ($entry["type"] == "rolecat") {
            $entry["has_child"] = false;
            array_push($local_role_coll_arr, $entry);
        }
    }
    // STEP 07 :: END

    // STEP 08 :: START 
    
    $local_role_coll_arr_str = var_export($local_role_coll_arr, true);
    $logfile->logfile_writeline("step 8 :: local_role_coll_arr is ::: ".$local_role_coll_arr_str);

    $local_role_collection = array();

    $local_role_collection["r"] = $local_role_coll_arr;
    $local_role_collection["s"] = $scope_roles;
    $local_role_collection["l"] = $logged_roles;
    $local_role_collection["u"] = $user_roles;

    return $local_role_collection;

    // STEP 08 :: END
}

function create_manifest($arg){
    // STEP 01 :: start
    $local_coll = $arg["local_coll"];
    $char_set = $arg["char_set"];
    $front_end_admin_cat_sig = $arg["front_end_admin_cat_sig"];
    $sec_map = $arg["sec_map"];
    $logfile = $arg["logfile"];
    $logfile->logfile_writeline("Getting inside create_manifest");
    // STEP 01 :: end

    // STEP 02 :: start
    $fld_map = array();
    $fld = "a";
    // STEP 02.1 :: start
    $l_role = $local_coll["l"];
    $l_role_str = var_export($l_role, true);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 02.1 :: l_role array ".$l_role_str);

    foreach($l_role as $k){ 
        $l_role_keys = array_keys($k);
        $l_role_keys_str = var_export($l_role_keys, true);
        $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 02.1 :: l_role_keys ".$l_role_keys_str);
    }

    foreach($l_role_keys as $k){  
        $logfile->logfile_writeline("the keys are:: ".$k);
        if(array_key_exists($k, $fld_map)){
        }else{
            $fld_map[$k] = $fld;
            $fld++;
        }
    }

    $fld_map_str = var_export($fld_map, true);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 02.1 :: fld_map ".$fld_map_str);
    // STEP 02.1 :: end

    // STEP 02.2 :: start 
    $logfile->logfile_writeline("FUNCTION : create_manifest :: front_end_admin_cat_sig".$front_end_admin_cat_sig);
    $c_rcat = get_role_by_sig($front_end_admin_cat_sig, $local_coll["r"], $logfile);
    $c_rcat_str = var_export($c_rcat, true);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 02.2 :: c_rcat".$c_rcat_str);
    $c_rcat_keys = array_keys($c_rcat);
    foreach($c_rcat_keys as $k){ 
        $logfile->logfile_writeline("FUNCTION : create_manifest :: c_rcat key".$k);
        if(array_key_exists($k, $fld_map)){

        }else{
            $fld_map[$k] = $fld;
            $fld++;
        }
    }
    $fld_map_str = var_export($fld_map, true);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 02.2 :: fld_map ".$fld_map_str);
    // STEP 02.2 :: end

   // STEP 02.3 :: start 
   $loc_col_r = $local_coll["r"];
   $loc_col_r_str = var_export($loc_col_r, true);
   $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 02.2 :: loc_col_r array ".$loc_col_r_str);

   $r_rcat = $local_coll["r"][0];
   $c_rcat_str = var_export($r_rcat, true);
   $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 02.2 :: r_rcat array ".$c_rcat_str);
   $r_rcat_keys = array_keys($r_rcat);
   foreach($r_rcat_keys as $k){ 
       if(array_key_exists($k, $fld_map)){

       }else{
           $fld_map[$k] = $fld;
           $fld++;
       }
   }
   $fld_map_str = var_export($fld_map, true);
   $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 02.3 :: fld_map ".$fld_map_str);
   // STEP 02.3 :: end
    // STEP 02 :: end 

    // STEP 03 :: start
    
    $mini_local_coll = array();
    foreach($local_coll as $key=>$value){ 
        $arr = array();
        foreach($value as $node){
            //$node["sig"] = $node["sec_sig"];
            unset($node["old_sig"]);
            /*
            if( array_key_exists("creator", $node)) {
                $node["creator"] = $sec_map[$node["creator"]];
            }
            if( array_key_exists("category", $node)) {
                $node["category"] = $sec_map[$node["category"]];
            }
            if( array_key_exists("parent", $node)) {
                $node["parent"] = $sec_map[$node["parent"]];
            }
            */

            $node_mapped = array();
            foreach ($node as $k=>$v) {
                $mk = $fld_map[$k];
                $node_mapped[$mk] = $v;
            }
            array_push($arr, $node_mapped);  

        }
        $mini_local_coll[$key] = $arr;

    }
    

    $mini_local_coll_str = var_export($mini_local_coll, true);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 03 :: mini_local_coll".$mini_local_coll_str);
    // STEP 03 :: end

    // STEP 04 :: start
    $mini_local_coll_jenc_str = json_encode($mini_local_coll);
    // STEP 04.1 :: start 
    $ob_key = str_shuffle($char_set);

    $ob_mini_local_coll_jenc_str = obfuscate($mini_local_coll_jenc_str, $char_set, $ob_key);
    // STEP 04.1 :: end
    //$logfile->logfile_writeline("FUNCTION : create_manifest :: Step 04 :: ob_mini_local_coll_jenc_str".$ob_mini_local_coll_jenc_str);
    // STEP 04 :: end

    // STEP 05 :: start
    $fld_map_jenc_str = json_encode($fld_map);
    //$ob_fld_map_jenc_str = obfuscate($fld_map_jenc_str, $char_set, $ob_key);
    //$logfile->logfile_writeline("FUNCTION : create_manifest :: Step 05 :: fld_map".$fld_map);
    $fld_map_str = var_export($fld_map, true);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 05 :: fld_map".$fld_map_str);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 05 :: fld_map_jenc_str".$fld_map_jenc_str);
    // STEP 05.1 :: start
    // STEP 05.1 :: end
    $ob_fld_map_jenc_str = obfuscate($fld_map_jenc_str, $char_set, $ob_key);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 05.1 :: ob_fld_map_jenc_str".$ob_fld_map_jenc_str);
    // STEP 05 :: end

    // STEP 06 :: start
    $ob_fld_map_jenc_str_len = strlen($ob_fld_map_jenc_str);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 06 :: ob_fld_map_jenc_str_len".$ob_fld_map_jenc_str_len);
    // STEP 06.1 :: start
    $ob_fld_map_jenc_str_len_str = (string)$ob_fld_map_jenc_str_len;
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 06.1 :: ob_fld_map_jenc_str_len_str: ".$ob_fld_map_jenc_str_len_str);
    $ob_fld_map_jenc_str_len_num_digit = strlen($ob_fld_map_jenc_str_len_str);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 06.1 :: ob_fld_map_jenc_str_len_num_digit: ".$ob_fld_map_jenc_str_len_num_digit);
    $num_append_zero = 8 - $ob_fld_map_jenc_str_len_num_digit;
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 06.1 :: num_append_zero: ".$num_append_zero);
    $fld_map_padded_len = str_repeat("0", $num_append_zero).$ob_fld_map_jenc_str_len_str;
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 06.1 :: fld_map_padded_len: ".$fld_map_padded_len);

    // STEP 06.1 :: end

    // STEP 06.2 :: start
    // STEP 06.2 :: end
    $ob_fld_map_padded_len = obfuscate($fld_map_padded_len, $char_set, $ob_key);
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 06.2 :: ob_fld_map_padded_len: ".$ob_fld_map_padded_len);
    // STEP 06 :: end

    // STEP 07 :: start
    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 7 :: ob_key: ".$ob_key);
    $ob_manifest = $ob_key.$ob_fld_map_padded_len.$ob_fld_map_jenc_str.$ob_mini_local_coll_jenc_str;

    $logfile->logfile_writeline("FUNCTION : create_manifest :: Step 7 :: ob_manifest: ".$ob_manifest);
    // STEP 07 :: end
    $logfile->logfile_writeline("******************************** end of create_manifest ********************************");
    return $ob_manifest;
}

function restore_manifest($arg){
    // STEP 01 :: start
    $ob_manifest = $arg["ob_manifest"];
    $char_set = $arg["char_set"];
    $sec_map = $arg["sec_map"];
    $logfile = $arg["logfile"];
    
    $logfile->logfile_writeline(__FILE__."---Dumping sec_map MAP: Begin");
    foreach($sec_map as $key => $value)
        {
            $logfile->logfile_writeline($key." : ".$value);
        }
    $logfile->logfile_writeline(__FILE__."---Dumping sec_map MAP: End");

    /*
    $assign_role_sig_map_inv = array();
    foreach ($sec_map as $k=>$v){
        $assign_role_sig_map_inv[$v] = $k;
    }*/

    
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 01 :: length of  char_set".strlen($char_set));

    // STEP 01.1 :: start 
    $ob_key = substr($ob_manifest, 0, strlen($char_set));
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 01.1 :: ob_key: ".$ob_key);
    $ob_manifest = substr($ob_manifest, strlen($char_set));
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 01.1 :: ob_manifest: ".$ob_manifest);
    // STEP 01.1 :: end
    // STEP 01 :: end

    // STEP 02 :: start
    // STEP 02.1 :: start
    $ob_fld_map_len = substr($ob_manifest, 0, 8);
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 02.1 :: ob_fld_map_len: ".$ob_fld_map_len);
    $ob_manifest = substr($ob_manifest, 8);
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 02.1 :: ob_manifest: ".$ob_manifest);
    // STEP 02.1 :: end

    // STEP 02.2 :: start
    $fld_map_len_padded = deobfuscate($ob_fld_map_len, $char_set, $ob_key);
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 02.2 :: fld_map_len_padded: ".$fld_map_len_padded);
    $fld_map_len = (int)$fld_map_len_padded;
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 02.2 :: fld_map_len: ".$fld_map_len);
    // STEP 02.2 :: end
    // STEP 02 :: end 

    // STEP 03 :: start
    // STEP 03.1 :: start
    $ob_fld_map = substr($ob_manifest, 0, $fld_map_len);
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 03.1 :: ob_fld_map: ".$ob_fld_map);
    $ob_manifest = substr($ob_manifest, $fld_map_len);
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 03.1 :: ob_manifest: ".$ob_manifest);
    // STEP 03.1 :: end

    // STEP 03.2 :: start
    $fld_map_enc_json = deobfuscate($ob_fld_map, $char_set, $ob_key);
    
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 03.2 :: fld_map_enc_json: ".$fld_map_enc_json);
    // STEP 03.2 :: end

    // STEP 03.3 :: start
    $fld_map = json_decode($fld_map_enc_json, "true"); //get decoded as array
    $fld_map_str = var_export($fld_map, true);
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 03.3 :: fld_map:".$fld_map_str);
    //$logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 03.3 :: fld_map: ".$fld_map);
    // STEP 03.3 :: end

    // STEP 03.4 :: start
    $fld_map_inv = array();
    foreach ($fld_map as $key=>$value) {
        $fld_map_inv[$value] = $key;
    }
    // STEP 03.4 :: end 
    // STEP 03 :: end

    // STEP 04 :: start
    // STEP 04.1 :: start
    $ob_mini_local_coll_jenc_str = deobfuscate($ob_manifest, $char_set, $ob_key);
    // STEP 04.1 :: end

    // STEP 04.2 :: start
    $mini_local_coll = json_decode($ob_mini_local_coll_jenc_str,"true");  //get decoded as array
    $mini_local_coll_str = var_export($mini_local_coll, true);
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 04.2 :: mini_local_coll_str:".$mini_local_coll_str);
    // STEP 04.2 :: end
    // STEP 04 :: end

    // STEP 05 :: start
    $local_coll = array();
    foreach($mini_local_coll as $key=>$value){
        $arr = array();
        foreach($value as $node){
            $node_mapped = array();
            foreach($node as $k=>$v) {
                $mk = $fld_map_inv[$k];
                $node_mapped[$mk] = $v;
            }
            
            $node_mapped["old_sig"] = $sec_map[$node_mapped["sig"]];
            array_push($arr, $node_mapped);   
        }
        $local_coll[$key] = $arr;
    }
    $local_coll_str = var_export($local_coll, true);
    $logfile->logfile_writeline("FUNCTION : restore_manifest :: Step 04.2 :: local_coll_str:".$local_coll_str);
    // STEP 05 :: end
    return $local_coll;
}

//This function converts a role array into an associative array
function conv_to_role_assoc($role_arr, $logfile) {
    $role_arr_str = var_export($role_arr, true);
    $logfile->logfile_writeline("conv_to_role_assoc :: role_arr_str is ::".$role_arr_str);
	$role_assoc = array();
	foreach ($role_arr as $k=>$v){
        $v_str = var_export($v, true);
        $logfile->logfile_writeline("conv_to_role_assoc :: v is ::".$v_str);
        foreach ($v as $val){
            $logfile->logfile_writeline("conv_to_role_assoc :: the sig is :: ".$val["sig"] );
		    $role_assoc[$val["sig"]] = $val;
        }
        
	}
	return $role_assoc;
}

//This function returns true if the role_sig is child of parent_sig
function is_descendant($role_arr_assoc, $role_sig, $parent_sig, $logfile){
    $logfile->logfile_writeline("getting inside this :: is_descendant");
    $logfile->logfile_writeline("parent_sig is :: ".$parent_sig);
    $logfile->logfile_writeline("role_sig is :: ".$role_sig);
	$ret = false;
    $curr_role_sig = $role_sig;
    $count = 0;
	while (($ret == false) && ( $count < 15)){
        $count ++;
        $role = $role_arr_assoc[$curr_role_sig];
        $role_str = var_export($role, true);
        $logfile->logfile_writeline("the role_str is :: ".$role_str);
		if($role["parent"] == $parent_sig){
			$ret = true;
        } 
        else {
            $curr_role_sig = $role["parent"];
        }

        if($curr_role_sig == "FRONTENDSUPERADMIN"){
            break;
        }
    }
    $logfile->logfile_writeline("the return value is".$ret);
	return $ret;
}

//This function returns true is a role is descendant of “Resident” role
Function is_resident($arg) {
    $projectID = $arg["proj_key"];
    $prop_id = $arg["prop_id"];
    $logfile = $arg["logfile"];
    $actl_urls = $arg["actl_urls"];
    $actlGetRoleDetsCurlURL = $actl_urls->actlGetRoleDetsCurlURL;
    $role_sig = $arg["role_sig"];

    $logfile->logfile_writeline("getting inside is_resident");

    $sig_arr_roles = array();
    array_push($sig_arr_roles,"FRONTENDSUPERADMIN");

    $hierarchy_true = "true";
    $data=array("param"=>array("key"=>$projectID, "client_id"=>$prop_id, "sig_arr"=>$sig_arr_roles, "hierarchy"=>$hierarchy_true));
    $data_str = json_encode($data,JSON_UNESCAPED_SLASHES);
    $actlRetObj = CurlSendPostJson($actlGetRoleDetsCurlURL, $data_str, $logfile); //what we get from the ACTL
    $role_details = json_decode($actlRetObj, true);
    $jsonDataSTR = $role_details['d']."\n";
    $data_json_decode = json_decode($jsonDataSTR, true);
    $perm_array = $data_json_decode["p"];
    $role_details_tree = $perm_array["Tree"];
    $role_details_tree_str = var_export($role_details_tree, true);
    $logfile->logfile_writeline("is_resident :: role_details_tree_str ::::: ".$role_details_tree_str);

    $role_tree_assoc = conv_to_role_assoc($role_details_tree, $logfile);
    $role_tree_assoc_str = var_export($role_tree_assoc, true);
    $logfile->logfile_writeline("is_resident :: role_tree_assoc_str ::::: ".$role_tree_assoc_str);

    $desc = is_descendant($role_tree_assoc, $role_sig, "RESIDENTS", $logfile);
    

    $desc_str = var_export($desc, true);
    $logfile->logfile_writeline("is_resident :: desc_str ::::: ".$desc_str);

    return $desc;
}



?>
