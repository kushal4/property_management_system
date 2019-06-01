function body_load_func() {
	var create_mul_prop = $("#create_mul_prop_btn_cont");
	button_creation(create_mul_prop, "create_mul_prop_btn", ["", "create_mul_prop_btn_style"], {}, ["create_mul_prop_anc_style"], {}, "CREATE", (function (el) {
		create_multiple_prop_checking();
		//$("#change_acc_id").dialog('close');
	}));

	var excel_button_cont = $("#heading_right_child_cont");
	button_creation(excel_button_cont, "excel_btn", ["", "excel_btn_style"], {}, ["excel_btn_anc_style"], {}, "Save as Excel", (function (el) {
		console.log("clicked on Save as Excel");
		download_excel();
	}));

	var save_user_prop = $("#owner_dets_table_btn_cont");
	button_creation(save_user_prop, "save_usr_prop_btn", ["", "excel_btn_style"], {}, ["excel_btn_anc_style"], {}, "SAVE!", (function (el) {
		console.log("clicked on Save as Excel");
		//download_excel();
		save_owner_dets_to_db();
	}));

}

function save_owner_dets_to_db(){
    owner_dets__sense_table ("owner_dets_table_container", "save_owner_dets.php");
}

function owner_dets__sense_table (tbl_id, post_url) {
    console.log("getting inside owner_dets__sense_table");
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
		//dataType: "json",
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
                    
				} else {
				
				}
			}
		},
		failure: function () {
		}
    });
    
}


function get_prop_topo_orig(){
	console.log("getting inside get_prop_topo_orig");
	
    var object = {};
	$.ajax({
		method: "POST",
		url: "get_prop_topo.php",
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
					
                    $(data.topo).each(function (i, val) {
						var prop_name = val.prop_name;
						var prop_id = val.root_id;
						console.log("the property name is"+prop_name);
						console.log("the property name is"+prop_id);
						//createNode("prop_topo_tree","#", "root", prop_name, "last");
						//$("#prop_topo_tree").jstree('create_node', '#', {'id' : 'myId', 'text' : 'My Text'}, 'last');
						$('#prop_topo_tree').on('ready.jstree', function (e, data) {
							createNode("prop_topo_tree","#", prop_id, prop_name, "last");
						});
					});
                    
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});

}

function get_prop_topo(){
	console.log("getting inside get_prop_name");
	var object = {};
	var topo_tree;
	
	return $.ajax({
		method: "POST",
		url: "get_prop_topo.php",
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
					//result = JSON.stringify(data.topo);
					result = data.topo;
					console.log ("get_prop_topo::after AJAX::topo_tree:: result");
					console.log(result);
					console.log(result.children);
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
		  		def.resolveWith({},[result]);
			}).promise();
	  });

	
}

function createNode(jstree_id,parent_node, new_node_id, new_node_text, position) {
	if(parent_node == "#"){
		$('#'+jstree_id).jstree('create_node',parent_node, { "text":new_node_text, "id":new_node_id }, position, false, false);	
	} else{
		$('#'+jstree_id).jstree('create_node', $(parent_node), { "text":new_node_text, "id":new_node_id }, position, false, false);	
	}
}


