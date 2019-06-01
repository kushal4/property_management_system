
function click_on_prop_old(prop_id, prop_name){
	$("#role_table_cont").tablecreator("clearTable");
	console.log("getting inside existing_prop_dash");
	tree_prop_name = prop_name;
	if (prop_id==0) {
		tree_prop_name = "Please Select a Property";
	}
	console.log ("Changing root node name");
	var inst = $.jstree.reference("PROP_ROOT");
	//console.log(inst);
	var jstree_root_node = inst.get_node("PROP_ROOT");
	//console.log("jstree_root_node");
	//console.log(jstree_root_node);
	//inst.select_node(jstree_root_node);
	//console.log("Selected Node:");
	//console.log(inst.get_selected(true));
	//inst.rename_node(inst.get_selected(true), "My New Name");
	inst.rename_node(jstree_root_node, tree_prop_name);
	//inst.rename_node(inst.get_node("PROP_ROOT"));
	console.log("jstree_root_node renamed");
	jstree_root_node.data = {pid:prop_id};
	inst.refresh_node(jstree_root_node);
}

function click_on_prop_new(prop_id, prop_name){
	$('#context_role_dropdown').empty();
	var option_blank = $("<option value='0' selected='selected'></option>");
	option_blank.text("Select role");
	$('#context_role_dropdown').append(option_blank);
	console.log("getting inside click_on_prop_new");
    var object = {};
    object.prop_id = prop_id;
	$.ajax({
		method: "POST",
		url: "../login/click_on_prop_new.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("click_on_prop_new::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if(data.ret_code == 0) { 
				   var roles = data.roles;
				   console.log(roles);
				   //$(roles).each(function (i, net) {
					//console.log(i);
				   //});  

				   for(var key in roles) {
						//console.log(key);
						//console.log(roles[key]);
						var option_val = $("<option/>");
						option_val.text(roles[key]);
						option_val.val(key);
						$('#context_role_dropdown').append(option_val);
				  }
				} else {
				
				}
			}
		},
		failure: function (jqXHR, exception) {
			console.log(exception);
		}
	});
	
	
}

function select_role(role_sig){
    console.log("getting inside select_role");
    var object = {};
    object.role_sig = role_sig;
	$.ajax({
		method: "POST",
		url: "select_role.php",
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
				   console.log("select role ajax success");
				   window.location.href='dashboard.php';
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function init_sel_prop_role_tree(arg){
	console.log("getting inside init_sel_prop_role_tree");
	//alert("getting inside init_sel_prop_role_tree");
	//alert("getting inside init_sel_prop_role_tree");
	//var object = arg;
	console.log(arg.pid);
	console.log(arg.role_sig);
	var object = {};
	object.prop_id = arg.pid;
	object.role_sig = arg.role_sig;
	return $.ajax({
		method: "POST",
		url: "init_sel_prop_role_tree.php",
		//url: "get_prop_topo.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("init_sel_prop_role_tree::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					role_tree = data.role;
					role_root = data.root;
					role_root.children.push(role_tree);
					//result = data.role;
					result = role_root;
					console.log ("get_prop_topo::after AJAX::topo_tree:: result");
					console.log ("********************acc_male_role Tree******************");
					console.log(result);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	  }).then(function() {
			console.log ("inside AJAX Then");
			return $.Deferred(function(def) {
				console.log ("inside AJAX deferred");
				console.log (result);
		  		def.resolveWith({},[result]);
			}).promise();
	  });

	
}


function temp_dashboard_link(node_id, node_name, prop_id){
	console.log("getting inside set_target_role_sig_sess");
	if(node_id != 0){
		var object = {};
		object.node_id = node_id;
		object.node_name = node_name;
		object.prop_id = prop_id;
		console.log(object);
		$.ajax({
			method: "POST",
			url: "temp_dashboard_link.php",
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
						window.location.href='dashboard.php';
					
					} else {
						

					}
				}
			},
			failure: function () {
			}
		});
	}else{
		//$('#enter_with_role_button_cont').click(false);
		$('#enter_with_role_button_cont').css( 'cursor', 'auto' );
	}
	
}


