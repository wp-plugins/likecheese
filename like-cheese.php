<?php
/*Plugin Name: Like Cheese Plugin 
URI: http://www.orcawebperformance.com/like-cheese-a-wordpress-plugin/
Description: Like Cheese lets your site visitors 'like' your site images.  Click the hearts to like an image! 
Hover over the number of likes to view who liked them.
Demo http://peaceoftheocean.com/shesellsseashells

Version: 1.0 
Author: Elizabeth Shilling
License: GPL2
*/
/*  Copyright 2015  Elizabeth Shilling - Orca Web Performance  
(email : eshilling@orcawebperformance.com)
This program is free software; you can redistribute it and/or modifyit under the terms of the GNU General Public License, 
version 2, aspublished by the Free Software Foundation.This program is distributed in the hope that it will be useful,but 
WITHOUT ANY WARRANTY; without even the implied warranty ofMERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
See theGNU General Public License for more details.
You should have received a copy of the GNU General Public Licensealong with this program; if not, 
write to the Free SoftwareFoundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


add_action( 'template_redirect', 'likecheese_add_js_likes' );
function likecheese_add_js_likes() {
    //ref http://www.sitepoint.com/adding-ajax-to-your-wordpress-plugin/
    
 
        // Get the Path to this plugin's folder
        $path = plugin_dir_url( __FILE__ );
 
        // Enqueue our script
		// Enqueue our jQuery. You never know if an install is loading it!
		wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'likecheese_ajax_like_image', 
                            $path. 'js/ajax_like_image.js',
                            array( 'jquery' ),
                            '1.0.0', true );
		  
        // Get the protocol of the current page
        $protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
 
        // Set the ajaxurl Parameter which will be output right before
        // our ajax-like-image.js file so we can use ajaxurl
        $params = array(
            // Get the url to the admin-ajax.php file using admin_url()
            'ajaxurl' => admin_url( 'admin-ajax.php', $protocol ),
        );
        // Print the script to our page
        wp_localize_script( 'likecheese_ajax_like_image', 'likecheese_params', $params );
 
}

	
											//data for the image likes and counts
											//second try
											function ecs_owp_content($content) { 
													
													//$pos = strpos($newstring, 'a', 1);  skip first a
												$postimagecount = substr_count( $content, 'wp-image-' );
												if ($postimagecount == 0) return $content; //no images in post
												
														else
												{//find img 
													while($postimagecount != 0)
														{
															$checkstring =strstr( $content, 'img' );  //remaining string inc img
															$imgpos = strpos( $content, 'img' );  //where i is at
															$firstcontent = substr( $content, 0, ($imgpos)); //beg to img(wo img or)
															$finalstring .= $firstcontent;
															//get info
															//get image id
													$pos3 = strpos($content, 'wp-image-');
													//string substr ( string $string , int $start [, int $length ] )
													$contentedit2 = substr($content, $pos3);  //wp-image...
													$pos4 = strpos($contentedit2, '"');
													$imagenumlen = $pos4 - 9;
													$imagechunk = 10 + $imagenumlen;
													$imageinfopart = substr($contentedit2, 0, $imagechunk);
													$imagenumlen1 = $imagenumlen + 1; 
													//imgnumlennlaspiece
													$imageid = (int) substr($imageinfopart, -$imagenumlen1, $imagenumlen);
													
													//get likers and count
													$key_1_value = get_post_meta( $imageid, _like_count, true );
													$key_2_value = get_post_meta( $imageid, _count, true );
													
														$arruserslike = explode(",", $key_1_value);
													
													$totallikecount = count($arruserslike);
													$userid = get_current_user_id();
													$like_user_info = get_userdata( $userid );
													$likecheeseusername = $like_user_info->display_name;
													
													//on image <3 load: grab default image <3, unless the user liked image then grab filled image <3 
													$likect = substr_count( $key_1_value, $likecheeseusername );
													if($likect != 0){$imageheart = plugins_url( 'images/filledc3.jpg' , dirname(_FILE_) );}
													else{$imageheart = plugins_url( 'images/defaultc3.jpg' , dirname(_FILE_) );}
													
													//Create site_url capture
													//$sitepath = site_url();
													$sitepath = wp_login_url();
													// Create a nonce for this action
                                                    $nonce = wp_create_nonce( 'likecheese_ali-like-' . $imageid );
													$class = 'likecheese_ali_like';  //looked for by onclick
													$str1 = '</br><span title="'; 
													$strspanclass = '" class="';
													$str2= '">';  
													$str3= '</span>  <img src="'; 
													$strid= '" onclick="swaparrows(this)" id="';
													$str4= '" data-id="';
													$str5= '" data-likecheeseusername="';   
													$str6= '" data-nonce="';
													$strsitepath= '" data-sitepath="';
													$str7= '" class="';
													$str8= '"><br />'; 
													//*****************
																//reset content to remaining string
																$content = $checkstring;
																//find end of image, the next </a>
																$checkstring =strstr( $content, '</a>' );  //remaining string inc </a>
																$endimgpos = strpos( $content, '</a>' );  //where < is at
																$firstcontent = substr( $content, 0, ($endimgpos+4)); //beg to </a>(w</a>
																 $finalstring .= ($firstcontent.$str1.$key_1_value.$strspanclass.$imageid.$str2.$key_2_value.$str3.$imageheart.$strid.$imageid.$str4.$imageid.$str5.$likecheeseusername.$str6.$nonce.$strsitepath.$sitepath.$str7.$class.$str8);
																$content = $checkstring;
																$postimagecount = substr_count( $content, 'wp-image-' );
														}
												}
												return $finalstring.$checkstring;
 
											}


													add_filter( 'the_content', 'ecs_owp_content' );
													/** * Adding a "Copyright" field to the media uploader $form_fields array 
													* 
													* ref http://bavotasan.com/2012/add-a-copyright-field-to-the-media-uploader-in-wordpress/ 
													* @param array $form_fields * @param object $post 
													* 
													* @return array 
													*/
													function likecheese_add_copyright_field_to_media_uploader($form_fields, $post){	
													if((get_post_meta($post->ID, '_copyright', true)) == "") $author_value = 'Steve';      
													else $author_value = get_post_meta( $post->ID, '_copyright', true );	
													$form_fields['copyright_field'] = array(		'label' => __('Copyright'),		'value' => $author_value,		'helps' => 'Set a copyright credit for the attachment'	);	
													return $form_fields;
													}
													add_filter('attachment_fields_to_edit', 'likecheese_add_copyright_field_to_media_uploader', null, 2);
													/** * Save our new "Copyright" field * * @param object $post * @param object $attachment * * @return array */
													function likecheese_add_copyright_field_to_media_uploader_save($post, $attachment) {	
													if(!empty($attachment['copyright_field'])) update_post_meta($post['ID'], '_copyright', sanitize_text_field($attachment['copyright_field']));	
													else delete_post_meta($post['ID'], '_copyright');	
													return $post;
													} 
													add_filter( 'attachment_fields_to_save', 'likecheese_add_copyright_field_to_media_uploader_save', null, 2);
													/** * Display our new "Copyright" field * * @param int $attachment_id * * @return array */
													function likecheese_get_featured_image_copyright($attachment_id = null){	
													$attachment_id = (empty( $attachment_id)) ? get_post_thumbnail_id() : (int)$attachment_id;	
													if($attachment_id) return get_post_meta($attachment_id, '_copyright', true);
													}
													/** * Adding a "like count" field to the media uploader $form_fields array * @param array $form_fields * @param object $post * * @return array */
													function likecheese_add_like_count_field_to_media_uploader($form_fields, $post){	
													$form_fields['like_count_field'] = array(		'label' => __('Liked by'),		'value' => get_post_meta( $post->ID, '_like_count', true ),		'helps' => 'Set a field to add display_names for users who like the image attachment'	);	
													return $form_fields;
													}
													add_filter('attachment_fields_to_edit', 'likecheese_add_like_count_field_to_media_uploader', null, 2);
													/** * Save our new "like count" field * * @param object $post * @param object $attachment * * @return array */
													function likecheese_add_like_count_field_to_media_uploader_save($post, $attachment){	
													if(!empty($attachment['like_count_field'])) update_post_meta($post['ID'], '_like_count', sanitize_text_field($attachment['like_count_field']));	
													else delete_post_meta($post['ID'], '_like_count');	
													return $post;
													}
													add_filter( 'attachment_fields_to_save', 'likecheese_add_like_count_field_to_media_uploader_save', null, 2);
													/** * Display our new "like count" field * * @param int $attachment_id * * @return array */
													function likecheese_get_featured_image_like_count($attachment_id = null){	
													$attachment_id = (empty($attachment_id)) ? get_post_thumbnail_id() : (int)$attachment_id;	
													if($attachment_id) return get_post_meta($attachment_id, '_like_count', true);
													}
													/** * Adding a "count" field to the media uploader $form_fields array * @param array $form_fields * @param object $post * * @return array */
													function likecheese_add_count_field_to_media_uploader($form_fields, $post){
													if((get_post_meta($post->ID, '_count', true )) == "") $count_value = '0';      
													else $count_value = get_post_meta( $post->ID, '_count', true );	
													$form_fields['count_field'] = array(		'label' => __('Number of Likes'),		'value' => $count_value,		'helps' => 'view the number of users who liked the image attachment'	);	
													return $form_fields;
													}
													add_filter('attachment_fields_to_edit', 'likecheese_add_count_field_to_media_uploader', null, 2);
													/** * Save our new "count" field * * @param object $post * @param object $attachment * * @return array */
													function likecheese_add_count_field_to_media_uploader_save($post, $attachment) {	
													if(!empty( $attachment['count_field'])) update_post_meta($post['ID'], '_count', sanitize_text_field($attachment['count_field']));	
													else delete_post_meta($post['ID'], '_count');	
													return $post;
													}
													add_filter('attachment_fields_to_save', 'likecheese_add_count_field_to_media_uploader_save', null, 2);
													/** * Display our new "count" field * * @param int $attachment_id * * @return array */
													function likecheese_get_featured_image_count($attachment_id = null){	
													$attachment_id = (empty($attachment_id)) ? get_post_thumbnail_id() : (int)$attachment_id;	
													if($attachment_id) return get_post_meta($attachment_id, '_count', true);
													}
													/** * Adding a "icon" field to the media uploader $form_fields array  * @param array $form_fields * @param object $post * * @return array */
													function likecheese_add_icon_field_to_media_uploader($form_fields, $post){	
													if((get_post_meta( $post->ID, '_icon', true )) == "") $image_value = 'default';      
													else $image_value = get_post_meta($post->ID, '_icon', true);		
													$form_fields['icon_field'] = array(		'label' => __('icon'),		'value' => $image_value,		'helps' => 'provides image of the heart for the image attachment'	);	
													return $form_fields;
													}
													add_filter('attachment_fields_to_edit', 'likecheese_add_icon_field_to_media_uploader', null, 2);
													/** * Save our new "icon" field * * @param object $post * @param object $attachment * * @return array */
													function likecheese_add_icon_field_to_media_uploader_save($post, $attachment){	
													if(!empty($attachment['icon_field'])) update_post_meta($post['ID'], '_icon', sanitize_text_field($attachment['icon_field']));	
													else delete_post_meta( $post['ID'], '_icon' );	
													return $post;
													}
													add_filter('attachment_fields_to_save', 'likecheese_add_icon_field_to_media_uploader_save', null, 2);
													/** * Display our new "icon" field * * @param int $attachment_id * * @return array */
													function likecheese_get_featured_image_icon($attachment_id = null){	
													$attachment_id = (empty($attachment_id)) ? get_post_thumbnail_id() : (int)$attachment_id;	
													if($attachment_id) return get_post_meta($attachment_id, '_icon', true);
													}


