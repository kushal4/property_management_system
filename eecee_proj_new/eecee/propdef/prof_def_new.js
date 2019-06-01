function call_prop_def_fea_list(){
	$("#main_container").load("prop_def_fea_list.php #main_cont");
	
}


$(document).ready(function(){
    //alert("loaded prof_def_new");
    //call_property_definition_feature_table();
    //alert("loaded prop_def");
    call_prop_def_fea_list();
});