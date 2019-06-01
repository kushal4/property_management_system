$(document).ready(function(){
// $.getScript('/opt/eecee_proj_new/eecee/helpdesk/js/manage_issue_categories.js',function (response, status, xhr) {
         
//     });
    $("#msg_dialog").dialog({
        width: 700,
        height: 150,
        dialogClass: 'generic_dialog cant_del_role_cat_dialog_style',
        autoOpen: false,
        modal: true,
        
        close: function () {
            
        },
    
    });	
    $("#manage_issue_cat_div").dialog({
        width: 700,
        height: 150,
        dialogClass: 'generic_dialog cant_del_role_cat_dialog_style',
        autoOpen: false,
        modal: true,
        
        close: function () {
            
        },
    
    });	
    $mainContent = $('#main_container');    

$("#row_HESKMANISSUEPRI").on("click",function(){

   
    $mainContent.load("/opt/eecee_proj_new/eecee/helpdesk/manage_issue_priorities.php #main_cont ",{"q":"l"}, function (response, status, xhr) {
    // alert();
    $.getScript('/opt/eecee_proj_new/eecee/helpdesk/js/manage_issue_pririties.js',function (response, status, xhr) {
         
    });
});


});
$("#row_HESKMANISSUECAT").on("click",function(){

   
    $mainContent.load("/opt/eecee_proj_new/eecee/helpdesk/manage_issue_categories.php #main_cont ",{"q":"l"}, function (response, status, xhr) {
    // alert();
    $.getScript('/opt/eecee_proj_new/eecee/helpdesk/js/manage_issue_categories.js',function (response, status, xhr) {
         
    });
});


});
    /*
$("#root").hide();
$("#row_HESKMANISSUECAT").on("click",function(){

//$(".sel-prop-page-section").hide();
//$("#root").show();
$mainContent = $('#main_container');
//$mainContent.load("/opt/eecee_proj_new/eecee/helpdesk/manage_issue_categories.php"+" > #main_cont > *", function (response, status, xhr) {
    $mainContent.load("/opt/eecee_proj_new/eecee/helpdesk/manage_issue_categories.php #main_cont ",{"q":"l"}, function (response, status, xhr) {
    // alert();
    $.getScript('/opt/eecee_proj_new/eecee/helpdesk/js/manage_issue_categories.js',function (response, status, xhr) {
         
    });
});


});
$("#row_HESKMANISSUEPRI").on("click",function(){
  
    });
    $("#row_HESKMAPISSUES").on("click",function(){
       
        });

*/
     


});