// Ajax Handler
add_action( 'wp_ajax_likecheese_ali_ajax_like_image', 'likecheese_ali_ajax_like_image' );
function likecheese_ali_ajax_like_image() {
    // Get the Image ID from the URL
    $imageid = sanitize_text_field($_REQUEST['imageid']);
	$likecheeseusername = sanitize_text_field($_REQUEST['likecheeseusername']);
	$imageheart = sanitize_text_field($_REQUEST['imageheart']);
	$nonce = sanitize_text_field($_REQUEST['nonce']);
    //get likers and count
$key_1_value = get_post_meta( $imageid, _like_count, true );
$key_2_value = get_post_meta( $imageid, _count, true );

$numlikes = (int) $key_2_value;
if ($numlikes == 0 || $numlikes == null || $numlikes == undefined) {
       $struserslike = $likecheeseusername;
	   $strlikes = '1';
	   $imageheart = plugins_url( 'images/filledc3.jpg' , dirname(_FILE_) );
	   $heart = 'filled';
	   }
	   else {
	   $arruserslike = explode(",", $key_1_value);
       $totallikecount = count($arruserslike);
	   $likect = substr_count( $key_1_value, $likecheeseusername );	
			if($likect != 0)
			{
			//unlike
					$middle = ', ' . $likecheeseusername . ', ';   //middle  , jane howl,  like jane
					$notend = $likecheeseusername . ', ';   //ben smith, jim helm   like ben  is least defined
					$atend = ', ' . $likecheeseusername;   //ben smith, jim helm   like jim
					$namect = substr_count( $key_1_value, $notend );
					$namemidct = substr_count( $key_1_value, $middle );
			//if username the solo like
					//if($key_1_value == $likecheeseusername) $struserslike = NULL;
					if((substr_count($key_1_value, ',')) == 0){ $struserslike = NULL;}
					//else if username is last
					 elseif($namect == 0) $struserslike = str_replace( $atend, "", $key_1_value );
					 //substr_count( string $haystack, string $needle [, int $offset = 0 [, int $length ]] );
				     //elseif username is in middle
					 elseif($namemidct != 0) $struserslike = str_replace( $notend, "", $key_1_value );
					else //user is first with others
					$struserslike = str_replace($notend, "", $key_1_value);
				
			//update count
			$beach = --$numlikes;
			$strlikes = (string)$beach;
			$imageheart = plugins_url( 'images/defaultc3.jpg' , dirname(_FILE_) );
			$heart = 'default';
			}
			else
			{
			//like
			$struserslike = $key_1_value . ", " . $likecheeseusername;
		    $beach = ++$numlikes;
		    $strlikes = (string)$beach;
			$imageheart = plugins_url( 'images/filledc3.jpg' , dirname(_FILE_) );
			$heart = 'filled';
			}
	   }
        update_post_meta( $imageid, _like_count, $struserslike );
	        update_post_meta( $imageid, _count, $strlikes );
			update_post_meta( $imageid, _icon, $heart );
    // Instantiate WP_Ajax_Response
    $response = new WP_Ajax_Response;
 
    // Proceed, again we are checking for permissions
    if( 
        // Verify Nonces
        wp_verify_nonce( $nonce, 'likecheese_ali-like-' . $imageid ) 
      ){
        // Build the response if successful
        $response->add( array(
            'data'  => 'success',
            'supplemental' => array(
                'imageid' => $imageid,
				'likecheeseusername' => $likecheeseusername,
                'imageheart' => $imageheart,
				'nonce' => $nonce,
				   'key_1_value' => $struserslike,
                'key_2_value' => $strlikes
            ),
        ) );
    } else {
        // Build the response if an error occurred
        $response->add( array(
            'data'  => 'error',
            'supplemental' => array(
                'imageid' => $imageid,
                'message' => 'Error liking/unliking this image ('. $imageid .')',
            ),
        ) );
    }
    // Whatever the outcome, send the Response back
    $response->send();
 
    // Always exit when doing Ajax
    exit();
	}