function prop_topo_create_node_old(parent_id, node_id, node_name){
	console.log("getting inside get_prop_name");
	var object = {};
	object.parent_id = parent_id;
	object.node_id = node_id;
	object.node_name = node_name;
	$.ajax({
		method: "POST",
		url: "prop_topo_create_node.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("prop_topo_create_node_old::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
			} else {
				if (data.ret_code == 0) {
					var node_id = data.last_id;
					console.log("entered success");
					
					$('#prop_topo_tree').on('rename_node.jstree', function (e, obj ) {
						$(this).jstree(true).set_id(obj.node, node_id);
                    });
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function prop_topo_create_node(jstree_cont_jqobj, jstree_node){
	console.log("getting inside prop_topo_create_node");
	console.log(jstree_node);
	console.log(jstree_node.type);
	var create_type = jstree_node.type;
	if(create_type == "unit"){
		var type = 1;
	}else{
		var type = 0;
	}
	console.log("the unit is" + type);
	var parent_id = jstree_node.parent;
	var node_id = jstree_node.id;
	var node_name = jstree_node.text;
	var object = {};
	object.parent_id = parent_id;
	object.node_id = node_id;
	object.node_name = node_name;
	object.unit = type;
	$.ajax({
		method: "POST",
		url: "prop_topo_create_node.php",
		data: { k: JSON.stringify(object) },
		//dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("prop_topo_create_node::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					var new_node_id = data.last_id;
					console.log("entered success");
					console.log("prop_topo_create_node: at AJAX success...");
					console.log(jstree_cont_jqobj);
					console.log(jstree_cont_jqobj.jstree(true));
					jstree_cont_jqobj.jstree(true).set_id(jstree_node, new_node_id);
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}



function rename_node(node_id, new_node_name){
	console.log("getting inside rename_node");
	console.log("the node ID is::"+node_id);
	var node_id_str = node_id.toString();
	console.log("the node ID string is::"+node_id_str);
	var object = {};
	object.node_id = node_id_str;
	object.node_name = new_node_name;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "rename_node.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("rename_node::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
			} else {
				if (data.ret_code == 0) {
					
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function move_node(data){
	console.log("getting inside move_node");
	console.log(data);
	console.log("the old parent is::"+ data.old_parent);
	console.log("the new parent is::"+ data.parent);
	console.log("the moving node type is::"+ data.node.type);
	var object = {};
	object.old_parent = data.old_parent;
	object.parent = data.parent;
	object.mov_node_id = data.node.id;
	object.mov_typ_type = data.node.type;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "move_node.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("rename_node::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
			} else {
				if (data.ret_code == 2) {
					console.log(data.ret_code);
					$('#prop_topo_tree').jstree(true).refresh();					
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function delete_node(obj){
	console.log("getting inside delete_node");
	console.log(obj);
	console.log("the deleted node ID is::"+obj.node.id);
	console.log("the deleted node's parent ID is::"+obj.parent);
	
	var object = {};
	object.node_id = obj.node.id;
	object.parent_id = obj.parent;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "delete_node.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("rename_node::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
			} else {
				if (data.ret_code == 0) {
					
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}



function user_details_dialog(unit_id){
	console.log("getting inside user_details_dialog");
	
	var object = {};
	object.unit_id = unit_id;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "user_details_dialog.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("user_details_dialog::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
			} else {
				if(data.ret_code == 1){
					var ret_message = data.ret_msg;
					var ret_message2 = data.ret_msg2;
					$("#no_user_err_span_cont").show();
					//$("#unit_name_span").text(ret_message);
					$("#no_user_err_span").text(ret_message);
					$("#add_new_user_span").text(ret_message2);
					//add_new_user_span
					
					$("#add_new_user_parent_cont").show();
				}
				else if(data.ret_code == 2){
					var ret_message = data.ret_msg;
					$("#no_user_err_span_cont").show();
					$("#no_user_err_span").text(ret_message);
					$("#add_new_user_parent_cont").hide();
					
				}
			}
		},
		failure: function () {
		}
	});
}

function unit_details_dialog(unit_id){
	console.log("getting inside unit_details_dialog");
	var object = {};
	object.unit_id = unit_id;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "unit_details_dialog.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("unit_details_dialog::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
			} else {
				if(data.ret_code == 0){
					var unit_name = data.unit_name;
					console.log(unit_name);
					//var ret_message2 = data.ret_msg2;
					//$("#no_user_err_span_cont").show();
					$("#unit_dets_view_panel_heading_span").text(unit_name);
					//$("#add_new_user_span").text(ret_message2);
					//$("#add_new_user_parent_cont").show();
				}
				else if(data.ret_code == 2){
					/*
					var ret_message = data.ret_msg;
					$("#no_user_err_span_cont").show();
					$("#no_user_err_span").text(ret_message);
					$("#add_new_user_parent_cont").hide();
					*/
				}
			}
		},
		failure: function () {
		}
	});
}


function addCustomMenuItem(itm, key, obj){
	itm[key] = obj;
}

function customMenu(node) {
	// The default set of all items
	target_node_id = node.id;
	isRoot = ($("#"+target_node_id).hasClass("topo_root"));
	console.log("customMenu");
	console.log(node);
	console.log(node.data);
	console.log(node.type);
	console.log("The user ID is: "+node.original.user_id);
	var user_det_flag = node.original.ud;
	var isGrp=false;
	
	if (node.type != null) {
		isGrp = (node.type=="group");
	}

	console.log("isGrp");
	console.log(isGrp);
	if (isRoot) {
		var items = {};
		
			obj = 	{ // The "rename" menu item
					"label": "Create Multiple",
					"action"			: function (data) {
						//console.log("refresh");
						var inst = $.jstree.reference(data.reference);
						obj = inst.get_node(data.reference);
						//console.log(obj.id);
						var node_id = obj.id;
						$("#create_mul_prop").data("node_id",node_id);//parent id of multiple prop
						$("#create_mul_prop").dialog('open');
						//alert("create multiple");
						//inst.refresh();
					}
			};
			addCustomMenuItem(items, "refresh" , obj);
		
		
		obj = 	{ // The "rename" menu item
					"label": "Create Group",
					"action"			: function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);
						inst.create_node(obj, {"type" : "group"}, "last", function (new_node) {
							//inst.set_icon(new_node,"../ext-styles/themes/default/group.ico?"+new Date().getTime());
							try {
								inst.edit(new_node);
							} catch (ex) {
								setTimeout(function () { inst.edit(new_node); },0);
							}
						});
					}
				};
		addCustomMenuItem(items, "createGroup" , obj);

		obj = 	{ // The "rename" menu item
					"label": "Create Unit",
					"action"			: function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);
						//inst.create_node(obj, {"data" : {"type" : "unit"}}, "last", function (new_node) {
						inst.create_node(obj, {"type" : "unit"}, "last", function (new_node) {
							//inst.set_icon(new_node,"../ext-styles/themes/default/unit.ico?"+new Date().getTime());
							try {
								inst.edit(new_node);
							} catch (ex) {
								setTimeout(function () { inst.edit(new_node); },0);
							}
						});
					}
				};
		addCustomMenuItem(items, "createUnit" , obj);
		/*
		var items = {
			"refresh": { // The "rename" menu item
				"label": "Create Multiple",
				"action": function (data) 	{
												//console.log("refresh");
												var inst = $.jstree.reference(data.reference);
												obj = inst.get_node(data.reference);
												//console.log(obj.id);
												var node_id = obj.id;
												$("#create_mul_prop").data("node_id",node_id);//parent id of multiple prop
												$("#create_mul_prop").dialog('open');
												//alert("create multiple");
												//inst.refresh();
											}
			},
			"createGroup": { // The "rename" menu item
				"label": "Create Group",
				"action": function (data) 	{
					console.log("createGroup");
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					inst.create_node(obj, {"data" : {"type" : "group"}}, "last", function (new_node) {
						try {
							inst.edit(new_node);
						} catch (ex) {
							setTimeout(function () { inst.edit(new_node); },0);
						}
					});
				}
			},
			"createUnit": { // The "rename" menu item
			"label": "Create Unit",
			"action"			: function (data) {
				var inst = $.jstree.reference(data.reference),
					obj = inst.get_node(data.reference);
				inst.create_node(obj, {"data" : {"type" : "unit"}}, "last", function (new_node) {
					try {
						inst.edit(new_node);
					} catch (ex) {
						setTimeout(function () { inst.edit(new_node); },0);
					}
				});
			}
		}
		};
		*/
	} else {
		var items = {};
		if (isGrp) {
			obj = 	{ // The "Create Multiple" menu item
					"label": "Create Multiple",
					"action": function (data) {
						//console.log("refresh");
						var inst = $.jstree.reference(data.reference);
						obj = inst.get_node(data.reference);
						//console.log(obj.id);
						var node_id = obj.id;
						$("#create_mul_prop").data("node_id",node_id);//parent id of multiple prop
						$("#create_mul_prop").dialog('open');
						//alert("create multiple");
						//inst.refresh();
					}
			};
			addCustomMenuItem(items, "refresh" , obj);
		}
		if (isGrp) {
			obj = 	{ // The "Create Group" menu item
			"label": "Create Group",
			"action": function (data) {
				var inst = $.jstree.reference(data.reference),
					obj = inst.get_node(data.reference);
				inst.create_node(obj, {"type" : "group"}, "last", function (new_node) {
					//console.log("context_menu:create_node");
					//console.log(new_node);
					//inst.set_icon(new_node,"../ext-styles/themes/default/group.ico?"+new Date().getTime());
					try {
						inst.edit(new_node);
					} catch (ex) {
						setTimeout(function () { inst.edit(new_node); },0);
					}
				});
			}
		};
			addCustomMenuItem(items, "createGroup" , obj);
		}
		
		if (isGrp == false && user_det_flag == 1) {
			obj = 	{ // The "Create Group" menu item
			"label": "User Details",
			"action": function (data) {
				var inst = $.jstree.reference(data.reference),
					obj = inst.get_node(data.reference);
					//console.log("context menu user dtails clicked");
					console.log(obj);
					//console.log("the user is is:: ")+user_id;
					var unit_id = obj.id;
					$("#user_dets_dialog").data("unit_id", unit_id);
					$("#user_dets_dialog").dialog('open');
					
			}
		};
			addCustomMenuItem(items, "userDetails" , obj);
		}

		if (isGrp == false && user_det_flag == 1) {
			obj = 	{ // The "Create Group" menu item
			"label": "Unit Details",
			"action": function (data) {
				var inst = $.jstree.reference(data.reference),
					obj = inst.get_node(data.reference);
					console.log(obj);
					var unit_id = obj.id;
					console.log(unit_id);
					$("#unit_dets_dialog").data("unit_id", unit_id);
					$("#unit_dets_dialog").dialog('open');
					
			}
		};
			addCustomMenuItem(items, "unitDetails" , obj);
		}

		/*
		obj = 	{ // The "Create Unit" menu item
					"label": "Create Unit",
					"action"			: function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);
						inst.create_node(obj, {"type" : "unit"}, "last", function (new_node) {
							//inst.set_icon(new_node,"../ext-styles/themes/default/unit.ico?"+new Date().getTime());
							try {
								inst.edit(new_node);
							} catch (ex) {
								setTimeout(function () { inst.edit(new_node); },0);
							}
						});
					}
				};
		addCustomMenuItem(items, "createUnit" , obj);
		*/

		obj = 	{ // The "rename" menu item
					"label": "Rename",
					"action"			: function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);
						inst.edit(obj);
					}
				};
		addCustomMenuItem(items, "renameItem" , obj);

		obj  =	{ // The "delete" menu item
					"label": "Delete",
					"action"			: function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);
							console.log(obj);
						if(inst.is_selected(obj)) {
							//alert("deleted");
							inst.delete_node(inst.get_selected());
							
						}
						else {
							inst.delete_node(obj);
						}
					}
				};
		addCustomMenuItem(items, "deleteItem" , obj);
		
	}
	return items;
}

