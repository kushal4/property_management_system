<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../eecee_include.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'sec.php';

include $eecee_php_lib_path.'eecee_lib.php';
include $sense_common_php_lib_path.'actl_lib.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

$conn = new \mysqli($server_name, $user_name, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
$html.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
$html.="<html>";
$html.="<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">";
$html.="<body>";
$html.="    <div id=\"main_cont\">";
$html.="    </div>";
$html.="</body>";
$html.="</html>";


$doc = createDomDoc($html);
$main_container =$doc->getElementById('main_cont');

$unit_type_id = $SENSESSION->get_val("unit_type_id");
//echo $unit_type_id;
$op = $_POST["op"];
$logfile->logfile_writeline("the op is :: ".$op);

if($op == "l"){
    $logfile->logfile_writeline("here ");
    //$unit_type_name = $_POST["unit_type_name"];
    $unit_type_edit = insertElement($main_container,"div",'{"id":"unit_type_edit", "class":"unit_type_edit_style"}'," ");
    $unit_type_edit_pg_sec = insertPageSection  ($unit_type_edit, '', '{"class":"container_body_pg_sec_style"}');

    $add_unit_tbl_parent = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_tbl_parent", "class":"add_unit_tbl_parent_style_sess"}'," ");

    $unit_type_name_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"unit_type_name_cont", "class":"unit_type_name_cont_style"}'," ");
    $unit_type_name_span = insertElement($unit_type_name_cont,"span",'{"id":"unit_type_name_span", "class":"unit_type_name_span_style"}',"Unit Type Name: ");
    $txtbox_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"txtbox_cont", "class":"txtbox_cont_style"}'," ");
    //$unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"$unit_type_name\"}";
    $unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"\"}";
    $unit_type_name_txtbox = insertElement($txtbox_cont,"input","$unit_type_name_prop","");

    $edit_btn_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"save_btn_cont", "class":"edit_btn_cont_style", "op":"s"}',"");
    $edit_btn_span = insertElement($edit_btn_cont,"span",'{"id":"edit_btn_span", "class":"edit_btn_span_style"}',"Save");
}

else if($op == "s"){
    $unit_type_name = $_POST["unit_type_name"];
    $logfile->logfile_writeline("the unit_type_name is:: ".$unit_type_name);

    $prop_id = $SENSESSION->get_val("prop_id");
    $logfile->logfile_writeline("the prop id is:: ".$prop_id);

    $unit_type_id = $SENSESSION->get_val("unit_type_id");
    if($unit_type_id != null){
        $sql_prop_topo_update = "UPDATE unit_types SET name=? WHERE id=?";
        $sql_prop_topo_update_stmt=$conn->prepare($sql_prop_topo_update);
        $sql_prop_topo_update_stmt->bind_param("si", $unit_type_name, $unit_type_id);
        $sql_prop_topo_update_stmt->execute();

    }else{
        $unit_types_sql = "INSERT INTO unit_types(prop_id, name) VALUES (?, ?)";
        $unit_types_temp = $conn->prepare($unit_types_sql);
        $unit_types_temp->bind_param("is",$prop_id, $unit_type_name);
        $unit_types_temp->execute();
        $last_id = $conn->insert_id;
        $logfile->logfile_writeline("the last inserted id is ::".$last_id); 
        $SENSESSION->token("unit_type_id", $last_id);
    }
     
    $unit_type_edit = insertElement($main_container,"div",'{"id":"unit_type_edit", "class":"unit_type_edit_style"}'," ");
    $unit_type_edit_pg_sec = insertPageSection  ($unit_type_edit, '', '{"class":"container_body_pg_sec_style"}');

    $add_unit_tbl_parent = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_tbl_parent", "class":"add_unit_tbl_parent_style_sess"}'," ");

    $unit_type_name_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"unit_type_name_cont", "class":"unit_type_name_cont_style"}'," ");
    $unit_type_name_span = insertElement($unit_type_name_cont,"span",'{"id":"unit_type_name_span", "class":"unit_type_name_span_style"}',"Unit Type Name: ");
    $txtbox_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"txtbox_cont", "class":"txtbox_cont_style"}'," ");
    //$unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"$unit_type_name\"}";
    //$unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"\"}";
    //$unit_type_name_txtbox = insertElement($txtbox_cont,"input","$unit_type_name_prop","");
    $unit_type_name_txtbox = insertElement($txtbox_cont,"span",'{"id":"unit_type_name_span_val", "class":"unit_type_name_txtbox_style"}',$unit_type_name);

    $edit_btn_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"save_btn_cont", "class":"edit_btn_cont_style", "op":"e"}',"");
    $edit_btn_span = insertElement($edit_btn_cont,"span",'{"id":"edit_btn_span", "class":"edit_btn_span_style"}',"Edit");

    /*
    $add_unit_feature_btn_div = insertElement($main_container,"div",'{"id":"add_unit_feature_btn_div", "class":"add_unit_feature_btn_div_style"}'," ");
    $add_unit_feature_btn_span = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_btn_span", "class":"add_unit_feature_btn_span_style"}',"Add Unit Featuress");
    $add_unit_feature_plus_cont = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_plus_cont", "class":"add_unit_feature_plus_cont_style"}'," ");
    $add_unit_feature_plus = insertElement($add_unit_feature_plus_cont,"div",'{"id":"add_unit_feature_plus", "class":"add_unit_feature_plus_style"}',"+");
    */
    
}

