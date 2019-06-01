
function clicked_on_user_name_old(user_id, user_name){
    $("#role_table_cont").tablecreator("clearTable");
	console.log("getting inside existing_prop_dash");
	tree_user_name = user_name;
	if (user_id==0) {
		tree_prop_name = "Please select an user name from the first fold";
	}
	console.log ("clicking on user");
	var op = "s";
	var inst = $.jstree.reference('#assign_role_tree');
	console.log("assign_role_tree INST");
	console.log(inst);
	var root_node = inst.get_node("PROP_ROOT");
	//root_node.data.user_id = user_id;
	root_node.data = {user_id:user_id , op:op, m: ""};
	//root_node.data = {op:op};
	inst.refresh_node(root_node);
	//jstree_root_node.data = {target_role_sig:role_sig};
}

function m_table_pop(){
	
}

function middle_table_population(ctx_id){
	console.log("getting inside middle_table_population");
	var object = {};
	object.ctx_id = ctx_id;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "middle_table_population.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("middle_table_population::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if(data.ret_code == 0) { 
					var user_name = data.user_name;
					var email = data.email;
					var node_name = data.node_name;
					console.log(user_name);
					console.log(email);
					console.log(node_name);
					$("#user_name").val(user_name);
					$("#emailid").val(email);
					$("#flat_name").val(node_name);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function clicked_on_user_name(ctx_id){
    $("#role_table_cont").tablecreator("clearTable");
	console.log("getting inside existing_prop_dash");
	//tree_user_name = user_name;
	if (ctx_id==0) {
		tree_prop_name = "Please select an user name from the first fold";
	}
	console.log ("clicking on user");
	console.log (ctx_id);
	middle_table_population(ctx_id);
	var op = "s";
	var inst = $.jstree.reference('#assign_role_tree');
	console.log("assign_role_tree INST");
	console.log(inst);
	var root_node = inst.get_node("PROP_ROOT");
	root_node.data = {user_id:ctx_id , op:op, m: ""};
	inst.refresh_node(root_node);
}


function init_assign_role_tree_old(arg){
	console.log("getting inside init_sel_prop_role_tree_old");
	//var object = arg;
	console.log(arg.uid);
	var object = {};
	object.user_id = arg.uid;
	return $.ajax({
		method: "POST",
		url: "init_assign_role_tree.php",
		//url: "get_prop_topo.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("init_assign_role_tree_old::AJAX Return: ");
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


function init_assign_role_tree(arg){
	console.log("getting inside init_sel_prop_role_tree");
	console.log(arg.user_id);
	var object = {};
	object.user_id = arg.user_id;
	object.op = arg.op;
	object.m = arg.m;
	object.m_node = arg.m_node;
	object.s = arg.s;
	console.log(object);
	return $.ajax({
		method: "POST",
		url: "init_assign_role_tree.php",
		data: { k: JSON.stringify(object) },
		//dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("init_assign_role_tree::AJAX Return: ");
			console.log(data);
			var dj = JSON.parse(data);
			var status = dj.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
			} else {
				if (dj.ret_code == 0) {
					role_tree = dj.role;
					role_root = dj.root;
					role_root.children.push(role_tree);
					result = role_root;
					console.log ("get_prop_topo::after AJAX::topo_tree:: result");
					console.log ("********************acc_male_role Tree******************");
					console.log(result);
				} else {
					console.log("some issue inside success");
				}
			}
		},
		failure: function () {
			console.log("failure");
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

function ckeck_uncheck_a_node(data, state){
	var inst = $.jstree.reference('#assign_role_tree');
	var root_node = inst.get_node("PROP_ROOT");
	var all_sel = inst.get_selected();
	var user_id = root_node.data.user_id;
	console.log(user_id);
	var m = root_node.data.m;
	console.log(m);
	
	var op = "";
	if (state==true){
		op = "c";
	} else {
		op = "u";
	}
	var m_node_id = data.node.id;
	root_node.data = {user_id:user_id , op:op, m:m, m_node : m_node_id, s : all_sel};
	var get_children_dom = inst.get_children_dom("PROP_ROOT");
	
	$(get_children_dom).each(function (i, index) {
		var child_id = index.id;
		console.log(child_id);
		var get_child_node = inst.get_node(child_id);
		inst.delete_node(get_child_node); // delete the node
	});
	inst.refresh_node(root_node);
}

function clicked_on_update(){
	var inst = $.jstree.reference('#assign_role_tree');
	var root_node = inst.get_node("PROP_ROOT");
	var all_sel = inst.get_selected();
	var user_id = root_node.data.user_id;
	var m = root_node.data.m;
	console.log(m);
	console.log(user_id);
	console.log(all_sel);
	var op = "w";
	var m_node_id = "";
	root_node.data = {user_id:user_id , op:op, m:m, m_node : m_node_id, s:all_sel};
	var get_children_dom = inst.get_children_dom("PROP_ROOT");
	
	$(get_children_dom).each(function (i, index) {
		var child_id = index.id;
		console.log(child_id);
		var get_child_node = inst.get_node(child_id);
		inst.delete_node(get_child_node); // delete the node
	});
	inst.refresh_node(root_node);
}

function col1_cls_clicked() {
	console.log("col1_cls_clicked called");
	//alert("col1_cls_clicked called");
	var ctx_id = $(this).data("ctx_id");
	//alert(ctx_id);
	clicked_on_user_name(ctx_id);
}

function table_population(res){
	$("#sel_prop_tbl_container").tablecreator("clearTable");
    $(res).each(function (i, net) {
       
		var user_name = net.user_name;
		var unit_name = net.unit_name;
		var capacity = net.capacity;
		var ctx_id = net.ctx_id;
        
        var tr = $("<tr/>");
        var td1 = $("<td/>");
		var td2 = $("<td/>");
		var td3 = $("<td/>");
        var anc = $("<a/>");
        var role_row_id = Math.floor(Math.random() * 100000);
		//tr.data("ctx_id", ctx_id);
		tr.data("ctx_id", ctx_id);
		//anc.attr("id", "a-" + role_sig);
		tr.addClass("user_name_col_style");
		tr.click(col1_cls_clicked);
		td1.addClass("col1_cls");

        anc.html(user_name);
        td1.append(anc);
		td2.html(unit_name);
		td3.html(capacity);
    
        tr.append(td1);
		tr.append(td2);
		tr.append(td3);
        $("#sel_prop_tbl_container").tablecreator("createRow", { sig: "a-" + role_row_id, tablerow: tr });
    });
}

function selected_resident(){
	console.log("getting inside selected_resident");
	var object = {};
	$.ajax({
		method: "POST",
		url: "selected_resident.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("selected_resident::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if(data.ret_code == 0) { 
				   var res = data.residents;
				   table_population(res);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function selected_nonresident(){
	console.log("getting inside selected_nonresident");
	var object = {};
	$.ajax({
		method: "POST",
		url: "selected_nonresident.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("selected_nonresident::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if(data.ret_code == 0) { 
				   var res = data.residents;
				   table_population(res);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function fill_drop_down(){
	$('#fold1_res_op').attr("selected", true);
	$('#fold1_dd').change();
}

function on_page_load_table(){
	fill_drop_down();
}


$(document).ready(function(){
    

	$('#assign_role_tree').jstree({
		"checkbox" : {
			"three_state" : false,
			"tie_selection": true,
			"keep_selected_style": false,
			"visible": true,
			
		  },
		'plugins': ["checkbox", "types"],
		
		'core' : {
		

			'data' : function (target_node_obj, callback) {
				console.log("assign role JSTREE loading");
				console.log(target_node_obj);
				console.log("target_node_obj.data");
				console.log(target_node_obj.data);
				
				var argobj = {};
				if (target_node_obj.data==null){
					argobj.user_id = "0";
					argobj.op = "";
					argobj.m = "";
					argobj.m_node = "";
					argobj.s = "";
				} else {
					console.log(target_node_obj.data.m);
					argobj.user_id = target_node_obj.data.user_id;
					argobj.op = target_node_obj.data.op;
					argobj.m = target_node_obj.data.m;
					argobj.m_node = target_node_obj.data.m_node;
					argobj.s = target_node_obj.data.s;
				}
				console.log(argobj);
				init_assign_role_tree(argobj).done(function(result){ 
					console.log ("assign role JSTREE FEEDER: " + result);
					console.log(this);
					console.log(argobj);
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
				"a_attr": {"class":"rolecat_no_check"}
				
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
				"a_attr": {"class":"rolecat_no_check"}
			},
			"role_disable" : {
				"icon" : "../ext-styles/themes/default/ricon24.png?"+new Date().getTime(),
				"a_attr": {"class":"role_disable_class"},
				"select_node" : false,

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

	$('#assign_role_tree').on('check_node.jstree', function (e, data) {
		console.log("checked");
		alert("checked");
	});

	$('#assign_role_tree').on('enable_checkbox.jstree', function (e, data) {
		//console.log("checked");
		alert("enable_checkbox");
	});

	$('#assign_role_tree').on('select_node.jstree', function (e, data) {
		console.log("selected");
		console.log(data);
		console.log(data.node);
		ckeck_uncheck_a_node(data, true);

	});

	$('#assign_role_tree').on('deselect_node.jstree', function (e, data) {
		console.log("deselected");
		console.log(data);
		console.log(data.node);
		ckeck_uncheck_a_node(data, false);

	});


	$('#user_role_tree').on('load_node.jstree', function (e, data) {
		alert("load node");
	});

	$("#update_button").on('click', function (e) {
		console.log("clicked on UPDATE button");
		clicked_on_update();
	});

	

	
	$("#fold1_dd").change(function (ev) {
		console.log("changed");
		//alert("changed");
		var selected_type_val = $("#fold1_dd").val();
		var selected_type_txt = $("#fold1_dd option:selected").text();
		console.log(selected_type_val);
		console.log(selected_type_txt);
		if(selected_type_val == "res"){
			selected_resident();
		}else if(selected_type_val == "nonres"){
			selected_nonresident();
		}
		var inst = $.jstree.reference('#assign_role_tree');
		console.log(inst);
		var root_node = inst.get_node("PROP_odd_rowOT");
		console.log(root_node);
		var user_id = 0;
		root_node.data = {user_id: user_id};
		var get_children_dom = inst.get_children_dom("PROP_ROOT");
		console.log(get_children_dom);
		$(get_children_dom).each(function (i, index) {
			var child_id = index.id;
			console.log(child_id);
			var get_child_node = inst.get_node(child_id);
			inst.delete_node(get_child_node); // delete the node
			
		});
		inst.refresh_node(root_node);
	});

	on_page_load_table();

	$(".resident").on('click', function (e) {
		alert("clicked on tr");
	});

	$("#sel_prop_tbl_container").tablecreator({
		headingStyle: 'generic-table-heading buy_credit_directly_tbl_heading',/**style classes appends to header element */
		headingTextStyle: '',/**style classes appends to header element span */
		headingText: '',
		contentStyle: 'table_wrapper buy_credit_directly_tbl_wrapper',/**style classes appends to content div */
		contentTableStyle: 'generic_table instance_table_cls',/**style classes appends to content table */
		tableCols: [{ text: 'User Name', class: 'client_package_info_col1', colsig: 'usr_name' },{ text: 'Unit Name', class: 'client_package_info_col1', colsig: 'unit_name' }, { text: 'Capacity', class: 'client_package_info_col2', colsig: 'capacity' }],
		tableHeaderStyle: 'tableHeaderStyle',
	});

	/*
	$("#tbl1_container").tablecreator({
		headingStyle: 'generic-table-heading buy_credit_directly_tbl_heading',
		headingTextStyle: '',
		headingText: '',
		contentStyle: 'table_wrapper buy_credit_directly_tbl_wrapper',
		contentTableStyle: 'generic_table instance_table_cls',
		tableCols: [{ text: 'User Name', class: 'user_det_col1', colsig: 'usr_name' },{ text: 'Email', class: 'user_det_col2', colsig: 'email' }, { text: 'Phone Numbers', class: 'user_det_col3', colsig: 'phno' }, { text: 'Emergency Phone Numbers', class: 'user_det_col4', colsig: 'ephno' }, { text: 'blood Group', class: 'user_det_col5', colsig: 'bld_grp' }],
		tableHeaderStyle: 'tableHeaderStyle',
	});
	*/

	//$(".user_name_col_style").on('click',function(e){
		/*
	$(".odd_row").on('click',function(e){
        console.log("clicked on user name");
		console.log(this.id);
		var name = $(this).data("name");
		console.log(name);
		clicked_on_user_name(this.id, name);
		
		$('#assign_role_accordion').accordion("open_tab", 2);
		console.log ("Opened");
	});
	*/


	$(".col1_cls").on('click',function(e){
		alert("clicked on col 1");
	});
	
	/*
	$(this).on('click',function(e){
		alert("clicked on col 1");
	});
	*/

	
});