function create_mul_property(radio_slctd_val, prop_num, node_id, dd1_sel, textbox_val1, dd2_sel, textbox_val2, dd3_sel, textbox_val3, dd4_sel, textbox_val4){
	//alert("inside create_multiple_prop");
	console.log("getting inside get_prop_name");
	var object = {};	
	object.rad_sel = radio_slctd_val;
	object.prop_num = prop_num;
	object.node_id = node_id;
	object.dd1_sel = dd1_sel;
	object.textbox_val1 = textbox_val1;
	object.dd2_sel = dd2_sel;
	object.textbox_val2 = textbox_val2;
	object.dd3_sel = dd3_sel;
	object.textbox_val3 = textbox_val3;
	object.dd4_sel = dd4_sel;
	object.textbox_val4 = textbox_val4;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "create_mul_property.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("create_mul_property::AJAX Return: ");
			console.log(data);
			console.log(data.ret_code);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
				
			} else {
				console.log(data.ret_code);
				var ret_code = data.ret_code; 
				var dd1_ret_code = data.ret_code1;
				var dd2_ret_code = data.ret_code2;
				var dd3_ret_code = data.ret_code3;
				var dd4_ret_code = data.ret_code4;
				var ret_message = data.ret_message;
				var dd1_ret_message = data.ret_message1;
				var dd2_ret_message = data.ret_message2;
				var dd3_ret_message = data.ret_message3;
				var dd4_ret_message = data.ret_message4;
				
				if (ret_code == 1) {
					console.log("retcode 1");
					$("#error_span_mul_prop").addClass("ajax_err_show");
					$("#error_span_mul_prop").removeClass("ajax_err_hide");
					$("#error_span_mul_prop").text(ret_message);
				}else if(ret_code == 2){
					console.log("retcode 1");
					$("#error_span_mul_prop").addClass("ajax_err_show");
					$("#error_span_mul_prop").removeClass("ajax_err_hide");
					$("#error_span_mul_prop").text(ret_message);
				}
				if(dd1_ret_code == 1 || dd1_ret_code == 2 || dd1_ret_code == 3 || dd1_ret_code == 4){
					$("#error_span_dd1_span").addClass("ajax_err_show");
					$("#error_span_dd1_span").removeClass("ajax_err_hide");
					$("#error_span_dd1_span").text(dd1_ret_message);
				}
				if(dd2_ret_code == 1 || dd2_ret_code == 2 || dd2_ret_code == 3 || dd2_ret_code == 4){
					
					$("#error_span_dd1_span").addClass("ajax_err_show");
					$("#error_span_dd1_span").removeClass("ajax_err_hide");
					$("#error_span_dd1_span").text(dd2_ret_message);
				}
				if(dd3_ret_code == 1 || dd3_ret_code == 2 || dd3_ret_code == 3 || dd3_ret_code == 4){
					$("#error_span_dd1_span").addClass("ajax_err_show");
					$("#error_span_dd1_span").removeClass("ajax_err_hide");
					$("#error_span_dd1_span").text(dd3_ret_message);
				}
				if(dd4_ret_code == 1 || dd4_ret_code == 2 || dd4_ret_code == 3 || dd4_ret_code == 4){
					$("#error_span_dd1_span").addClass("ajax_err_show");
					$("#error_span_dd1_span").removeClass("ajax_err_hide");
					$("#error_span_dd1_span").text(dd4_ret_message);
				}

				if ( (ret_code == 0) && (dd1_ret_code == 0) && (dd2_ret_code == 0) && (dd3_ret_code == 0) && (dd4_ret_code == 0) ){
					$("#create_mul_prop").dialog('close');
					$('#prop_topo_tree').jstree(true).refresh();
				}
			}
		},
		failure: function () {
			console.log("ajax failure");
		},
		error: function () {
			console.log("ajax error");
		}
	});
}


function create_multiple_prop_checking(){
	console.log("getting inside create_multiple_prop");
	var radio_slctd_val =$("input[name='prop_typ_slctr']:checked").val();
	console.log("the value of radio button is:"+ radio_slctd_val);
	
	var prop_num = $("#prop_num_textbox").val();
	console.log("the propety number is:: "+ prop_num);

	var node_id = $("#create_mul_prop").data("node_id");
	console.log("the node ID is:: "+ node_id);

	//var format1_alp = $("#first_dd_sel_alp_textbox").val();
	//console.log("format1 alphanet order start alphabet::"+format1_alp);

	//var format1_let = $("#first_dd_sel_let_textbox").val();
	//console.log("format1 letter order start number"+format1_let);
	

	var textbox_val1 = $("#first_dd_sel_textbox").val();
	console.log("first textbox value:: "+textbox_val1);

	var textbox_val2 = $("#second_dd_sel_textbox").val();
	console.log("second textbox value:: "+textbox_val2);

	var textbox_val3 = $("#third_dd_sel_textbox").val();
	console.log("third textbox value:: "+textbox_val3);

	var textbox_val4 = $("#fourth_dd_sel_textbox").val();
	console.log("fourth textbox value:: "+textbox_val4);

	var dd1_sel = $("#name_format_dropdown_1").find(":selected").text();
	console.log("the selected val of textbox1 is"+ dd1_sel);
	var dd2_sel = $("#name_format_dropdown_2").find(":selected").text();
	console.log("the selected val of textbox2 is"+ dd2_sel);
	var dd3_sel = $("#name_format_dropdown_3").find(":selected").text();
	console.log("the selected val of textbox3 is"+ dd3_sel);
	var dd4_sel = $("#name_format_dropdown_4").find(":selected").text();
	console.log("the selected val of textbox4 is"+ dd4_sel);


	create_mul_property(radio_slctd_val, prop_num, node_id, dd1_sel, textbox_val1, dd2_sel, textbox_val2, dd3_sel, textbox_val3, dd4_sel, textbox_val4);

	
}

$(document).ready(function () {
	$("#first_dd_sel_textbox").on('blur', function (e) {
		var err_span = "#error_span_dd1_span";
		validate_while_bluring("name_format_dropdown_1", "first_dd_sel_textbox", err_span);
		
	});

	$("#second_dd_sel_textbox").on('blur', function (e) {
		var err_span = "#error_span_dd2_span";
		validate_while_bluring("name_format_dropdown_2", "second_dd_sel_textbox", err_span);
	});

	$("#third_dd_sel_textbox").on('blur', function (e) {
		var err_span = "#error_span_dd3_span";
		validate_while_bluring("name_format_dropdown_3", "third_dd_sel_textbox", err_span);
	});

	$("#fourth_dd_sel_textbox").on('blur', function (e) {
		var err_span = "#error_span_dd4_span";
		validate_while_bluring("name_format_dropdown_4", "fourth_dd_sel_textbox", err_span);
	});
});