else if($op == "e"){
    $unit_type_id = $SENSESSION->get_val("unit_type_id");
    if($unit_type_id != null){
        $logfile->logfile_writeline("inside op = e ");
        $unit_type_name = $_POST["unit_type_name"];
        $logfile->logfile_writeline("the unit_type_name is:: ".$unit_type_name);

        //$unitTypeid = $SENSESSION->get_val("unit_type_id");
        $unit_types_sql = "SELECT * FROM unit_types WHERE id = ?";
        $unit_types_temp = $conn->prepare($unit_types_sql);
        $unit_types_temp->bind_param("i",$unit_type_id);
        $unit_types_temp->execute();
        $unit_types_result = $unit_types_temp->get_result();
        $unit_types_fetch_assoc = $unit_types_result->fetch_assoc();

        $unit_types_fetch_assoc_str = var_export($unit_types_fetch_assoc, true);
        $logfile->logfile_writeline("The unit_types_fetch_assoc_str is :: ".$unit_types_fetch_assoc_str);
        $unitTypeName = $unit_types_fetch_assoc["name"];
        $logfile->logfile_writeline("the unitTypeName is:: ".$unitTypeName);


        $unit_type_edit = insertElement($main_container,"div",'{"id":"unit_type_edit", "class":"unit_type_edit_style"}'," ");
        $unit_type_edit_pg_sec = insertPageSection  ($unit_type_edit, '', '{"class":"container_body_pg_sec_style"}');

        $add_unit_tbl_parent = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_tbl_parent", "class":"add_unit_tbl_parent_style_sess"}'," ");

        $unit_type_name_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"unit_type_name_cont", "class":"unit_type_name_cont_style"}'," ");
        $unit_type_name_span = insertElement($unit_type_name_cont,"span",'{"id":"unit_type_name_span", "class":"unit_type_name_span_style"}',"Unit Type Name: ");
        $txtbox_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"txtbox_cont", "class":"txtbox_cont_style"}'," ");
        $unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"$unitTypeName\"}";
        //$unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"\"}";
        $unit_type_name_txtbox = insertElement($txtbox_cont,"input","$unit_type_name_prop","");

        $edit_btn_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"save_btn_cont", "class":"edit_btn_cont_style", "op":"s"}',"");
        $edit_btn_span = insertElement($edit_btn_cont,"span",'{"id":"edit_btn_span", "class":"edit_btn_span_style"}',"Save");

        $add_unit_feature_btn_div = insertElement($main_container,"div",'{"id":"add_unit_feature_btn_div", "class":"add_unit_feature_btn_div_style"}'," ");
        $add_unit_feature_btn_span = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_btn_span", "class":"add_unit_feature_btn_span_style"}',"Add Unit Featuress");
        $add_unit_feature_plus_cont = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_plus_cont", "class":"add_unit_feature_plus_cont_style"}'," ");
        $add_unit_feature_plus = insertElement($add_unit_feature_plus_cont,"div",'{"id":"add_unit_feature_plus", "class":"add_unit_feature_plus_style"}',"+");
    }else{

    }
}

