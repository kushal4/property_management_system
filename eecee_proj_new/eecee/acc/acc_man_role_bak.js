
function body_load_func() {
	//init_role_tree()
}

function createRoleCatOld(jstree_cont_jqobj, jstree_node) {
	console.log("getting inside existing_prop_dash");
	var object = {};
	object.node_id = node_id;
	$.ajax({
		method: "POST",
		url: "../acc/create_role_category.php",
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
				if (data.ret_code == 0) {
					$('.role_tree_man').jstree(true).refresh();
				} else {

				}
			}
		},
		failure: function () {
		}
	});
}


function createRoleCat(parent_id, node_name, node_type) {
	console.log("getting inside createRoleCat");
	var object = {};
	object.parent_id = parent_id;
	object.node_name = node_name;
	object.node_type = node_type;
	$.ajax({
		method: "POST",
		url: "../acc/create_role_n_category.php",
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
				if (data.ret_code == 0) {
					//$('#roll_tree').jstree(true).refresh();
				} else {

				}
			}
		},
		failure: function () {
		}
	});
}

function deleteRoleCategory(obj) {
	console.log("getting inside deleteRoleCategory");
	var delete_node_id = obj.node.id;
	console.log("the delete_node_id is:: " + delete_node_id);
	var object = {};
	object.node_id = delete_node_id;
	$.ajax({
		method: "POST",
		url: "../acc/delete_role_category.php",
		data: { k: JSON.stringify(object) },
		//dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			console.log("the ret code is:: " + data.ret_code);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");

				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					$('.role_tree_man').jstree(true).refresh();
				} else if (data.ret_code != 0) {
					$("#cant_del_role_cat_dialog").dialog('open');
					var roles = data.roles;
					console.log(roles);
					var role_ul = $("<ul/>");
					var ul_parent = $("#no_del_ul_cont");
					ul_parent.append(role_ul);

					$(roles).each(function (i, net) {
						console.log(net);
						var role_li = $("<li/>");
						role_li.html(net);
						role_ul.append(role_li);
					});
				}
			}
		},
		failure: function () {
		}
	});
}


function deleteRole(obj) {
	console.log("getting inside deleteRole");
	var delete_node_id = obj.node.id;
	console.log("the delete_node_id is:: " + delete_node_id);
	var object = {};
	object.node_id = delete_node_id;
	$.ajax({
		method: "POST",
		url: "../acc/delete_role.php",
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
				if (data.ret_code == 0) {
					$('.role_tree_man').jstree(true).refresh();
					console.log("the ret code is 0");
				} else {

				}
			}
		},
		failure: function () {
		}
	});
}


function decommission_role(node_id) {
	console.log("getting inside decommissionRole");
	var dcm_node_id = node_id;
	console.log("the dcm_node_id is:: " + dcm_node_id);
	var object = {};
	object.node_id = dcm_node_id;
	$.ajax({
		method: "POST",
		url: "decommission_role.php",
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
				if (data.ret_code == 0) {
					$('.role_tree_man').jstree(true).refresh();
					console.log("the ret code is 0");
				} else {

				}
			}
		},
		failure: function () {
		}
	});
}


function commission_role(node_id) {
	console.log("getting inside commission_role");
	var dcm_node_id = node_id;
	console.log("the dcm_node_id is:: " + dcm_node_id);
	var object = {};
	object.node_id = dcm_node_id;
	$.ajax({
		method: "POST",
		url: "commission_role.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("commission_role::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");

				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					$('.role_tree_man').jstree(true).refresh();
					console.log("the ret code is 0");
				} else {

				}
			}
		},
		failure: function () {
		}
	});
}


function set_target_role_sig_sess(target_sig, type) {
	console.log("getting inside set_target_role_sig_sess");
	var object = {};
	object.target_sig = target_sig;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "../acc/set_target_role_sig_sess.php",
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
				if (data.ret_code == 0) {
					if (type == "view") {
						window.location.href = '../acc/role_man_view_perm.php'; //go to role_man_view_perm
					} else {
						window.location.href = '../acc/role_man_upd_perm.php'; //go to role_man_upd_perm
					}

				} else {


				}
			}
		},
		failure: function () {
		}
	});
}

