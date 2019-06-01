<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
//include '../eecee_include.php';
include '../eecee_include.php';
//include $eecee_php_lib_path.'eecee_sec_map.php';
//include '../../lib/php-lib/eecee_constants.php';
include  $sense_common_php_lib_path.'session_exp.php';
include $sense_common_php_lib_path.'dom_func.php';
require $sense_common_php_lib_path.'composite_control_classes.php';
include $sense_common_php_lib_path.'reg_func.php';
include $sense_common_php_lib_path.'sec.php';

$html = "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
$html.="<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
$html.="<html>";
$html.="<meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\">";
$html.="<body>";
$html.="    <div id=\"swfm_main_cont\">";
$html.="    </div>";
$html.="</body>";
$html.="</html>";
$doc = createDomDoc($html);
$session_val= $SENSESSION->session_exists();
$main_container =$doc->getElementById('swfm_main_cont');

if(!$session_val){
    //header("Location: ../login/eecee_logout.php");
   $stat=4; 
}else{
//$id=$_SESSION["prop_id"];
//echo "Prop id=re";


$stat=0; 

//$main_div = insertElement($main_container,"div",'{"id":"content", "class":"unit_type_edit_style"}'," ");
//$unit_type_list_pg_sec = insertPageSection  ($main_div, '', '{"class":"container_body_pg_sec_style"}');
$request_feat_name=$_POST["feat"];


//echo "request_val.$request_val";
if($request_feat_name=="swf_role_management"){
    $acc_man_role_parent_container = insertElement($main_container,"div",'{"class":"sel_prop_div"}'," ");
    $features_pg_sec = insertPageSection($acc_man_role_parent_container, '', '{"class":"sel-prop-page-section"}');
    $role_tree_container = insertElement($features_pg_sec,"div",'{"class":"user_prop_container role_tree_man"}'," ");  
    addAttribToElement($role_tree_container, '{"id":"swfm_role_tree"}');  
}

}
$role_man_sess_container = insertElement($main_container,"span",'{"id":"role_man_sess"}',"");
addDataToElement($role_man_sess_container, '{"s":"'.$stat.'"}');  
echo $doc->saveHTML();
?>