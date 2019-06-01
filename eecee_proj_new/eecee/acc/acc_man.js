
function set_feat_sig_in_sess(secedFeatSig, landing_url){
	console.log("getting inside set_feat_cat_sig_in_sess");
	var object = {};
	object.feat_sig = secedFeatSig;
	$.ajax({
		method: "POST",
		url: "../login/feat_sess.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("set_feat_cat_sig_in_sess::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					
					/*
					console.log(landing_url);
					if(landing_url == "acc_man_usr.php"){
						window.location.href = "assign_role.php";
					}else if(landing_url == "def_role_scp.php"){
						window.location.href = "acc_man_role_scope.php";
					}else{
						window.location.href = landing_url;
					}
					*/
					var landingUrl = data.url_name;
					window.location.href = landingUrl;
					
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}



$(document).ready(function(){

$(".Feature_name_style").on('click',function(e){
   console.log("the a feature is clicked");
    var secedFeatSig = $(this).data("s");
    console.log(secedFeatSig);
    var sig = $(this).data("j");
	var landingURL = sig + ".php";
	console.log(landingURL);
	set_feat_sig_in_sess(secedFeatSig, landingURL);
	
});	


});