function validate_while_bluring(dd_id, textbox_id, err_span){

	var dd_sel = $("#"+dd_id).find(":selected").text();
	console.log("the selected val of textbox is"+ dd_sel);	
	if(dd_sel == "alphabetical order"){
		console.log("alphabet");
		var textbox_val = $("#"+textbox_id).val();
		console.log("the value of textbox is:::::"+textbox_val);
		if(textbox_val != ""){
			var check_if_alp = isNaN(textbox_val);
			if(check_if_alp == false){
				$(err_span).addClass("ajax_err_show");
				$(err_span).removeClass("ajax_err_hide");
				$(err_span).text("Must be letter");
			}else{
				$(err_span).addClass("ajax_err_hide");
				//$(err_span).removeClass("ajax_err_show");
				//$(err_span).removeClass("ajax_err_hide");
				var alp_len = textbox_val.length;
				console.log("the length is"+alp_len);
				if(alp_len != 1){
					//alert("it should be a single letter");
					$(err_span).addClass("ajax_err_show");
					$(err_span).removeClass("ajax_err_hide");
					$(err_span).text("Must be a single letter");
				}else{
					$(err_span).addClass("ajax_err_hide");
					//$(err_span).removeClass("ajax_err_show");
					//$(err_span).removeClass("ajax_err_hide");
				}
			}
			
		}
	}else if(dd_sel == "numeric order"){
		
		var textbox_val = $("#"+textbox_id).val();
		console.log("the value of textbox is:::::"+textbox_val);
		if(textbox_val != ""){
			//alert("not null");
			var check_if_num = isNumeric(textbox_val);
			console.log("isNumeric result"+check_if_num);
			if(check_if_num == false){
				//alert("not number");
				//alert(err_span);
				$(err_span).addClass("ajax_err_show");
				$(err_span).removeClass("ajax_err_hide");
				$(err_span).text("Must be a number");
			}else{
				$(err_span).addClass("ajax_err_hide");
				//$(err_span).removeClass("ajax_err_show");
				//$(err_span).removeClass("ajax_err_hide");
				var alp_len = textbox_val.length;
				console.log("the length is"+alp_len);			
				if(alp_len > 3){
					$(err_span).addClass("ajax_err_show");
					$(err_span).removeClass("ajax_err_hide");
					$(err_span).text("3 digits Max");
				}else{
					$(err_span).addClass("ajax_err_hide");
					//$(err_span).removeClass("ajax_err_show");
					//$(err_span).removeClass("ajax_err_hide");
				}
			}
			
		}
		
		//console.log("#"+textbox_id);
		//document.getElementById(textbox_id).maxLength = "3";
		//document.getElementById(textbox_id).maxLength = "3";
		//document.getElementById(textbox_id).width = "50";
		//$("#"+textbox_id).width(30);
	}else if(dd_sel == "fixed"){
		//$(sel_cont).show();
		//$(sel_id).text("enter fixed value");
		//var textbox_val = $("#"+textbox_id).val();
		//console.log("the value of textbox is:::::"+textbox_val);
		//var format_alp = $("#"+textbox_id).val();
		//console.log("format fixed value is:: "+format_alp);
		//document.getElementById(textbox_id).maxLength = "16";
		//$("#"+textbox_id).width(100);

		var format_alp = $("#"+textbox_id).val();
		console.log("format1 alphanet order start alphabet"+format_alp);

		var pattern = /[!@#$%^&*()_+\=\[\]{};':"\\|,.<>\/?]/g;
		var result = pattern.test(format_alp);
		console.log(result);
		if(result == true){
			$(err_span).addClass("ajax_err_show");
			$(err_span).removeClass("ajax_err_hide");
			$(err_span).text("special characters not allowed");
		}
	}
}

function naming_format_dropdown_change_old(dropdown_id, sel_cont, sel_id, textbox_id, err_span){
	
	//$(err_span).addClass("ajax_err_show");
	$(err_span).removeClass("ajax_err_show");
	$(err_span).text("");
	//var a = "#name_format_dropdown_1";
	//alert("changed");
	console.log(dropdown_id);
	console.log(textbox_id);
	var selected_topic_text = $(dropdown_id).find(":selected").text();
	console.log("the value of the selected textbox is::" + selected_topic_text);
	//alert(selected_topic_text);

	//var selected_topic01 = $("+dropdown_id+").find(":selected").text();
	//var radio_slctd_val02=$('input[name='+pri_id+']:checked').val();
	//var target_text01 = $(selected_topic01).text();
	//console.log("the selected text is::"+target_text01);
	//validate_naming_format(sel_cont, sel_id, textbox_id, err_span, selected_topic_text);

	if(selected_topic_text == "alphabetical order"){
		console.log("alphabet");
		$(sel_cont).show();
		$(sel_id).text("Start alphabet");
		$("#"+textbox_id).width(15);
		//$("#first_dd_sel_let_cont").hide();
		//$("#first_dd_sel_fixed_cont").hide();
		var textbox_val = $("#"+textbox_id).val();
		console.log("the value of textbox is:::::"+textbox_val);
		
		if(textbox_val != ""){
			var check_if_alp = isNaN(textbox_val);
			if(check_if_alp == false){
				//alert("not alphabet");
				$(err_span).addClass("ajax_err_show");
				$(err_span).removeClass("ajax_err_hide");
				$(err_span).text("it is not an alphabet");
			}
			var alp_len = textbox_val.length;
			console.log("the length is"+alp_len);
			if(alp_len != 1){
				//alert("it should be a single letter");
				$(err_span).addClass("ajax_err_show");
				$(err_span).removeClass("ajax_err_hide");
				$(err_span).text("Must be a single letter");
			}
		}
		
		/*
		$("#"+textbox_id).focusout(function(){
			//alert("on blur alpha");
			var format_alp = $("#"+textbox_id).val();
			console.log("format1 alphanet order start alphabet"+format_alp);
			var alp_len = format_alp.length;
			console.log("the length is"+alp_len);
			if(alp_len != 1){
				//alert("it should be a single letter");
				$(err_span).addClass("ajax_err_show");
				$(err_span).removeClass("ajax_err_hide");
				$(err_span).text("Must be a single letter");
			}
			var check_if_alp = isNaN(format_alp);
			if(check_if_alp == false){
				alert("not alphabet");
				$(err_span).addClass("ajax_err_show");
				$(err_span).removeClass("ajax_err_hide");
				$(err_span).text("it is not an alphabet");
			}
		});
		*/
		
	}else if(selected_topic_text == "numeric order"){
		console.log("number");	
		$(sel_cont).show();
		
		$(sel_id).text("Start number");
		//$(textbox_id).maxlength(3);
		var textbox_val = $("#"+textbox_id).val();
		console.log("the value of textbox is:::::"+textbox_val);
		
		if(textbox_val != ""){
			var check_if_num = isNumeric(textbox_val);
			console.log("isNumeric result"+check_if_num);
			if(check_if_num == false){
				//alert("not alphabet");
				$(err_span).addClass("ajax_err_show");
				$(err_span).removeClass("ajax_err_hide");
				$(err_span).text("Must be a number");
			}
			var alp_len = textbox_val.length;
			console.log("the length is"+alp_len);
			
			if(alp_len > 3){
					$(err_span).addClass("ajax_err_show");
					$(err_span).removeClass("ajax_err_hide");
					$(err_span).text("3 digits Max");
			}
		}
		
		console.log("#"+textbox_id);
		document.getElementById(textbox_id).maxLength = "3";
		document.getElementById(textbox_id).maxLength = "3";
		//document.getElementById(textbox_id).width = "50";
		$("#"+textbox_id).width(30);

		/*
		$("#"+textbox_id).focusout(function(){
			//alert("on blur number");
			var format_alp = $("#"+textbox_id).val();
			console.log("format1 alphanet order start alphabet"+format_alp);
			var alp_len = format_alp.length;
			console.log("the length is"+alp_len);
			
			if(alp_len > 3){
					$(err_span).addClass("ajax_err_show");
					$(err_span).removeClass("ajax_err_hide");
					$(err_span).text("3 digits Max");
			}
			var format_alp = $("#"+textbox_id).val();
			console.log("format1 alphanet order start number"+format_alp);
			var check_if_num = isNumeric(format_alp);
			console.log("isNumeric result"+check_if_num);
			if(check_if_num == false){
				//alert("not alphabet");
				$(err_span).addClass("ajax_err_show");
				$(err_span).removeClass("ajax_err_hide");
				$(err_span).text("Must be a number");
			}
		});

		*/
	}else if(selected_topic_text == "fixed"){
		$(sel_cont).show();
		$(sel_id).text("enter fixed value");
		var textbox_val = $("#"+textbox_id).val();
		console.log("the value of textbox is:::::"+textbox_val);
		//var format_alp = $("#"+textbox_id).val();
		//console.log("format fixed value is:: "+format_alp);
		document.getElementById(textbox_id).maxLength = "16";
		$("#"+textbox_id).width(100);

		
		$("#"+textbox_id).focusout(function(){
			
			var format_alp = $("#"+textbox_id).val();
			console.log("format1 alphanet order start alphabet"+format_alp);

			var pattern = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/g;
			var result = pattern.test(format_alp);
			console.log(result);
			if(result == true){
				$(err_span).addClass("ajax_err_show");
				$(err_span).removeClass("ajax_err_hide");
				$(err_span).text("special characters not allowed");
			}
		});
		
	}
	
}

function naming_format_dropdown_change(dropdown_id, sel_cont, sel_id, textbox_id, err_span){
	
	$(err_span).removeClass("ajax_err_show");
	$(err_span).text("");
	console.log(dropdown_id);
	console.log(textbox_id);
	var selected_topic_text = $("#"+dropdown_id).find(":selected").text();
	console.log("the value of the selected textbox is::" + selected_topic_text);
	//check_nam_format_validation();
	//validate_naming_format(sel_cont, sel_id, textbox_id, err_span, selected_topic_text);
	validate_while_bluring(dropdown_id, textbox_id, err_span);
	name_format_selection_change(sel_cont, sel_id, textbox_id, selected_topic_text);
}

function name_format_selection_change(sel_cont, sel_id, textbox_id, selected_topic_text){
	if(selected_topic_text == "alphabetical order"){
		$(sel_cont).show();
		$(sel_id).text("Start alphabet");
		$("#"+textbox_id).width(15);
	}else if(selected_topic_text == "numeric order"){
		$(sel_cont).show();
		$(sel_id).text("Start number");
		console.log("#"+textbox_id);
		document.getElementById(textbox_id).maxLength = "3";
		document.getElementById(textbox_id).maxLength = "3";
		$("#"+textbox_id).width(30);

	}else if(selected_topic_text == "fixed"){
		$(sel_cont).show();
		$(sel_id).text("enter fixed value");
		document.getElementById(textbox_id).maxLength = "16";
		$("#"+textbox_id).width(100);
	}
}


function show_flats(){
	console.log("getting inside show flats");
	$("#flat_table").tablecreator("clearTable");
	var object = {};
	console.log(object);
	$.ajax({
		method: "POST",
		url: "show_flats.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			//console.log("rename_node::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				
				$("#session_expire_dialog").dialog('open');
			} else {
				
				$(data).each(function (i, net) {
					var flat_name = net.node_name;
					var tr=$("<tr/>");
					var td1=$("<td/>").html(flat_name);
					tr.append(td1);
					var td2 = $("<td/>");
					tr.append(td2);
					$("#flat_table").tablecreator("createRow", { sig: "a-" + i, tablerow: tr });
				});
			}
		},
		failure: function () {
			console.log("getting failed");
		}
	});
}