function cut_paste_node(moved_node_id, node_parent_id) {
	//alert("getting inside cut_paste_node");
	var object = {};
	object.moved_node_id = moved_node_id;
	object.node_parent_id = node_parent_id;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "../acc/cut_paste_node.php",
		data: { k: JSON.stringify(object) },
		//dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");

				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					$('.role_tree_man').jstree(true).refresh();
				} else {


				}
			}
		},
		failure: function () {
		}
	});
}


function copy_paste_node(moved_node_id, node_parent_id) {
	console.log("getting inside paste_node");
	var object = {};
	object.moved_node_id = moved_node_id;
	object.node_parent_id = node_parent_id;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "../acc/copy_paste_node.php",
		data: { k: JSON.stringify(object) },
		//dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");

				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					$('.role_tree_man').jstree(true).refresh();
				} else {


				}
			}
		},
		failure: function () {
		}
	});
}


function copy_paste_shortcut(moved_node_id, node_parent_id) {
	console.log("getting inside copy_paste_shortcut");
	var object = {};
	object.moved_node_id = moved_node_id;
	object.node_parent_id = node_parent_id;
	console.log(object);
	$.ajax({
		method: "POST",
		url: "../acc/copy_paste_shortcut.php",
		data: { k: JSON.stringify(object) },
		//dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");

				$("#session_expire_dialog").dialog('open');
				//	}
			} else {
				if (data.ret_code == 0) {
					$('.role_tree_man').jstree(true).refresh();
				} else {


				}
			}
		},
		failure: function () {
		}
	});
}


function update_role_n_category(node_id, updated_name, updated_description, type) {
	console.log("getting inside update_role_n_category");
	console.log(node_id);
	console.log(updated_name);
	console.log(updated_description);
	var object = {};
	object.node_name = updated_name;
	object.node_id = node_id;
	object.description = updated_description;
	object.type = type;
	$.ajax({
		method: "POST",
		url: "../acc/update_role_n_category.php",
		data: { k: JSON.stringify(object) },
		dataType: "json",
		success: function (data, textStatus, jQxhr) {
			console.log("add_new_topics::AJAX Return: ");
			console.log(data);
			var status = data.session_expire;
			if (data.ret_code == 4) {
				console.log("session expired key found");
				$("#session_expire_dialog").dialog('open');
			} else {
				if (data.ret_code == 0) {
					$('.role_tree_man').jstree(true).refresh();
					$("#upd_role_n_cat_dialog").dialog('close');
				} else {


				}
			}
		},
		failure: function () {
		}
	});
}

function update_role_n_category_dialog(obj) {
	var node_id = obj.id;
	var old_name = obj.text;
	console.log(obj.type);
	var type = obj.type;
	$("#upd_role_n_cat_dialog").data("node_id", node_id);
	$("#upd_role_n_cat_dialog").data("old_name", old_name);
	$("#upd_role_n_cat_dialog").data("type", type);
	$("#upd_role_n_cat_dialog").dialog('open');
}

function addCustomMenuItem(itm, key, obj) {
	itm[key] = obj;
}

