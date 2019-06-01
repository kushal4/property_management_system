function resend_email(email){
	console.log("getting inside resend_email");
	
	var object = {};
	object.email = email;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "resend_email.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("resend_email::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
			} else {
				if (data.ret_code == 0) {
					var message = data.ret_msg;
					console.log(message);
					//var hrs_left = data.hours_left;
					$("#notification_lbl").text(message);
				}
			}
		},
		failure: function () {
			console.log("AJAX failed");
		}
	});
}

$(document).ready(function(){
    $("#resend_email").on('click', function (e) {
		var email = $("#email_id").val();
		//console.log("the email is::"+email);
		resend_email(email);
		
	});
});