function download_excel(){
	console.log("getting inside download_excel");
	var object = {};
	console.log(object);
	$.ajax({
		method: "POST",
		url: "download_excel.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("download_excel::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
			} else {
				if (data.ret_code == 0) {
					var excel_path = data.excel_path;
					console.log(excel_path);
					$("#heading_right_child_cont_anc").show();
					//var newUrl = 'output/test_save.xlsx';
					$("#excel_btn_anc").text("Download Excel");
					$("#excel_btn_anc").attr("href", excel_path);
				}
			}
		},
		failure: function () {
			console.log("AJAX failed");
		}
	});
}

function validate_email(unit_id, email, re_email, first_name, last_name, op_code){
	console.log("The unit ID is:: "+unit_id);
	console.log("The email ID is:: "+email);
	console.log("The repeated email ID is:: "+re_email);
	var object = {};
	object.unit_id = unit_id;
	object.first_name = first_name;
	object.last_name = last_name;
	object.email = email;
	object.re_email = re_email;
	object.op_code = op_code;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "validate_email.php",
		data: { k: JSON.stringify(object) },
		//dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("validate_email::AJAX Return: ");
			console.log(data);
			console.log(data.ret_code);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
			} else {
				if(data.ret_code == 0){
					var ret_msg = data.ret_msg;
					console.log(f_msg);
					$("#validate_err").show();
					$("#validate_err").text(ret_msg);
					$("#validate_cls_btn_cont").show();
					$("#validate_btn_cont").hide();
				}else if(data.ret_code == 1){
					console.log("the error message is::"+ ret_msg);
					var err_msg = data.ret_msg;
					$("#validate_err").show();
					$("#validate_err").text(err_msg);
				}else if(data.ret_code == 2){
					console.log("the error message is::"+ ret_msg);
					var err_msg = data.ret_msg;
					$("#validate_err").show();
					$("#validate_err").text(err_msg);
				}else if(data.ret_code == 3){
					console.log("the error message is::"+ ret_msg);
					var err_msg = data.ret_msg;
					$("#validate_err").show();
					$("#validate_err").text(err_msg);
				}else if(data.ret_code == 4){
					var err_msg = data.ret_msg;
					$("#validate_err").show();
					$("#validate_err").text(err_msg);
				}else if(data.ret_code == 5){
					var err_msg = data.ret_msg;
					$("#validate_err").show();
					$("#validate_err").text(err_msg);
				}else if(data.ret_code == 6){
					var err_msg = data.ret_msg;
					$("#validate_err").show();
					$("#validate_err").text(err_msg);
				}else if(data.ret_code == 7){
					var err_msg = data.ret_msg;
					$("#validate_err").show();
					$("#validate_err").text(err_msg);
				}
				if(data.ret_code == 15){
					var ret_msg = data.ret_msg;
					console.log(ret_msg);
					$("#validate_err_cont").show();
					$("#validate_err").show();
					//$("#first_email_parent_cont").hide();
					//$("#second_email_parent_cont").hide();
					
					
					$("#validate_err").text(ret_msg);
				}
				if(data.f_code == 8){
					var f_msg = data.f_msg;
					console.log(f_msg);
					$("#validate_err").show();
					$("#validate_err").text(f_msg);
					$("#validate_cls_btn_cont").show();
					$("#validate_btn_cont").hide();
				}				
			}
		},
		failure: function () {
			console.log("AJAX failed");
		}
	});
}

