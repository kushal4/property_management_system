
function body_load_func() {
	
	var setup_prop_btn_cont = $("#setup_prop_btn_cont");
	button_creation(setup_prop_btn_cont, "setup_prop_btn_cont_id", ["generic_btn_style", "client_pck_info_butt_cont"], {}, ["btn_anc"], {}, "Save", (function (el) {
		console.log("clicked on SAVE button");
        save_setup_property_to_db();
        
    }));
    
    

}

function eecee_logout(){
    alert("Your session expired. You will be logged out.");
    window.location.href="eecee_logout.php";
}

/*
function dashboard_prop(prop_name){


    function buildUrl(url, parameters){
        var qs = "";
        for(var key in parameters) {
          var value = parameters[key];
          qs += encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";
        }
        if (qs.length > 0){
          qs = qs.substring(0, qs.length-1); //chop off last "&"
          url = url + "?" + qs;
        }
        return url;
      }
      
      // example:
      var url = "dashboard.php";
      
      var parameters = {
        prop_name: prop_name
      };
      
      window.location.href=buildUrl(url, parameters);
      //console.log(buildUrl(url, parameters));
}
*/

function proc_sense_table (tbl_id, post_url) {
    console.log("getting inside proc_sense_table");
    $ajax_elem = $(".sta-"+tbl_id);
    console.log ($ajax_elem);
    var object = {};   
    $($ajax_elem).each(function (k, v) {
        
        var first_key = v.dataset;
        $(first_key).each(function (key, value) {
            var fld_key = value.i;
            var fld_val = $("#"+fld_key).val();
            object[fld_key] = fld_val;
        });
    });
    console.log(object);
    $.ajax({
		method: "POST",
		url: post_url,
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
                    property_nm = prop_name;
                    console.log("the property name is"+prop_name);
                    window.location.href='dashboard.php';
                    dashboard_prop(prop_name);
				} else {
				
				}
			}
		},
		failure: function () {
		}
    });
    
}

function save_setup_property_to_db(){
    proc_sense_table ("setup_prop_tbl_container", "save_setup_prop.php");
}

function existing_prop_dash(prop_id){
    console.log("getting inside existing_prop_dash");
    var object = {};
    object.prop_id = prop_id;
	$.ajax({
		method: "POST",
		url: "existing_prop_dash.php",
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
                    //var prop_name = data.prop_name;
                    //console.log("the property name is"+prop_name);

                    //$("#left_box_name").text(prop_name);
                    window.location.href='dashboard.php';
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

$( function() {
    $( "#user_dets_accordion" ).accordion({
      collapsible: true,
      active: false,
      heightStyle:"content",
    });
  } );

$(document).ready(function(){
    $("#box_left").on('click',function(e){
        console.log("Clicked on set-up property box");
        window.location.href='prop_def.php';
    });

    $("#create_prop_butt").on('click',function(e){
        console.log("Clicked on set-up property box");
        window.location.href='create_prop.php';
    });



   $("div.sel_prop_table_wrapper table").delegate('td.prp_name_style', 'click', function(e) {
    //$("div.sel_prop_table_wrapper table").delegate('tr', 'click', function(e) {
    console.log("You clicked my <tr>!");
    //var tr_data= (<tr).data("data");
    //var target = $(e.target)
    //alert(target);
    //var tr_data= target.data("prop_id");
    //$(this).data("prop_id")
    var prop_id = $(this).data("prop_id");
    console.log(prop_id);
    existing_prop_dash(prop_id);
    //window.location.href='dashboard.php'
    //get <td> element values here!!??
});

//$(function () { $('#demo_prop_tree').jstree(); });




/*
$('#prop_topo_tree').on('ready.jstree', function (e, data) {
    createNode("prop_topo_tree","#", "root", "Prop name", "last");
    
    //createNode("#prop_topo_tree", "base_directory02", "Second Parent node", "last");
    //createNode("#prop_topo_tree", "base_directory03", "Third Parent node", "last");
    //createNode("#base_directory", "child_2", "Child 2", "last");
    
});
*/


//$('#demo_prop_tree').jstree('create_node', $(parent_node), { "text":new_node_text, "id":new_node_id }, position, false, false);	
});
