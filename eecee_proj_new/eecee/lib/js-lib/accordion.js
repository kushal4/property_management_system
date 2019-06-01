//https://stackoverflow.com/questions/13711127/how-to-extend-an-existing-jquery-ui-widget

$.widget( "ui.accordion", $.ui.accordion, {
  //options: {
  //    delay: 500,
  //    prefix: ""
  //},

  open_tab: function( index ) {
      this._activate(index);
  },
});


$( function() {
  $( "#global_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
} );

$( function() {
  $( "#network_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
} );

$( function() {
  $( "#instance_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
} );

$( function() {
  $( "#pack_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
} );

$( function() {
  $( "#cre_pol_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
} );

$( function() {
  $( "#netscp_net_inst_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
} );


$( function() {
  $( "#owner_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
} );


$( function() {
  $( "#tenant_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
} );


$( function() {
  $( "#select_role_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
});

$( function() {
  $( "#assign_role_accordion" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
});

$( function() {
  //console.log("accordion initialization");
  $( ".attrib_accordion_style" ).accordion({
    collapsible: true,
    active: false,
    heightStyle:"content",
  });
});

  /*
  $( ".accordion" ).accordion("option", { 
    collapsible: true,
    active: false
});
*/
/*
jQuery(document).ready(function() {
  jQuery( "#global_accordion" ).accordion({
    collapsible: true,
    active: false,
  });
});
*/
