
/*
$( function() {
    $( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( "#tabs" ).on( "tabsactivate", function( event, ui ) {
    	my_tabsactivate_func( event, ui );
    } );
    $( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  } );
*/

  
$( function() {
  $( "#ud_tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
  $( "#ud_tabs" ).on( "tabsactivate", function( event, ui ) {
    my_tabsactivate_func( event, ui );
  } );
  $( "#ud_tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
} );


/*
function my_tabsactivate_func( event, ui ) {
	console.log("my_tabsactivate_func");
	console.log(ui.newTab.attr("id"));
	
	//$( "#tabs" ).tabs( "activate_tab", 1 );
	//tabs(_activate: function( index )
}

*/

