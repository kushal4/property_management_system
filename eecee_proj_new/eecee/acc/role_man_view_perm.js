function role_man_view_perm_on_load(){
    console.log("getting inside role_man_view_perm_on_load");
	var object = {};
	//object.target_sig = target_sig;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "get_view_perm.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if(data.ret_code == 0) {
                   window.location.href='role_man_view_perm.php'; //go to role_man_view_perm
				} else {
					

				}
			}
		},
		failure: function () {
		}
	});
}

$(document).ready(function(){

    $(function () {
        console.log("ON LOAD:: role_man_view_perm");
        role_man_view_perm_on_load();
	});  

	$(".back_button_style").on('click', function (e) {
		console.log("pressed back");
		
		window.location.href='acc_man_role.php';
	});
    
});