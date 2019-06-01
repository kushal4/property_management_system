//$( window ).on( "load", get_prop_name());

function body_load_func() {
    get_prop_name();
}

function get_prop_name(){
    console.log("getting inside dashboard");
    var object = {};
	$.ajax({
		method: "POST",
		url: "get_prop_name.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
                    var prop_name = data.prop_name;
                    console.log("the property name is"+prop_name);

                    $("#left_box_name").text(prop_name);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}


function landing_page(res){
	var landing_pg = res + ".php";
	window.location.href = landing_pg;
}

function set_feat_cat_sig_in_sess(secedFeatCatSig, landing_url){
	console.log("getting inside set_feat_cat_sig_in_sess");
	console.log(secedFeatCatSig);
	var object = {};
	object.feat_cat_sig = secedFeatCatSig;
	$.ajax({
		method: "POST",
		url: "feat_cat_sess.php",
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
					//alert("ajax success");
					window.location.href = landing_url;
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

$(document).ready(function(){
	console.log(this);
	console.log($(this));
	$(".fc").on('click',function(e){
		//console.log(this);
		//console.log($(this));
		console.log($(this).data("j"));
		var data_j = $(this).data("j");
		console.log(data_j);
		var secedFeatCatSig = $(this).data("s");
		console.log(secedFeatCatSig);
		if(data_j == "acc_man"){
			var path = "../acc/";
		}else if(data_j == "prof_def"){
			var path = "../propdef/";
		}else if(data_j == "usr_prof"){
			//var path = "../usr_prof/";
		}else if(data_j == "helpdesk"){
			var path = "../helpdesk/";
		}else if(data_j == "swfm"){
			var path = "../swfm/";
		}
		var landing_url = path + data_j + ".php";
		console.log(landing_url);
		set_feat_cat_sig_in_sess(secedFeatCatSig, landing_url);
	});	
});