<?php

session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../eecee_include.php';
include 'prop_topo.php';
$log_path = $eecee_log_path."prop_def.log";
require_once $sense_common_php_lib_path.'Log.php';

require $eecee_ext_php_lib_path.'vendor/autoload.php';
include 'show_flats_func.php';
include $sense_common_php_lib_path.'sec.php';
include $sense_common_php_lib_path.'session_exp.php';

$logfile = new \Sense\Log($log_path, __FILE__);
$logfile->logfile_open("a");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$logfile->logfile_writeline("getting inside download excel PHP");

$conn = new \mysqli($server_name, $user_name, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$session_val= is_session_valid();

if($session_val==0){
    $prop_id = $SENSESSION->get_val("prop_id");
    $parentid = 0;
    
    $sql_check_prop = "SELECT * FROM prop_topo WHERE prop_id = ? AND parent_id = ?";
    $sql_check_prop_temp = $conn->prepare($sql_check_prop);
    $sql_check_prop_temp->bind_param("ii",$prop_id, $parentid);
    $sql_check_prop_temp->execute();
    $sql_check_prop_result = $sql_check_prop_temp->get_result();
    $sql_check_prop_row = $sql_check_prop_result->fetch_assoc();
    $prop_topo_root_id = $sql_check_prop_row["id"];
    //$num_of_flats = mysqli_num_rows($sql_check_prop_result);
    //$logfile->logfile_writeline("the number of flats are".$num_of_flats);

    $ref_array =  array();

    show_flats($ref_array, $prop_topo_root_id, $prop_id, $conn, $logfile);
    
    //$num_flat_rows = count($ref_array);//number of rows

    $flat_name = "";
    $spreadsheet = new Spreadsheet();
    
    foreach ($ref_array as $key => $value) {
        $flat_name = $value["node_name"];
        $flat_no = $key + 1;
        $logfile->logfile_writeline("the flat names are****".$value["node_name"]);  
        $logfile->logfile_writeline("for_each loop end");
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A'.$flat_no, $flat_name);
       
    } 

    $writer = new Xlsx($spreadsheet);
    $writer->save('output/test_save.xlsx');
    
    $raw_json["ret_code"] = 0;
    //$raw_json["excel_path"] = "$server_name/opt/eecee/output/test_save.xlsx";
    $raw_json["excel_path"] = "output/test_save.xlsx";
    $raw_json_encode=json_encode($raw_json);
    echo $raw_json_encode;
}

else{      
}
$conn->close();
$logfile->logfile_close();
?>
