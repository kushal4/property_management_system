<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'lib/php-lib/eecee_constants.php';
$log_path = "Logs/eecee.log";
include '../lib/php-lib/sec.php';
include 'prop_topo.php';
include '../lib/php-lib/session_exp.php';

$logfile = new \Sense\Log("Logs/eecee.log", __FILE__);
$logfile->logfile_open("a");

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

$logfile->logfile_writeline("getting inside acc_man PHP");

$feature_cat_sig = $_SESSION["FCAT"];
$perm_obj = $_SESSION["perm_obj"];

foreach ($perm_obj as $value){  
    foreach ($value as $v){
        $sig = $v["sig"];
        $fet_cat_name = $v["text"];
        $type = $v["type"];
        $parent = $v["parent"];
        
        if($type == "feature" && $parent == $feature_cat_sig){
            $secedFeatCatSig = sec_push_val_single_entry ("feat_cat_sig_map", $sig);
            echo "the sig is::".$sig."</br>";
            echo "the parent is::".$parent."</br>";
            echo "the type is::".$type."</br>";
            echo "</br>";
        }
    }
}

/*
$raw_json["ret_code"] = 0;
$raw_json_encode=json_encode($raw_json);
echo $raw_json_encode;
*/

?>