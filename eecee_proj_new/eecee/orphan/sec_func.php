<?php

$net_map = array();
$net_inst_map = array();
$global_scope_topic_map = array();
$global_scope_imp_topic_map = array();

$public_scope_topic_map = array();
$acct_global_scope_topic_map = array();

$net_scope_topic_map = array();
$net_inst_scope_topic_map = array();
$net_scope_client_map = array();
$net_inst_scope_client_map = array();
$interval_map = array();

$_SESSION["net_map"] = $net_map;
$_SESSION["net_inst_map"] = $net_inst_map;
$_SESSION["global_scope_topic_map"] = $global_scope_topic_map;
$_SESSION["global_scope_imp_topic_map"] = $global_scope_imp_topic_map;


$_SESSION["public_scope_topic_map"] = $public_scope_topic_map;//didn't really get what this is
$_SESSION["acct_global_scope_topic_map"] = $acct_global_scope_topic_map; //didn't really get what this is

$_SESSION["net_scope_topic_map"] = $net_scope_topic_map;
$_SESSION["net_inst_scope_topic_map"] = $net_inst_scope_topic_map;
$_SESSION["net_scope_client_map"] = $net_scope_client_map;
$_SESSION["net_inst_scope_client_map"] = $net_inst_scope_client_map;
$_SESSION["interval_map"] = $interval_map;

function push_map ($map_name, $map){
    $_SESSION[$map_name]=$map;
}

function push_map_single_entry ($map_name, $key, $val){
    $curr_map=$_SESSION[$map_name];
    $curr_map[$key] = $val;
    $_SESSION[$map_name]=$curr_map;
}

function clear_map ($map_name){
    $_SESSION[$map_name] = array();
}

function get_map_val ($map_name, $key, $val){
    $curr_map=$_SESSION[$map_name];
    return $curr_map[$key];
}



?>