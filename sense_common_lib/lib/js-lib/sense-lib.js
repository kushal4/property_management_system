
function button_creation(butt_parent, butt_id, cont_classes, butt_data, anc_classes, anc_data, anc_text, callback){
	var butt_div = $("<div/>");
	butt_div.attr("id", butt_id);
	$(cont_classes).each(function (idx, cls){
		butt_div.addClass(cls);
	});
	//not using $.each on object as it has an obscure bug.
	//https://stackoverflow.com/questions/6276207/how-does-jquery-each-work-with-associative-arrays-objects
	for (var key in butt_data) {
        if (butt_data.hasOwnProperty(key)) {
            var value = butt_data[key];
			butt_div.data(key,value);
        }
    }

	butt_parent.append(butt_div);

	var butt_anc = $("<a/>");
	var anc_id = butt_id + "_anc";
	butt_anc.attr("id", anc_id);
	$(anc_classes).each(function (idx, cls){
		butt_anc.addClass(cls);
	});
	butt_anc.data("cont_el",butt_div);

	//not using $.each on object as it has an obscure bug.
	//https://stackoverflow.com/questions/6276207/how-does-jquery-each-work-with-associative-arrays-objects
	for (var key in anc_data) {
        if (anc_data.hasOwnProperty(key)) {
            var value = anc_data[key];
			butt_anc.data(key,value);
        }
    }

	//butt_anc.text(anc_text);
	butt_anc.html(anc_text);
	butt_anc.on("click", function (ev) {
		ev.stopPropagation();
		//console.log("button_creation");
		//console.log($(this));
		if (typeof callback === "function") {
			callback($(this));
		}
		
	});
	butt_div.data("anc_el", butt_anc);
	butt_div.append(butt_anc);
	butt_div.on("click", function () {
		console.log("clicked on div");
		//console.log(butt_div);
		butt_anc.click();
		
	});
	//console.log(anc_data);
	//console.log(butt_div);
	return butt_div;
}

function check_sec_map_old(array_name, primary_id){
	console.log("getting inside check security mapping");
	var check_if_number = $.isNumeric(primary_id);
	console.log("check_if_number::"+check_if_number);
	if(check_if_number == false){
		console.log("all okay! :)");
	}else if(check_if_number == true){
		console.log("security breach :(");
	}

	var pri_id_len = primary_id.length;
	console.log("the length of the primary ID is"+ pri_id_len);
	if(pri_id_len == 16){
		console.log("the length is fine! :)");
	}else if(pri_id_len != 16){
		console.log("something wrong with the primary id length! :)");
	}
}

function check_sec_map(json_array_parent, arr_name_str, key_name_str){
	var key_val="";
	var json_array = json_array_parent[arr_name_str];
	$(json_array).each(function (i, arr_row) {
		key_val = arr_row[key_name_str];
		if ($.isNumeric(key_val)){
			alert("FATAL Security Error (" + arr_name_str + "::" + key_name_str + ") SecVal violation)!!!"+ "[" + i + "]");
			return false;
		} else if (key_val.length !=16) {
			alert("FATAL Security Error (" + arr_name_str + "::" + key_name_str + ") SecLen violation)!!!"+ "[" + i + "]");
			return false;
		}
 
    });
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
