(function(){


$mainContent = $('#main_container');    
function refreshScreen(qs){
    $mainContent.load("/opt/eecee_proj_new/eecee/helpdesk/manage_issue_priorities.php #main_cont ",{"q":qs}, function (response, status, xhr) {
        // alert();
        $.getScript('/opt/eecee_proj_new/eecee/helpdesk/js/manage_issue_pririties.js',function (response, status, xhr) {
             
        });
    });
}

 

$("#row_HESKMANISSUECAT").on("click",function(){
    refreshScreen("l");
});

$("#create_priority").on('click',function(){
    refreshScreen("c");
});


$("#cat_add_btn").on('click',function(){
    $.post('/opt/eecee_proj_new/eecee/helpdesk/manage_issue_priorities_api.php',   // url
       { method: 'create',cat_name:$("#cat_txt").val() }, // data to be submit
       function(data, status, jqXHR) {// success callback
        console.log(data);
        refreshScreen("l");
       
        })
  
});
$(".hesk_del").on('click',function(ev){
    var data_s=$(this).parent().data("s");
   // alert(data_s);
  

    $.post('/opt/eecee_proj_new/eecee/helpdesk/manage_issue_priorities_api.php',  // url
       { method: 'delete',s:data_s }, // data to be submit
       function(data, status, jqXHR) {// success callback
        console.log("success after ajax");
        console.log(jqXHR);
        console.log(data);
        refreshScreen("l");
       
        });
   
});
$(".hesk_update").on('click',function(){
    var data_s=$(this).parent().data("s");
    $mainContent.load("/opt/eecee_proj_new/eecee/helpdesk/manage_issue_priorities.php #main_cont ",{"q":"u","s":data_s}, function (response, status, xhr) {
        // alert();
        //console.log(response);
        $.getScript('/opt/eecee_proj_new/eecee/helpdesk/js/manage_issue_pririties.js',function (response, status, xhr) {
             
        });
        
      });
  
});
$("#cat_update_btn").on('click',function(){
   // alert($(this).data("s"));
    var data_s=$(this).data("s");
    //alert(data_s);
    $.post('/opt/eecee_proj_new/eecee/helpdesk/manage_issue_priorities_api.php',  // url
    { method: 'update',s:data_s,text:$("#cat_txt").val() }, // data to be submit
    function(data, status, jqXHR) {// success callback
        refreshScreen("l");
    
     });

});
function upDownRows(what,elem){
  var src=elem.parent().data("s");;
  var dest=null;
   if(what=="up"){
    console.log(elem.parent());
    console.log(elem.parent().parent());
    console.log(elem.parent().parent().prev());
    dest=elem.parent().parent().prev().children(1).data("s");
   
    console.log(dest);
   }
   else if(what=="down"){
    dest=elem.parent().parent().next().children(1).data("s");
    console.log(dest);
   }
   

   if(dest==undefined){
    dest=null;
   }

   $.post('/opt/eecee_proj_new/eecee/helpdesk/manage_issue_priorities_api.php',  // url
   { method: 'change_pos',source:src,destination:dest }, // data to be submit
   function(data, status, jqXHR) {// success callback
      console.log(data);
       refreshScreen("l");
   
    });





}



$(".up").on("click",function(){
     upDownRows("up",$(this));
});
$(".down").on("click",function(){
    upDownRows("down",$(this));
});


})();