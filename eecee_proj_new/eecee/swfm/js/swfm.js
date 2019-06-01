
 

$(document).ready(function(){
   
	
	$mainContent = $('#main_container');   
						console.log($("#row_SWF_FM_USER_MAN").find("td"));
						$("#row_SWF_FM_USER_MAN").find("td").on("click",function(){

							$mainContent.load("../swfm/view_swf_roster.php #roster_view_main_cont", function (response, status, xhr) {
                              $("#add_swf_roster_plus_cont").on("click",function(){
								$mainContent.load("../swfm/add_update_swf_usr.php #add_roster_user_main_cont",{"op":"n"}, function (response, status, xhr) {

								});
							  });
							});	
						});
							
	$("#row_SWF_ADMIN_ROLE_MAN").on("click",function(){
        console.log("clicked on swfm feature");
		console.log($(this));
		var object = {};
		var secedFeatSig=$(this).find("td").data("s");
		console.log("the sfwfm feat sig"+secedFeatSig);
	object.feat_sig = secedFeatSig;
		$.ajax({
			method: "POST",
			url: "../login/feat_sess.php",
			data: { k: JSON.stringify(object) },
			dataType: "json",
			success: function (data, textStatus, jQxhr) {
				$mainContent.load("../swfm/swfm_administration_view_cat.php #swfm_main_cont",{"feat":"swf_role_management"}, function (response, status, xhr) {
					//alert();
					var session_stat=$("#role_man_sess").attr("data-s");
					console.log(session_stat);
					if(session_stat!=0){
						eecee_logout();
					}
					//console.log(response);
					$.getScript('../swfm/js/swfm_role_management.js',function (response, status, xhr) {
					
					});
			});
			},
			failure: function () {
			}
		});
       ;

});

$("#swfm_cat_div").dialog({
	width: 700,
	height: 150,
	dialogClass: 'generic_dialog cant_del_role_cat_dialog_style',
	autoOpen: false,
	modal: true,
	
	close: function () {
		
	},

});

function eecee_logout(){
	window.location.href="../login/eecee_logout.php";
}
});