function clr_cap_dd(){
	console.log("getting inside sel_prop_first");
	$('#context_role_dropdown').empty();
	//var option = $("<option value='-1' </option>");
	var option = $("<option/>");
	option.val(-1);
	option.text("Please select a property first");
	//
	$('#context_role_dropdown').append(option);
	option.attr("selected", true);
	$('#context_role_dropdown').change();
}

function on_page_load(){
	clr_cap_dd();
}

function click_on_role(prop_id, role_sig){
	console.log("getting inside click_on_role function");
	var inst = $.jstree.reference('#login_role_tree');
	console.log(inst);
	console.log(role_sig);
	var root_node = inst.get_node("PROP_ROOT");
	console.log(root_node);
	console.log(root_node);
	root_node.data = {pid:prop_id , role_sig:role_sig};
	var get_children_dom = inst.get_children_dom("PROP_ROOT");
	console.log(get_children_dom);
	$(get_children_dom).each(function (i, index) {
		var child_id = index.id;
		console.log(child_id);
		var get_child_node = inst.get_node(child_id);
		inst.delete_node(get_child_node); // delete the node
	});
	inst.refresh_node(root_node);
}


$(document).ready(function(){
 
	
	//alert("getting inside sel_prop.js");
	$('#login_role_tree').jstree({
		'plugins': ["dnd", "types"],
		'core' : {
			
			'data' : function (target_node_obj, callback) {
				console.log(" Sel Prop JSTREE loading");
				console.log(target_node_obj);
				console.log("target_node_obj.data");
				console.log(target_node_obj.data);
				var argobj = {};
				if (target_node_obj.data==null){
					argobj.pid = "0";
					argobj.role_sig = "";
				} else {
					argobj.pid = target_node_obj.data.pid;
					argobj.role_sig = target_node_obj.data.role_sig;
					console.log(argobj);
					
				}
				
				init_sel_prop_role_tree(argobj).done(function(result){ 
					console.log ("Sel Prop JSTREE FEEDER: " + result);
					console.log(this);
					console.log(result);
					callback.call(this, result);	
					});
				},
				
			
			'check_callback': true
		},
		"types": {
				
			"role" : {
				"icon" : "../ext-styles/themes/default/ricon24.png?"+new Date().getTime(),

			},
			"rolecat" : {
				"icon" : "../ext-styles/themes/default/cicon24.png?"+new Date().getTime(),
				
			},
			"role_dcm" : {
				"icon" : "../ext-styles/themes/default/ricon24.png?"+new Date().getTime(),
				"a_attr" : { "style" : "color:#787f94" } ,
				
			},
			"role_link" : {
				"icon" : "../ext-styles/themes/default/rlink24.png?"+new Date().getTime(),
				
			},
			"role_res" : {
				"icon" : "../ext-styles/themes/default/rlocked24.png?"+new Date().getTime(),
			},
			"cat_res" : {
				"icon" : "../ext-styles/themes/default/clocked24.png?"+new Date().getTime(),
			}
		}
	}).on('rename_node.jstree', function (e, data) {
		console.log("Rename Node Fired");
		var node_name = data.node.text;
			var node_id = data.node.id;
			console.log(data);
			//console.log(node);
			console.log(node_id);
			

	});

	var inst = $.jstree.reference('#login_role_tree');
	console.log(inst);
	var root_node = inst.get_node("PROP_ROOT");
	console.log(root_node);

	$("#existing_prop_dropdown").change(function (ev) {
        console.log("changed");
		var selected_property_val = $("#existing_prop_dropdown").val();
		var selected_property_txt = $("#existing_prop_dropdown option:selected").text();
		console.log("the value of the selected property is:: "+selected_property_val);
		console.log("the name of the selected property is:: "+selected_property_txt);
		//click_on_prop(selected_property_val, selected_property_txt);
		
		if(selected_property_txt != "Select Property"){
			click_on_prop_new(selected_property_val, selected_property_txt);
		}
		if(selected_property_txt == "Select Property"){
			clr_cap_dd();
		}
    });

	$("#context_role_dropdown").change(function (ev) {
		//alert(" context_role_dropdown change");
        console.log(" context_role_dropdown changed");
		var selected_role_val = $("#context_role_dropdown").val();
		var selected_property_val = $("#existing_prop_dropdown").val();
		//var selected_property_txt = $("#existing_prop_dropdown option:selected").text();
		console.log("the value of the selected role is:: "+selected_role_val);
		console.log("the value of the selected property is:: "+selected_property_val);
		//console.log("the name of the selected property is:: "+selected_property_txt);
		if(selected_role_val == 0 || selected_role_val == -1){
			click_on_role(selected_property_val, null);
		}
		if (selected_role_val != 0){
			click_on_role(selected_property_val, selected_role_val);
		}
		
	});
	
	on_page_load();


    $("#role_table_cont").tablecreator({
		headingStyle: 'generic-table-heading client-table-heading',/**style classes appends to header element */
		headingTextStyle: '',/**style classes appends to header element span */
		headingText: '',
		contentStyle: 'table_wrapper role_table_wrapper',/**style classes appends to content div */
		contentTableStyle: 'generic_table role_tbl_cls',/**style classes appends to content table */
		tableCols: [{ text: 'Role', class: 'client-list-col1', colsig: 'name_id' }],
		tableHeaderStyle: 'tableHeaderStyle',
	});

	/*
	$("#test_btn").on('click', function (e) {
		
		console.log("Test Button Clicked");
		var inst = $.jstree.reference("login_role_tree");
		console.log (inst);
		var node_obj = inst.get_node("PROP_ROOT");
		console.log(node_obj);
		inst.select_node(node_obj);
		inst.rename_node(node_obj, "My New Name");
	});
	*/
	

	$('#login_role_tree').on("select_node.jstree", function (e, data) { 
		var type = data.node.data;
		console.log("type of node is :: "+type);
		var node_id = data.node.id;
		console.log("node_id: " + data.node.id); 
		if(type == "role"){
			console.log("type is role");
			$("#login_view_perm_cont").show();
			$("#login_view_perm_cont").load("login_view_perm.php", { node_id: node_id });
			$("#enter_with_role_button_cont").data("node_id", node_id);
			$('.enter_with_role_button_styl').css("cursor","pointer");
		}else{
			$("#login_view_perm_cont").hide();
			$("#enter_with_role_button_cont").data("node_id", 0);
			$('.enter_with_role_button_styl').css("cursor","text");
		}
	});

	$("#enter_with_role_button_cont").on('click', function (e) {
		//var node_id = $("#enter_with_role_button_cont").data("node_id");
		//console.log("the node ID is: "+node_id);
		var inst = $.jstree.reference('#login_role_tree');
		var selected_node = inst.get_selected(true);
		var sel_node = selected_node[0];
		console.log(sel_node);
		console.log("selected node printed");
		var selected_node_id = sel_node.id;
		var selected_node_type = sel_node.type;
		var selected_node_name = sel_node.text;
		console.log("role ID is ::"+ selected_node_id);
		console.log("type is role"+ selected_node_type);
		var selected_property_val = $("#existing_prop_dropdown").val();
		console.log(selected_property_val);
		
		if(selected_node_type == "role" || selected_node_type == "role_res"){
			$('.enter_with_role_button_styl').css("cursor","pointer");
			temp_dashboard_link(selected_node_id, selected_node_name, selected_property_val);
		}else{
			$('.enter_with_role_button_styl').css("cursor","text");
		}
	
	});

	$("#dev").on('click', function (e) {
		//alert("clicked on dev");
		window.location.href='dashboard.php';
	});

});