function u_details(unit_id){
	console.log("getting inside unit_details");
	var object = {};
	object.unit_id = unit_id;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "u_details.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("unit_details::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
			} else {
				if (data.ret_code == 0) {
					window.location.href='unit_details.php';
					console.log("rdirect");
				}
			}
		},
		failure: function () {
			console.log("AJAX failed");
		}
	});
}

function call_prop_def_fea_list(){
	$("#main_container").load("prop_def_fea_list.php #main_cont");
	/*
	console.log("getting inside unit_details");
	var object = {};
	object.unit_id = unit_id;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "prop_def_fea_list.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("unit_details::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
			} else {
				if (data.ret_code == 0) {
					
				}
			}
		},
		failure: function () {
			console.log("AJAX failed");
		}
	});
	*/
}

function set_feat_sig_in_sess(secedFeatSig, landing_url){
	console.log(landing_url);
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
					console.log(landing_url);
					if(landing_url == "prof_def.php"){
						window.location.href = "property_topology.php";
					}
					else if(landing_url == "prop_def_unit_type.php"){
						window.location.href = "prop_def_unit_type.php";
					}
					
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

function get_unit_name_and_type_name(unit_id){
	console.log("getting inside get_unit_name_and_type_name");
	var object = {};
	object.unit_id = unit_id;
	$.ajax({
		method: "POST",
		url: "get_unit_name_and_type_name.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("get_unit_name_and_type_name::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (status == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					
					var unit_name = data.unit_name;
					console.log("the unit name is :: "+unit_name);
					var unit_type_name = data.unit_type_name;
					console.log("the unit type name is :: "+unit_type_name);
					var dialog_title = "Unit Details for: " + unit_name + " (" + unit_type_name + ")";
					console.log("the dialog_title is :: "+dialog_title);
					$( "#unit_dets_dialog" ).dialog({ title: dialog_title });
				} else {
				
				}
			}
		},
		failure: function () {
		}
	});
}

$(document).bind("dnd_start.vakata", function(e, data) {
    console.log("Start dnd");
})
.bind("dnd_move.vakata", function(e, data) {
    console.log("Move dnd");
})
.bind("dnd_stop.vakata", function(e, data) {
    console.log("Stop dnd");
});
$("#prop_topo_tree").jstree({
    // tree...
}).bind("move_node.jstree", function(e, data) {
   console.log("Drop node " + data.node.id + " to " + data.parent);
});