else if($op = "u"){
    $logfile->logfile_writeline("getting inside u");
    $unit_type_id = $_POST["unit_type_id"];
    $logfile->logfile_writeline("the unit_type_id is:: u".$unit_type_id);

    $unit_type_id_decoded = sec_get_map_val ("unit_type_id_sig_map", $unit_type_id);
    $logfile->logfile_writeline("the encoded unit_type_id is".$unit_type_id);
    $logfile->logfile_writeline("the decoded unit_type_id is".$unit_type_id_decoded);

    $unit_types_sql = "SELECT * FROM unit_types WHERE id = ?";
    $unit_types_temp = $conn->prepare($unit_types_sql);
    $unit_types_temp->bind_param("i",$unit_type_id_decoded);
    $unit_types_temp->execute();
    $unit_types_result = $unit_types_temp->get_result();
    $unit_types_fetch_assoc = $unit_types_result->fetch_assoc();

    $unit_types_fetch_assoc_str = var_export($unit_types_fetch_assoc, true);
    $logfile->logfile_writeline("The unit_types_fetch_assoc_str is :: ".$unit_types_fetch_assoc_str);
    $unitTypeName = $unit_types_fetch_assoc["name"];
    $unitTypeId = $unit_types_fetch_assoc["id"];
    $SENSESSION->token("unit_type_id", $unitTypeId);
    $logfile->logfile_writeline("the unitTypeName is:: u: ".$unitTypeName);
    $logfile->logfile_writeline("the unitTypeId is:: u: ".$unitTypeId);

    $unit_type_edit = insertElement($main_container,"div",'{"id":"unit_type_edit", "class":"unit_type_edit_style"}'," ");
    $unit_type_edit_pg_sec = insertPageSection  ($unit_type_edit, '', '{"class":"container_body_pg_sec_style"}');

    $add_unit_tbl_parent = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_tbl_parent", "class":"add_unit_tbl_parent_style_sess"}'," ");

    $unit_type_name_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"unit_type_name_cont", "class":"unit_type_name_cont_style"}'," ");
    $unit_type_name_span = insertElement($unit_type_name_cont,"span",'{"id":"unit_type_name_span", "class":"unit_type_name_span_style"}',"Unit Type Name: ");
    $txtbox_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"txtbox_cont", "class":"txtbox_cont_style"}'," ");
    $unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"$unitTypeName\"}";
    //$unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"\"}";
    $unit_type_name_txtbox = insertElement($txtbox_cont,"input","$unit_type_name_prop","");

    $edit_btn_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"save_btn_cont", "class":"edit_btn_cont_style", "op":"s"}',"");
    $edit_btn_span = insertElement($edit_btn_cont,"span",'{"id":"edit_btn_span", "class":"edit_btn_span_style"}',"Save");

    $add_unit_feature_btn_div = insertElement($main_container,"div",'{"id":"add_unit_feature_btn_div", "class":"add_unit_feature_btn_div_style"}'," ");
    //$add_unit_feature_btn_span = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_btn_span", "class":"add_unit_feature_btn_span_style"}',"Add Unit Featuress");
    //$add_unit_feature_plus_cont = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_plus_cont", "class":"add_unit_feature_plus_cont_style"}'," ");
    //$add_unit_feature_plus = insertElement($add_unit_feature_plus_cont,"div",'{"id":"add_unit_feature_plus", "class":"add_unit_feature_plus_style"}',"+");
    
}

