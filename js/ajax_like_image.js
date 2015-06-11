jQuery( document ).ready( function() {


    jQuery( '.likecheese_ali_like' ).click( function( e ) {
        var link = this;
        var imageid   = jQuery( link ).attr( 'data-id' );
        var nonce = jQuery( link ).attr( 'data-nonce' );
		var likecheeseusername = jQuery( link ).attr( 'data-likecheeseusername' );
		var imageheart = jQuery( link ).attr( 'src' );
		var site_url = jQuery( link ).attr( 'data-sitepath' );
           
        // This is what we are sending the server
        var data = {
            action: 'likecheese_ali_ajax_like_image',
            imageid: imageid,
            nonce: nonce,
			likecheeseusername: likecheeseusername,
			imageheart: imageheart
        }
       
        // To provide the user some immediate feedback
       
		if (likecheeseusername == null || likecheeseusername == "" || likecheeseusername == undefined){		
													alert("Please login")
														
					window.location = site_url + '?action=register' ;}
    

													
                                                          
        // Post to the server
        jQuery.post( likecheese_params.ajaxurl, data, function( data ) {
            // Parse the XML response with jQuery
            // Get the Status
            var status = jQuery( data ).find( 'response_data' ).text();
            // Get the Message
            var message = jQuery( data ).find( 'supplemental message' ).text();
			 // Get the key_1_value
            var key_1_value = jQuery( data ).find( 'supplemental key_1_value' ).text();
			// Get the key_2_value
            var key_2_value = jQuery( data ).find( 'supplemental key_2_value' ).text();
			// Get the imageheart
            var imageheart = jQuery( data ).find( 'supplemental imageheart' ).text();
			// Get the imageid
            var imageid = jQuery( data ).find( 'supplemental imageid' ).text();
			
            // If we are successful, add the success message and remove the link
            if( status == 'success' ) {
			     jQuery( link ).before(' currently: ');
				 jQuery( link ).after( '<span title="' + key_1_value + '">' + key_2_value + '</span>  <img src="' + imageheart + '" data-id="' + imageid + '" data-likecheeseusername="' + likecheeseusername + '" data-nonce="' + nonce + '" class="likecheese_ali_like"><br /></a>');
				 jQuery( link ).remove();
            } else {
                // An error occurred, alert an error message
                alert( message );
            }
        });
        // Prevent the default behavior for the link
        e.preventDefault();
    });
});