function customMenu(node) {
	// The default set of all items
	target_node_id = node.id;
	isRoot = ($("#" + target_node_id).hasClass("topo_root"));
	console.log("customMenu");
	console.log(node);
	console.log(node.id);
	console.log(node.data);
	var nodeType = node.type;
	console.log(nodeType);
	console.log(node.reserved);
	console.log("The user ID is: " + node.original.user_id);
	var user_det_flag = node.original.ud;

	var cpy_lbl = $(".role_tree_man").data("cpy_lbl");
	var cut_lbl = $(".role_tree_man").data("cut_lbl");

	//console.log(lbl);
	var isRoleCat = false;

	if (node.type != null) {
		isRoleCat = (node.type == "rolecat");
	}

	if (node.type != null) {
		isRoleRes = (node.type == "role_res");
	}

	if (node.type != null) {
		isCatRes = (node.type == "cat_res");
	}

	if (node.type != null) {
		isRoleLink = (node.type == "role_link");
	}

	var items = {};

	if (nodeType == "default") {
		/*
		obj = 	{ // The "Create Multiple" menu item
				"label": "Create Role Category",
				
				"action": function (data) {
					
					var inst = $.jstree.reference(data.reference);
					obj = inst.get_node(data.reference);
					//console.log(obj.id);
					var node_id = obj.id;
					console.log(obj.id);
					inst.edit();
					inst.create_node(obj, {"type" : "rolecat"}, "last", function (new_node) {
						//inst.set_icon(new_node,"../ext-styles/themes/default/unit.ico?"+new Date().getTime());
						try {
							inst.edit(new_node);
						} catch (ex) {
							setTimeout(function () { inst.edit(new_node); },0);
						}
					});
					
					
				}
		};
		addCustomMenuItem(items, "createRoleCategory" , obj);
		*/
	} else {



		if (isRoleRes) {
			obj = { // The "View permission" menu item
				"label": "View Permission",
				"separator_before": true,
				"action": function (data) {
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					//inst.edit(obj);
					//console.log("clicked on view permission");
					console.log(obj.id);
					var target_sig = obj.id;
					var type = "view";
					set_target_role_sig_sess(target_sig, type);
				}
			};
			addCustomMenuItem(items, "viewPermission", obj);

		}



		if (isRoleLink) {
			obj = { // The "CUt Role" menu item
				"label": "Cut Role Shortcut",
				"action": function (data) {
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					obj.label = data.item.label;
					inst.cut(obj);

					//alert("cut cut");
				}
			};
			addCustomMenuItem(items, "cutRoleShortcut", obj);

			obj = { // The "Copy Shortcut" menu item
				"label": "Copy Role Shortcut",
				"action": function (data) {
					console.log(data);
					console.log(data.item.label);
					//console.log(data.item.label);
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					obj.label = data.item.label;
					console.log(obj);
					inst.copy(obj);
				}
			};
			addCustomMenuItem(items, "copyShortcutofShortcut", obj);

			obj = { // The "Delete Role Shortcut" menu item
				"label": "Delete Role Shortcut",
				"action": function (data) {
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					console.log(obj);
					if (inst.is_selected(obj)) {
						inst.delete_node(inst.get_selected());
					}
					else {
						inst.delete_node(obj);
					}
				}
			};
			addCustomMenuItem(items, "deleteRoleShortcut", obj);


		}

		if (isCatRes) {

			obj = { // The "Create Multiple" menu item
				"label": "Create Role Category",

				"action": function (data) {

					var inst = $.jstree.reference(data.reference);
					obj = inst.get_node(data.reference);
					//console.log(obj.id);
					var node_id = obj.id;
					console.log(obj.id);
					inst.edit();
					inst.create_node(obj, { "type": "rolecat" }, "last", function (new_node) {
						//inst.set_icon(new_node,"../ext-styles/themes/default/unit.ico?"+new Date().getTime());
						try {
							inst.edit(new_node);
						} catch (ex) {
							setTimeout(function () { inst.edit(new_node); }, 0);
						}
					});


				}
			};
			addCustomMenuItem(items, "createRoleCategory", obj);


			obj = { // The "Create Role" menu item
				"label": "Create Role",
				"action": function (data) {

					var inst = $.jstree.reference(data.reference);
					obj = inst.get_node(data.reference);
					//console.log(obj.id);
					var node_id = obj.id;
					console.log(obj.id);
					inst.edit();
					inst.create_node(obj, { "type": "role" }, "last", function (new_node) {
						//inst.set_icon(new_node,"../ext-styles/themes/default/unit.ico?"+new Date().getTime());
						try {
							inst.edit(new_node);
						} catch (ex) {
							setTimeout(function () { inst.edit(new_node); }, 0);
						}
					});


				}
			};
			addCustomMenuItem(items, "createRole", obj);

			obj = { // The "Paste Role" menu item
				"label": "Paste Role",
				"_disabled": function (data) {
					//return !$.jstree.reference(data.reference).can_paste();
					if (cpy_lbl == "Copy Role Shortcut" || cut_lbl == "Cut Role Shortcut") {
						return $.jstree.reference(data.reference).can_paste();
					} else {
						return !$.jstree.reference(data.reference).can_paste();
					}

				},
				"action": function (data) {
					console.log(data);
					console.log(data.item.label);
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					obj.label = data.item.label;
					var can_paste = inst.can_paste();
					console.log("can paste? " + can_paste);
					inst.paste(obj);
				},
			};

			addCustomMenuItem(items, "pasteRole", obj);



			obj = { // The "Paste Role Shortcut" menu item
				"label": "Paste Role Shortcut",
				"_disabled": function (data) {

					//console.log(data.item.label);
					//var label = data.item.label;
					//alert(label);
					if (cpy_lbl == "Copy Role" || cut_lbl == "Cut Role") {
						return $.jstree.reference(data.reference).can_paste();
					} else {
						return !$.jstree.reference(data.reference).can_paste();
					}
				},
				"action": function (data) {
					console.log(data);
					console.log(data.item.label);
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					obj.label = data.item.label;
					var can_paste = inst.can_paste();
					console.log("can paste? " + can_paste);
					inst.paste(obj);
				},
			};
			addCustomMenuItem(items, "pasteRoleShortcut", obj);
		}




		if (isRoleCat) {

			obj = { // The "Create Multiple" menu item
				"label": "Create Role Category",

				"action": function (data) {

					var inst = $.jstree.reference(data.reference);
					obj = inst.get_node(data.reference);
					//console.log(obj.id);
					var node_id = obj.id;
					console.log(obj.id);
					inst.edit();
					inst.create_node(obj, { "type": "rolecat" }, "last", function (new_node) {
						//inst.set_icon(new_node,"../ext-styles/themes/default/unit.ico?"+new Date().getTime());
						try {
							inst.edit(new_node);
						} catch (ex) {
							setTimeout(function () { inst.edit(new_node); }, 0);
						}
					});


				}
			};
			addCustomMenuItem(items, "createRoleCategory", obj);

			obj = { // The "Delete Role Category" menu item
				"label": "Delete Role Category",
				"separator_after": true,
				"action": function (data) {
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					console.log(obj);
					if (inst.is_selected(obj)) {
						inst.delete_node(inst.get_selected());
					}
					else {
						inst.delete_node(obj);
					}
				}
			};
			addCustomMenuItem(items, "deleteRoleCategory", obj);

			obj = { // The "Update Role" menu item
				"label": "Update Role Category",
				"action": function (data) {
					console.log("Update Category Clicked");
					console.log(data.reference);
					var inst = $.jstree.reference(data.reference);
					console.log(inst);
					inst.edit();
					//var obj = inst.get_node(data.reference);
					var node_obj = inst.get_node("PROP_ROOT");
					//if(inst.edit(node_obj)) {
					console.log(data);
					console.log(node_obj);
					console.log(inst.get_selected(true));
					//inst.rename_node(inst.get_selected(true), "My New Name");
					inst.rename_node(node_obj, "My New Name");
					//update_role_n_category_dialog(obj);

					//}

				}
			};
			addCustomMenuItem(items, "updateRoleCat", obj);


			obj = { // The "Create Role" menu item
				"label": "Create Role",
				"action": function (data) {

					var inst = $.jstree.reference(data.reference);
					obj = inst.get_node(data.reference);
					//console.log(obj.id);
					var node_id = obj.id;
					console.log(obj.id);
					inst.edit();
					inst.create_node(obj, { "type": "role" }, "last", function (new_node) {
						//inst.set_icon(new_node,"../ext-styles/themes/default/unit.ico?"+new Date().getTime());
						try {
							inst.edit(new_node);
						} catch (ex) {
							setTimeout(function () { inst.edit(new_node); }, 0);
						}
					});


				}
			};
			addCustomMenuItem(items, "createRole", obj);

			obj = { // The "Paste Role" menu item
				"label": "Paste Role",
				"_disabled": function (data) {
					//return !$.jstree.reference(data.reference).can_paste();
					if (cpy_lbl == "Copy Role Shortcut" || cut_lbl == "Cut Role Shortcut") {
						return $.jstree.reference(data.reference).can_paste();
					} else {
						return !$.jstree.reference(data.reference).can_paste();
					}

				},
				"action": function (data) {
					console.log(data);
					console.log(data.item.label);
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					obj.label = data.item.label;
					var can_paste = inst.can_paste();
					console.log("can paste? " + can_paste);
					inst.paste(obj);
				},
			};

			addCustomMenuItem(items, "pasteRole", obj);



			obj = { // The "Paste Role Shortcut" menu item
				"label": "Paste Role Shortcut",
				"_disabled": function (data) {

					if (cpy_lbl == "Copy Role" || cut_lbl == "Cut Role") {
						return $.jstree.reference(data.reference).can_paste();
					} else {
						return !$.jstree.reference(data.reference).can_paste();
					}
				},
				"action": function (data) {
					console.log(data);
					console.log(data.item.label);
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					obj.label = data.item.label;
					var can_paste = inst.can_paste();
					console.log("can paste? " + can_paste);
					inst.paste(obj);
				},
			};
			addCustomMenuItem(items, "pasteRoleShortcut", obj);
		}




		if (isRoleCat == false && isCatRes == false && isRoleRes == false && isRoleLink == false) {

			obj = { // The "Update Role" menu item
				"label": "Update Role",
				"action": function (data) {
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					//if(inst.edit(obj)) {
					console.log(data);
					console.log(obj);
					//inst.rename_node(inst.get_selected());
					update_role_n_category_dialog(obj);

					//}

				}
			};
			addCustomMenuItem(items, "updateRole", obj);

			obj = { // The "CUt Role" menu item
				"label": "Cut Role",
				"action": function (data) {
					console.log(data.item.label);
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					obj.label = data.item.label;
					inst.cut(obj);
					//alert("cut cut");
				}
			};
			addCustomMenuItem(items, "cutRole", obj);

			obj = { // The "Copy Role" menu item
				"label": "Copy Role",
				"action": function (data) {
					console.log(data);
					//console.log(data);
					console.log(data.item.label);
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					obj.label = data.item.label;
					inst.copy(obj);
					console.log(obj);
				}
			};
			addCustomMenuItem(items, "copyRole", obj);



			obj = { // The "Delete Role" menu item
				"label": "Delete Role",
				"action": function (data) {
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					console.log(obj);
					if (inst.is_selected(obj)) {
						//alert("deleted");
						inst.delete_node(inst.get_selected());

					}
					else {
						inst.delete_node(obj);
					}
				}
			};
			addCustomMenuItem(items, "deleteRole", obj);

			if (nodeType == "role_dcm") {
				obj = { // The "View permission" menu item
					"label": "Commission Role",
					"separator_after": true,
					"action": function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);

						console.log(obj.id);
						var target_sig = obj.id;
						var type = "view";
						console.log("commission commission");
						console.log(obj);
						commission_role(obj.id);
					}
				};
				addCustomMenuItem(items, "commissionRole", obj);
			} else {
				obj = { // The "View permission" menu item
					"label": "Decommission Role",
					"separator_after": true,
					"action": function (data) {
						var inst = $.jstree.reference(data.reference),
							obj = inst.get_node(data.reference);

						console.log(obj.id);
						var target_sig = obj.id;
						var type = "view";
						console.log("***********************");
						console.log(obj);
						decommission_role(obj.id);
					}
				};
				addCustomMenuItem(items, "decommissionRole", obj);

			}




			obj = { // The "Copy Role Shortcut" menu item
				"label": "Copy Role Shortcut",
				"action": function (data) {
					console.log(data);
					console.log(data.item.label);
					//console.log(data.item.label);
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					obj.label = data.item.label;
					console.log(obj);
					inst.copy(obj);

				}
			};
			addCustomMenuItem(items, "copyRoleShortcut", obj);




			obj = { // The "View permission" menu item
				"label": "View Permission",
				"separator_before": true,
				"action": function (data) {
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					//inst.edit(obj);
					//console.log("clicked on view permission");
					console.log(obj.id);
					var target_sig = obj.id;
					var type = "view";
					set_target_role_sig_sess(target_sig, type);
				}
			};
			addCustomMenuItem(items, "viewPermission", obj);

			obj = { // The "Update permission" menu item
				"label": "Update Permission",
				"action": function (data) {
					var inst = $.jstree.reference(data.reference),
						obj = inst.get_node(data.reference);
					//inst.edit(obj);
					console.log("clicked on update permission");
					var type = "update";
					var target_sig = obj.id;
					set_target_role_sig_sess(target_sig, type);
				}
			};
			addCustomMenuItem(items, "updatePermission", obj);
		}

	}

	return items;
}