/*
if (isset($_SESSION['unit_type_id']) && $op == "e")
{
    $logfile->logfile_writeline("here ");
    $unit_type_name = $_POST["unit_type_name"];
    $unit_type_edit = insertElement($main_container,"div",'{"id":"unit_type_edit", "class":"unit_type_edit_style"}'," ");
    $unit_type_edit_pg_sec = insertPageSection  ($unit_type_edit, '', '{"class":"container_body_pg_sec_style"}');

    $add_unit_tbl_parent = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_tbl_parent", "class":"add_unit_tbl_parent_style_sess"}'," ");
    //$add_unit_table = insertElement($add_unit_tbl_parent,"div",'{"id":"add_unit_table", "class":"add_unit_table_style"}'," ");

    $unit_type_name_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"unit_type_name_cont", "class":"unit_type_name_cont_style"}'," ");
    $unit_type_name_span = insertElement($unit_type_name_cont,"span",'{"id":"unit_type_name_span", "class":"unit_type_name_span_style"}',"Unit Type Name2: ");
    $txtbox_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"txtbox_cont", "class":"txtbox_cont_style"}'," ");
    $unit_type_name_prop = "{\"id\":\"unit_type_name_span_val\", \"class\":\"unit_type_name_txtbox_style\", \"value\":\"$unit_type_name\"}";
    $unit_type_name_txtbox = insertElement($txtbox_cont,"input","$unit_type_name_prop","");
    //addAttribToElement($unit_type_name_txtbox, "{'class': $unit_type_name }");
    

    $edit_btn_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"save_btn_cont", "class":"edit_btn_cont_style", "op":"e"}',"");
    $edit_btn_span = insertElement($edit_btn_cont,"span",'{"id":"edit_btn_span", "class":"edit_btn_span_style"}',"Save");

    $feature_tbl_cont = insertElement($unit_type_edit_pg_sec,"div",'{"id":"edit_feature_tbl_cont", "class":"feature_tbl_cont_style"}'," ");
    
    $add_unit_feature_btn_div = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_feature_btn_div", "class":"add_unit_feature_btn_div_style"}'," ");
    $add_unit_feature_btn_span = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_btn_span", "class":"add_unit_feature_btn_span_style"}',"Add Unit Feature");
    $add_unit_feature_plus_cont = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_plus_cont", "class":"add_unit_feature_plus_cont_style"}'," ");
    $add_unit_feature_plus = insertElement($add_unit_feature_plus_cont,"div",'{"id":"add_unit_feature_plus", "class":"add_unit_feature_plus_style"}',"+");
   

}else{
    
    if($op == "s"){
        //$logfile->logfile_writeline("here 2");
        $logfile->logfile_writeline("here 2");
        $unit_type_name = $_POST["unit_type_name"];
        $logfile->logfile_writeline("the unit_type_name is:: ".$unit_type_name);
    
        $prop_id = $_SESSION['prop_id'];
        $logfile->logfile_writeline("the prop id is:: ".$prop_id); 
        //$logfile->logfile_writeline("the unit_type_name is:: ".$unit_type_name);
        $unit_types_sql = "INSERT INTO unit_types(prop_id, name) VALUES (?, ?)";
        $unit_types_temp = $conn->prepare($unit_types_sql);
        $unit_types_temp->bind_param("is",$prop_id, $unit_type_name);
        $unit_types_temp->execute();
    
        $last_id = $conn->insert_id;
    
        $logfile->logfile_writeline("the last inserted id is ::".$last_id); 
        $_SESSION['unit_type_id'] = $last_id;
    //}
    
        $logfile->logfile_writeline("unit_type_id not in session");
        $unit_type_edit = insertElement($main_container,"div",'{"id":"unit_type_edit", "class":"unit_type_edit_style"}'," ");
        $unit_type_edit_pg_sec = insertPageSection  ($unit_type_edit, '', '{"class":"container_body_pg_sec_style"}');

        $add_unit_tbl_parent = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_tbl_parent", "class":"add_unit_tbl_parent_style_sess"}'," ");
        //$add_unit_table = insertElement($add_unit_tbl_parent,"div",'{"id":"add_unit_table", "class":"add_unit_table_style"}'," ");

        $unit_type_name_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"unit_type_name_cont", "class":"unit_type_name_cont_style"}'," ");
        $unit_type_name_span = insertElement($unit_type_name_cont,"span",'{"id":"unit_type_name_span", "class":"unit_type_name_span_style"}',"Unit Type Name2: ");
        $txtbox_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"txtbox_cont", "class":"txtbox_cont_style"}'," ");
        $unit_type_name_txtbox = insertElement($txtbox_cont,"span",'{"id":"unit_type_name_span_val", "class":"unit_type_name_txtbox_style"}',$unit_type_name);

        $edit_btn_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"save_btn_cont", "class":"edit_btn_cont_style", "op":"e"}',"");
        $edit_btn_span = insertElement($edit_btn_cont,"span",'{"id":"edit_btn_span", "class":"edit_btn_span_style"}',"Edit");

        $feature_tbl_cont = insertElement($unit_type_edit_pg_sec,"div",'{"id":"edit_feature_tbl_cont", "class":"feature_tbl_cont_style"}'," ");
        
        $add_unit_feature_btn_div = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_feature_btn_div", "class":"add_unit_feature_btn_div_style"}'," ");
        $add_unit_feature_btn_span = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_btn_span", "class":"add_unit_feature_btn_span_style"}',"Add Unit Feature");
        $add_unit_feature_plus_cont = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_plus_cont", "class":"add_unit_feature_plus_cont_style"}'," ");
        $add_unit_feature_plus = insertElement($add_unit_feature_plus_cont,"div",'{"id":"add_unit_feature_plus", "class":"add_unit_feature_plus_style"}',"+");
    }
    else if($op == ""){
        $logfile->logfile_writeline("here 3");
        $unit_type_edit = insertElement($main_container,"div",'{"id":"unit_type_edit", "class":"unit_type_edit_style"}'," ");
        $unit_type_edit_pg_sec = insertPageSection  ($unit_type_edit, '', '{"class":"container_body_pg_sec_style"}');

        $add_unit_tbl_parent = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_tbl_parent", "class":"add_unit_tbl_parent_style"}'," ");
        //$add_unit_table = insertElement($add_unit_tbl_parent,"div",'{"id":"add_unit_table", "class":"add_unit_table_style"}'," ");

        $unit_type_name_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"unit_type_name_cont", "class":"unit_type_name_cont_style"}'," ");
        $unit_type_name_span = insertElement($unit_type_name_cont,"span",'{"id":"unit_type_name_span", "class":"unit_type_name_span_style"}',"Unit Type Name: ");
        $txtbox_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"txtbox_cont", "class":"txtbox_cont_style"}'," ");
        $unit_type_name_txtbox = insertElement($txtbox_cont,"input",'{"id":"unit_type_name_txtbox", "class":"unit_type_name_txtbox_style"}'," ");

        $save_btn_cont = insertElement($add_unit_tbl_parent,"div",'{"id":"save_btn_cont", "class":"save_btn_cont_style", "op":"s"}',"");
        $save_btn_span = insertElement($save_btn_cont,"span",'{"id":"save_btn_span", "class":"save_btn_span_style"}',"Save");

        $feature_tbl_cont = insertElement($unit_type_edit_pg_sec,"div",'{"id":"edit_feature_tbl_cont", "class":"feature_tbl_cont_style"}'," ");
        

        $add_unit_feature_btn_div = insertElement($unit_type_edit_pg_sec,"div",'{"id":"add_unit_feature_btn_div", "class":"add_unit_feature_btn_div_style"}'," ");
        $add_unit_feature_btn_span = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_btn_span", "class":"add_unit_feature_btn_span_style"}',"Add Unit Feature");
        $add_unit_feature_plus_cont = insertElement($add_unit_feature_btn_div,"div",'{"id":"add_unit_feature_plus_cont", "class":"add_unit_feature_plus_cont_style"}'," ");
        $add_unit_feature_plus = insertElement($add_unit_feature_plus_cont,"div",'{"id":"add_unit_feature_plus", "class":"add_unit_feature_plus_style"}',"+");
    }
}
*/

echo $doc->saveHTML();

$logfile->logfile_close();


?>