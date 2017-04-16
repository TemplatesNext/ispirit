jQuery(document).ready(function($){
	
	"use strict";	
	
	jQuery( '.woo-border-box' ).each(function() {
		if ( jQuery( this ).children( 'a:first-child' ).children('img').length > 1 )
		{
			jQuery( this ).addClass( 'nx-flipit' );
		}		
		
	});
	
});