function init_role_tree(node_to_be_loaded) {
	console.log("getting inside get_prop_name");
	console.log(node_to_be_loaded);
	var object = node_to_be_loaded;

	return $.ajax({
		method: "POST",
		url: "../acc/init_role_tree.php",
		//url: "get_prop_topo.php",
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
					role_tree = data.role;
					role_root = data.root;
					role_root.children.push(role_tree);
					//result = data.role;
					result = role_root;
					console.log("get_prop_topo::after AJAX::topo_tree:: result");
					console.log("********************acc_male_role Tree******************");
					console.log(result);
				} else {

				}
			}
		},
		failure: function () {
		}
	}).then(function () {
		console.log("inside AJAX Then");
		return $.Deferred(function (def) {
			console.log("inside AJAX deferred");
			console.log(def);
			def.resolveWith({}, [result]);
		});
	});


}


function role_tree_manip() {
	$('.role_tree_man').jstree({

		"deselect_all": true,
		'plugins': ["state", "contextmenu", "dnd", "types"],
		'contextmenu': { items: customMenu },
		'core': {
			/*
			'data' : [
				{
					'id' : 'PROP_ROOT',
					'text' : 'Please Select a Property',
					'children' : []
				}
			],
			*/

			'data': function (target_node_obj, callback) {
				//console.trace();
				console.log("JSTREE loading");
				console.log(target_node_obj);
				console.log("target_node_obj.data");
				console.log(target_node_obj.data);
				console.log(callback);
				var argobj = {};
				argobj.userid = target_node_obj.userid;
				init_role_tree(argobj).done(function (result) {
					console.log("JSTREE FEEDER: " + result);
					console.log(this);
					console.log(result);
					callback.call(this, result);
				});


			},

			'check_callback': true
		},
		"types": {

			"role": {
				"icon": "../ext-styles/themes/default/ricon24.png?" + new Date().getTime(),

			},
			"rolecat": {
				"icon": "../ext-styles/themes/default/cicon24.png?" + new Date().getTime(),

			},
			"role_dcm": {
				"icon": "../ext-styles/themes/default/ricon24.png?" + new Date().getTime(),
				"a_attr": { "style": "color:#787f94" },

			},
			"role_link": {
				"icon": "../ext-styles/themes/default/rlink24.png?" + new Date().getTime(),

			},
			"role_res": {
				"icon": "../ext-styles/themes/default/rlocked24.png?" + new Date().getTime(),
			},
			"cat_res": {
				"icon": "../ext-styles/themes/default/clocked24.png?" + new Date().getTime(),
			}
		}

	}).on('ready.jstree', function (node, parent, position) {
		console.log("jstree.ready event fired");
		var inst = $.jstree.reference(".role_tree_man");
		console.log(inst);
		obj = inst.get_node(".jstree-node");
		root_node = $(".jstree-node");
		console.log(root_node[0].id);
		$("#test_btn").data("node", root_node[0].id);
		console.log("JSTREE DEFAULTS");
		console.log($.jstree.defaults);
		console.log(obj);
		console.log(node);
		//console.log(test_node_obj);

	}).on('create_node.jstree', function (node, parent, position) {
		console.log("jstree.create_node event fired");
		console.log(parent);
		console.log(parent.node);
		var node_type = parent.node.type;
		console.log(node_type);
		var parent_id = parent.parent;

		console.log("the parent ID is::" + parent_id);
		//console.log(parent.node);
		var node_id = parent.node.id;
		var node_name = parent.node.text;
		console.log("the node ID is::" + node_id);
		console.log("the node name is::" + node_name);
		//prop_topo_create_node(parent_id, node_id, node_name);

		$(this).on('blur', function (e) {
			var node_name = parent.node.text;
			//alert(node_name);
			createRoleCat(parent_id, node_name, node_type);

		});
	});
	//});


	$('.role_tree_man').on('delete_node.jstree', function (e, obj) {

		alert("delete node");
		console.log("on delete");
		console.log(obj);
		console.log(obj.node.type);
		var type = obj.node.type;
		alert(type);
		//console.log(obj.children);
		//console.log("the new text is:::"+obj.node.text);
		//console.log("the new text id is:::"+obj.node.id);
		//delete_node(obj);
		children_arr = obj.node.children;
		console.log(children_arr);
		console.log(children_arr.length);
		/*
		if(typeof children_arr == "undefined" || children_arr == null || children_arr.length == null || children_arr.length == 0){
			console.log("Role Category has no children:: Hence can delete the role category");
			deleteRoleCategory(obj);
		}else{
			console.log("Role Category has children:: Hence can't delete the role category ");
			$("#cant_del_role_cat_dialog").dialog('open');
	
		}*/

		if (type == "role" || type == "role_link") {
			//alert("delete role");
			deleteRole(obj); //delete role function
		} else if (type == "rolecat") {
			deleteRoleCategory(obj); //delete role category function
		}

		//deleteRoleCategory(obj); //delete role category function
	});


	$('.role_tree_man').on('cut.jstree', function (e, data) {
		//alert("CUT....!!!");
		//console.log("moved");
		console.log(parent);
		//console.log(this);
		console.log(data);
		console.log(data.node);
		var node_arr = data.node[0];
		console.log(node_arr);
		console.log(node_arr.label);

		$(".role_tree_man").data("cut_lbl", node_arr.label);
	});

	$('.role_tree_man').on('copy.jstree', function (e, data) {
		//alert("COPY....!!!");
		//console.log("moved");
		//console.log(data);
		//console.log(e);
		console.log(parent);
		//console.log(this);
		console.log(data);
		console.log(data.node);
		var node_arr = data.node[0];
		console.log(node_arr);
		console.log(node_arr.label);

		$(".role_tree_man").data("cpy_lbl", node_arr.label);
		//console.log("the old parent is::"+ data.old_parent);
		//console.log("the new parent is::"+ data.parent);
		//move_node(data);
	});

	$('.role_tree_man').on('paste.jstree', function (e, data) {
		//alert("PASTE....!!!");

		console.log(data);
		console.log(e);

		console.log(data.node);
		var node_arr = data.node[0];
		console.log(node_arr);
		console.log(node_arr.label);

		var mode = data.mode;
		var moved_node_arr = data.node;
		var moved_node = moved_node_arr[0];
		var moved_node_id = moved_node.id;
		var node_parent_id = data.parent;

		var lbl = $(".role_tree_man").data("cpy_lbl");

		console.log(lbl); // label
		console.log(mode);
		console.log(moved_node_id);
		console.log(node_parent_id);


		if (mode == "move_node") {
			cut_paste_node(moved_node_id, node_parent_id);
		} else if (mode == "copy_node" && lbl == "Copy Role") {
			copy_paste_node(moved_node_id, node_parent_id);
		} else if (mode == "copy_node" && lbl == "Copy Role Shortcut") {
			//alert("here");
			copy_paste_shortcut(moved_node_id, node_parent_id);
		}
		/*
		else if(mode == "move_node" && lbl == "Copy Role Shortcut"){
			//alert("here");
			cut_paste_node(moved_node_id, node_parent_id);
		}*/

	});

	$('.role_tree_man').on('rename_node.jstree', function (e, data) {
		var node_name = data.node.text;
		var node_id = data.node.id;
		console.log(data);
		//console.log(node);
		console.log(node_id);

		$("#upd_role_n_cat_dialog").data("node_name", node_name);
		$("#upd_role_n_cat_dialog").data("node_id", node_id);
		//$("#upd_role_n_cat_dialog").dialog('open');
	});
}



