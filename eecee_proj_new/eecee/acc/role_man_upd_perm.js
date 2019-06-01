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



function update_perm(upd_perm_arr){
    console.log("getting inside role_man_view_perm_on_load");
	var object = {};
	object.upd_perm_arr = upd_perm_arr;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "update_perm.php",
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
        console.log("ON LOAD:: role_man_upd_perm");
        //role_man_view_perm_on_load();
	});  

	$("#update_button").on('click', function (e) {
        console.log("pressed update");	
        var x = document.getElementsByClassName("feature_row");
        console.log(x);
        var upd_perm_arr = [];
        //var active = 0;
        $(x).each(function (i, val) {
            console.log(val.id);
            var last_child = $(val).children().last()							
            //console.log(last_child);
            $(last_child).each(function (i, v) {
                
               // console.log(v.childNodes);
                var childNode = v.childNodes;
                var  active = childNode[0];
                console.log(active.checked);
                

                var upd_obj = { "sig" : val.id , "active" : active.checked};
                upd_perm_arr.push(upd_obj);
            });
        });
        console.log(upd_perm_arr);

        update_perm(upd_perm_arr);

    });
    

    /*
    $('input[type="checkbox"]').click(function(){
        if($(this).is(":checked")){
            alert("Checkbox is checked.");
        }
        else if($(this).is(":not(:checked)")){
            alert("Checkbox is unchecked.");
        }
    });
    */
   $(".back_button_style").on('click', function (e) {
	console.log("pressed back");
	window.location.href='acc_man_role.php';
});
    
});