$(document).ready(function(){
	//alert("boom");
	//call_property_definition_feature_table();
	//call_prop_def_fea_list();
	$("#create_mul_prop").dialog({
		width: 700,
		height: 300,
		dialogClass: 'generic_dialog',
		autoOpen: false,
		modal: true,
		close: function () {
			
		},

	});	

	$("#excel_dialog").dialog({
		width: 1200,
		height: 900,
		dialogClass: 'generic_dialog',
		autoOpen: false,
		modal: true,
		close: function () {
			
		},

	});	

	$("#user_dets_dialog").dialog({
		width: 700,
		height: 300,
		dialogClass: 'generic_dialog',
		autoOpen: false,
		modal: true,
		close: function () {
			
		},

	});	

	$("#unit_dets_dialog").dialog({
		width: 900,
		height: 500,
		dialogClass: 'generic_dialog',
		autoOpen: false,
		modal: true,
		close: function () {
			
		},
		open: function(){
			console.log("unit details dialog opened");
			var unit_id = $("#unit_dets_dialog").data("unit_id");
			console.log("The unit ID is:: "+unit_id);
			//unit_details_dialog(unit_id);
			var object = {};
			object.op = "v";
			object.unit_id = unit_id;
			get_unit_name_and_type_name(unit_id);
			
			$("#unit_dets_dia_cont").load("unit_details_dialog_view.php #main_cont", object, function(data){
				//console.log(data);
				//alert(data);
				$( ".attrib_accordion_style" ).accordion({
					collapsible: true,
					active: false,
					heightStyle:"content",
				  });
				$(".attach_unit_type_btn_cont_style").on('click',function(e){
					console.log("clicked on attach_unit_type_btn_cont_style");
					var object = {};
					object.op = "a";
					object.unit_id = unit_id;
					$("#unit_dets_dia_cont").load("unit_details_dialog_view.php #main_cont", object, function(data){
						var unit_type_sel_val = $("#select_unit_type_dd").val();
                		var unit_type_sel_txt = $("#select_unit_type_dd option:selected").text();
						console.log("unit_type_sel_val is :: "+ unit_type_sel_val);
						console.log("unit_type_sel_txt is :: "+ unit_type_sel_txt);
						if(unit_type_sel_txt == "Select Unit Type"){
							$("#add_unit_type_btn_cont").attr("disabled", true);
						}
						//else{
							$("#select_unit_type_dd").change(function(){
								console.log("chaned select unit type dropdown");
								$("#add_unit_type_btn_cont").attr("disabled", false);
							 	$("#add_unit_type_btn_cont").css("cursor", "pointer" );
								var unit_type_sel_val = $("#select_unit_type_dd").val();
								var unit_type_sel_txt = $("#select_unit_type_dd option:selected").text();
								console.log("unit_type_sel_val is :: "+ unit_type_sel_val);
								console.log("unit_type_sel_txt is :: "+ unit_type_sel_txt);
								console.log("The unit ID is:: "+unit_id);
								//add_unit_type_to_unit(unit_id, unit_type_sel_val);
								$("#add_unit_type_btn_cont").on('click',function(e){
									var object = {};
									object.op = "s";
									object.unit_id = unit_id;
									object.unit_type_id = unit_type_sel_val;
									$("#unit_dets_dia_cont").load("unit_details_dialog_view.php #main_cont", object, function(data){
										hidden_val = $("#hidden_elem_cont").val();
										console.log(hidden_val);
										if(hidden_val == "0"){
											
											var feat_tbl_object = {};
                                			feat_tbl_object.op = "u";
                                			$("#unit_dets_dia_cont").load("feat_list_tbl.php #main_cont", feat_tbl_object, function(){

											});	
											
										}
									});	
								});

							}); 
							//$("#add_unit_type_btn_cont").attr("disabled", false);
							//$("#add_unit_type_btn_cont").css("cursor", "pointer" );
						//}
					});
				});
				
			});
			
		},

	});	

	$('#user_dets_dialog').dialog({autoOpen: false}).
	//parent().find('div.ui-dialog-titlebar').addClass('abcccc');
	parent().find('span.ui-dialog-title').addClass('dialog_title_span_style');
	

	$("#excel_container_image").on('click',function(e){
		console.log("Clicked on excel");
		$("#excel_dialog").dialog('open');
        
	});

	/*
	$("#tabs_1").on('click',function(e){
		alert("bb");
	});
	*/
	/*
	$(this).on('click',function(obj){
		console.log("Clicked node ");
		//console.log(e);
		//console.log($(e));
		//console.log(e.target);
		//console.log(e.target.id);
		//node_id = e.target.id;
		console.log(obj);
	});
	*/


	/*
	$(document).on('click', '.jstree-anchor', function(e) {
		
	console.log(e);
	});
	*/
	$("#prop_topo_tree").bind("select_node.jstree", function(event, data) {

		var evt =  window.event || event;
		var button = evt.which || evt.button;
		console.log(evt);
		console.log(button);
		console.log(data);

		if( button != 1 && ( typeof button != "undefined")) return false; 

		if( button == 1){
			console.log("clicked on a node"); 
			var unit_id = data.node.id;
			console.log(data.node.id);
			var type = data.node.type;
			console.log("the type is:: "+ type);
			if(type == "unit"){
				u_details(unit_id);
			}
		}

	});
	

	/*
	$('#prop_topo_tree').on("select_node.jstree", function (e, data) { 
		//alert("node_id: " + data.node.id); 
		console.log("clicked on a node"); 
		var unit_id = data.node.id;
		console.log(data.node.id);
		var type = data.node.type;
		console.log("the type is:: "+ type);
		if(type == "unit"){
			u_details(unit_id);
		}
	});
	*/

	$("#add_new_user_yes_btn").on('click',function(e){ // no is clicked
		console.log("Clicked on want to add user");
		$(".dialog_title_span_style").text('Add new User to Unit');
		$("#add_new_user_parent_cont").hide(); 
		$("#no_user_err_span").hide();
		$("#add_email_parent_cont").show();
	});


	$("#users_container").on('click',function(e){ 
		window.location.href='user_details.php';
	});
	

	$("#add_new_user_no_btn").on('click',function(e){ // no is clicked
		console.log("Clicked on do not want to add user");
		$("#user_dets_dialog").dialog('close');
	});

	$("#validate_btn").on('click',function(e){ // no is clicked
		console.log("Clicked validate email button");
		var unit_id = $("#user_dets_dialog").data("unit_id");
		var email = $("#first_email_input").val();
		var re_email = $("#second_email_input").val();
		var first_name = $("#first_name_input").val();
		var last_name = $("#last_name_input").val();

		console.log("the unit ID is")+unit_id;

		var op_code = $("#user_dets_dialog").data("op");
		console.log("the op code is :: "+op_code);
		
		validate_email(unit_id, email, re_email, first_name, last_name, op_code);
	});

	$("#validate_cls_btn").on('click',function(e){ // no is clicked
		$("#user_dets_dialog").dialog('close');
	});

	/*
	$("#excel_btn_anc").on('click',function(e){
		console.log("clicked on download excel");
		//download_excel();
		//$("#heading_right_child_cont_anc").show();
	});
	*/
	
	$( "#excel_dialog" ).on( "dialogopen", function( event, ui ) {
		console.log("excel dialog open");
		$("#excel_btn_anc").text("Save as Excel");
		$("#excel_btn_anc").removeAttr("href");
		show_flats();
	} );

	$( "#user_dets_dialog" ).on( "dialogopen", function( event, ui ) {
		console.log("user details dialog open");
		var unit_id = $("#user_dets_dialog").data("unit_id");
		$(".dialog_title_span_style").text('User Details');
		$("#add_email_parent_cont").hide();
		$("#validate_cls_btn_cont").hide();
		$("#validate_btn_cont").show();
		$("#no_user_err_span").show();
		console.log("The unit ID is:: "+unit_id);
		user_details_dialog(unit_id);
		
		$("#first_email_input").val("");
		$("#second_email_input").val("");
		
		$("#first_name_input").val("");
		$("#last_name_input").val("");
		$("#validate_err").text("");
	});

	$( "#unit_dets_dialog" ).on( "dialogopen", function( event, ui ) {
		/*
		console.log("user details dialog open");
		var unit_id = $("#unit_dets_dialog").data("unit_id");
		console.log("The unit ID is:: "+unit_id);
		unit_details_dialog(unit_id);
		*/
		
		
	});


	$( "#create_mul_prop" ).on( "dialogopen", function( event, ui ) {
		console.log("create_mul_prop dialog opened");
		//prop_num_textbox
		$("#prop_num_textbox").val("");
		$("#first_dd_sel_textbox").val("");
		$("#second_dd_sel_textbox").val("");
		$("#third_dd_sel_textbox").val("");
		$("#fourth_dd_sel_textbox").val("");

		$("#name_format_dropdown_1").val("");
		$("#name_format_dropdown_2").val("");
		$("#name_format_dropdown_3").val("");
		$("#name_format_dropdown_4").val("");

		$("#first_dd_sel_cont").hide();
		$("#second_dd_sel_cont").hide();
		$("#third_dd_sel_cont").hide();
		$("#fourth_dd_sel_cont").hide();

	} );

	$("#flat_table").tablecreator({
		headingStyle: 'generic-table-heading client-table-heading',/**style classes appends to header element */
		headingTextStyle: '',/**style classes appends to header element span */
		headingText: '',
		contentStyle: 'flat_det_table_wrapper client_list_table_wrapper',/**style classes appends to content div */
		contentTableStyle: 'generic_table client_table_cls',/**style classes appends to content table */
		tableCols: [{ text: 'Flat Name', class: 'client-list-col1', colsig: 'name_id' }, { text: 'Details', class: 'client-list-col2', colsig: 'cli_id' }],
		tableHeaderStyle: 'tableHeaderStyle',
	});

	$(function () {

		$('#prop_topo_tree').on('ready.jstree', function (node, parent, position) {
			console.log ("jstree.ready event fired");
			var inst = $.jstree.reference("#prop_topo_tree"),
			//obj = inst.get_node(data.reference);
			//get_children_dom
			obj = inst.get_node(".jstree-node");
			root_node = $(".jstree-node");
			//console.log(inst);
			console.log(obj);
			cdom = inst.get_children_dom(root_node);
			console.log("cdom");
			console.log(cdom);
			//console.log(root_node);
			/*
			console.log(parent);
			console.log(parent.node);
			var parent_id = parent.parent;
			console.log("the load_node parent ID is::"+parent_id);
			//console.log(parent.node);
			var node_id = parent.node.id;
			var node_name = parent.node.text;
			console.log("the load_node ID is::"+node_id);
			console.log("the load_node name is::"+node_name);
			*/
		}).jstree({
			"deselect_all": true,
			'plugins': ["state", "contextmenu", "dnd", "types"],
			'contextmenu': {items: customMenu},
			'core': {
					
					//'data': [
								/*
								{
								"id": "base_directory",
								"text": "First Parent node",
								"state": {
								"opened": true
								},
								"children": [{
											"text": "Child 1",
											"id": "child_1"
											}]
								}
								*/
					//		],
					/*
					'data' : {
						//'url' : '/get/children/',
						'data' : function (node) {
							console.log("JSTREE loading");
							console.log(node);
							return { 'id' : node.id };
						}
					},
					*/

					'data' : function (obj, callback) {
						console.log("JSTREE loading");
						//console.log(this);
						//callback.call(this, ['Root 1', 'Root 2']);
						get_prop_topo().done(function(result){ 
							console.log ("JSTREE FEEDER: " + result);
							console.log(result);
							callback.call(this, result);	
						});
					},
					

					'check_callback': true
					},
			"types": {
				//"default" : {
				//	"icon" : "../ext-styles/themes/default/unit.ico?"+new Date().getTime()
				//},
				"unit" : {
					"icon" : "../ext-styles/themes/default/unit.ico?"+new Date().getTime(),

					"type": "unit"

				},
				"group" : {
					"icon" : "../ext-styles/themes/default/group.ico?"+new Date().getTime(),
					"type": "group"
				}

			}		
		}).on('create_node.jstree', function (node, parent, position) {
			console.log ("jstree.create_node event fired");
			console.log(parent);
			console.log(parent.node);
			var parent_id = parent.parent;
			console.log("the parent ID is::"+parent_id);
			//console.log(parent.node);
			var node_id = parent.node.id;
			var node_name = parent.node.text;
			console.log("the node ID is::"+node_id);
			console.log("the node name is::"+node_name);
			//prop_topo_create_node(parent_id, node_id, node_name);
			prop_topo_create_node($('#prop_topo_tree'), parent.node);
		});
	});

	$('#prop_topo_tree').on('ready.jstree', function (e, data) {
		//createNode("prop_topo_tree","#", "root", "Prop name", "last");
	});

	$('#prop_topo_tree').on('rename_node.jstree', function (e, obj ) {
		console.log("rename fired");
		console.log(obj);
		console.log("the new text is:::"+obj.node.text);
		console.log("the new text id is:::"+obj.node.id);
		var ref = $('#prop_topo_tree').jstree(true);
		var sel = ref.get_selected();
		console.log("the reference is");
		console.log(ref);
		console.log("the selected id is::");
		console.log(sel);
		var new_name = $("#"+sel).text();
		console.log("the new name of the node is:: "+new_name);
		rename_node(obj.node.id,obj.node.text);
	});

	$('#prop_topo_tree').on('delete_node.jstree', function (e, obj ) {
		//alert("on delete");
		console.log("on delete");
		console.log(obj);
		//console.log("the new text is:::"+obj.node.text);
		//console.log("the new text id is:::"+obj.node.id);
		//delete_node(obj);
		delete_node(obj);
	});
	
	$('#prop_topo_tree').on('move_node.jstree', function (e, data) {
		alert("moved");
		console.log("moved");
		console.log(data);
		console.log("the old parent is::"+ data.old_parent);
		console.log("the new parent is::"+ data.parent);
		move_node(data);
	});

	$(".generic_feature_name").on('click',function(e){
		console.log("the a feature is clicked");
		 var secedFeatSig = $(this).data("s");
		 console.log(secedFeatSig);
		 var sig = $(this).data("j");
		 //var secedFeatSigLower = strtolower(secedFeatSig);
		 //var secedFeatSigLower = secedFeatSig.toLowerCase()
		 var landingURL = sig + ".php";
		 //set_feat_sig_in_sess(secedFeatSig, landingURL);
	 });	

	 $(this).on('click',function(e){
		//console.log(e.target.className);
		//console.log(e);
		
		var class_name = e.target.className;
		if(class_name == "generic_feature_name"){
			console.log(e.target.id);
			var sig_j = e.target.dataset.j;
			var sig_s = e.target.dataset.s;
			console.log(sig_j);
			if(sig_j == "prop_def_topo"){
				var landingURL = "prof_def.php";
				set_feat_sig_in_sess(sig_s, landingURL);
			}else if(class_name == "generic_feature_name"){
				var landingURL = "prop_def_unit_type.php";
				set_feat_sig_in_sess(sig_s, landingURL);
			}
			//set_feat_sig_in_sess(secedFeatSig, landingURL);
		}
	 });	


	$("#name_format_dropdown_1").change(function (ev) {
		console.log("select option changed of 1st dropdown");
		//first_naming_format_change();
		var dropdown_id = "name_format_dropdown_1";
		var alp_cont_id = "#first_dd_sel_cont";
		var let_cont_id = "#first_dd_sel_span";
		var textbox_id = "first_dd_sel_textbox";
		var err_span = "#error_span_dd1_span";
		naming_format_dropdown_change(dropdown_id, alp_cont_id, let_cont_id, textbox_id, err_span);
		
	});

	$("#name_format_dropdown_2").change(function (ev) {
		console.log("select option changed of 2nd dropdown");
		//second_naming_format_change();
		var dropdown_id = "name_format_dropdown_2";
		var sel_cont = "#second_dd_sel_cont";
		var span_id = "#second_dd_sel_span";
		var textbox_id = "second_dd_sel_textbox";
		var err_span = "#error_span_dd2_span";
		naming_format_dropdown_change(dropdown_id, sel_cont, span_id, textbox_id, err_span);
	});

	$("#name_format_dropdown_3").change(function (ev) {
		console.log("select option changed of 3rd dropdown");
		//third_naming_format_change();
		var dropdown_id = "name_format_dropdown_3";
		var sel_cont = "#third_dd_sel_cont";
		var span_id = "#third_dd_sel_span";
		var textbox_id = "third_dd_sel_textbox";
		var err_span = "#error_span_dd3_span";
		naming_format_dropdown_change(dropdown_id, sel_cont, span_id, textbox_id, err_span);
	});

	$("#name_format_dropdown_4").change(function (ev) {
		console.log("select option changed of 4th dropdown");
		//fourth_naming_format_change();
		var dropdown_id = "name_format_dropdown_4";
		var sel_cont = "#fourth_dd_sel_cont";
		var span_id = "#fourth_dd_sel_span";
		var textbox_id = "fourth_dd_sel_textbox";
		var err_span = "#error_span_dd4_span";
		naming_format_dropdown_change(dropdown_id, sel_cont, span_id, textbox_id, err_span);
	});

	$("#be4wqfy0ip7z4RVD").on('click', function (e) {
		alert("clicked on unit where ID is");
	});

	$("#unit_dets_tbl_cont").tablecreator({
		headingStyle: 'generic-table-heading client-table-heading',/**style classes appends to header element */
		headingTextStyle: '',/**style classes appends to header element span */
		headingText: '',
		contentStyle: 'table_wrapper client_list_table_wrapper',/**style classes appends to content div */
		contentTableStyle: 'generic_table unit_det_table_cls',/**style classes appends to content table */
		tableCols: [{ text: 'column 1', class: 'unit_details_col1', colsig: 'role_id' }, { text: 'column 2', class: 'unit_details_col2', colsig: 'cross2' }, { text: 'column 3', class: 'unit_details_col1', colsig: 'cross3' }],
		tableHeaderStyle: 'tableHeaderStyle',
	});
});