$(document).ready(function () {

	$("#cant_del_role_cat_dialog").dialog({
		width: 700,
		height: 150,
		dialogClass: 'generic_dialog cant_del_role_cat_dialog_style',
		autoOpen: false,
		modal: true,
		close: function () {

		},

	});

	$("#upd_role_n_cat_dialog").dialog({
		width: 350,
		height: 250,
		dialogClass: 'generic_dialog cant_del_role_cat_dialog_style',
		autoOpen: false,
		modal: true,
		close: function () {

		},

	});
	role_tree_manip();
	//$(function () {

	console.log("---------------------------------------getting inside acc_man_role.js----------------------------------------------------------------------")

	//var test_node_obj={id:"kgsfkgsf"};



	$("#upd_role_n_cat_dialog").on("dialogopen", function (event, ui) {
		var old_name = $("#upd_role_n_cat_dialog").data("old_name");
		$("#name_textbox").val(old_name);
		$("#description_textbox").val("");
	});


	$("#upd_role_n_cat_dialog").on("dialogclose", function (event, ui) {
		$('.role_tree_man').jstree(true).refresh();
	});

	$("#cant_del_role_cat_dialog").on("dialogclose", function (event, ui) {
		$('.role_tree_man').jstree(true).refresh();
	});


	$("#update_cont").on('click', function (e) {
		var node_id = $("#upd_role_n_cat_dialog").data("node_id");
		var updated_name = $('#name_textbox').val();
		var updated_description = $('#description_textbox').val();
		var type = $("#upd_role_n_cat_dialog").data("type");
		update_role_n_category(node_id, updated_name, updated_description, type);
	});

	$("#test_btn").on('click', function (e) {

		console.log("Test Button Clicked");

		var root_node_id = $("#test_btn").data("node");
		console.log(root_node_id);

		var inst = $.jstree.reference("roll_tree");
		console.log(inst);
		var node_obj = inst.get_node(root_node_id);
		node_obj.data = { user_id: "some_user_id" };
		$('.role_tree_man').jstree(true).refresh_node(node_obj);

		//var myobj = {};
		//myobj.id=root_node_id;
		//myobj.data="something";
		//myobj.data.userid="test_user_id";
		//$('#roll_tree').jstree(true).refresh_node(myobj);


		/*
		var inst = $.jstree.reference("roll_tree");
		console.log (inst);
		var node_obj = inst.get_node("PROP_ROOT");
		console.log(node_obj);
		inst.rename_node(node_obj, "My New Name");
		*/



	});
	//$('#roll_tree').jstree(true).refresh();


});