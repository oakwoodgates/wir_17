<?php
//Setup Default Groups Avatar
function my_default_get_group_avatar($avatar) {
global $bp, $groups_template;

if( strpos($avatar,'group-avatars') ) {
return $avatar;
}
else {
$custom_avatar = 'https://app.wheninroamtravelapp.com/wp-content/uploads/2017/06/Apple1024-1.png';

if($bp->current_action == "")
return '<img class="avatar" alt="' . esc_attr( $groups_template->group->name ) . '" src="'.$custom_avatar.'" width="'.BP_AVATAR_THUMB_WIDTH.'" height="'.BP_AVATAR_THUMB_HEIGHT.'" />';
else
return '<img class="avatar" alt="' . esc_attr( $groups_template->group->name ) . '" src="'.$custom_avatar.'" width="'.BP_AVATAR_FULL_WIDTH.'" height="'.BP_AVATAR_FULL_HEIGHT.'" />';
}
}
add_filter( 'bp_get_group_avatar', 'my_default_get_group_avatar');

function replace_class( $items, $menu, $args ) {
    // Iterate over the items to search and destroy
	//WHEN-14
	if ( is_user_logged_in() )
    {
		foreach ( $items as $key => $item ) {
			try {
				if ( $item->title == "Logout" )
				{
					$item->url = "https://app.wheninroamtravelapp.com/logout/?_wpnonce=" . wp_create_nonce( 'log-out' ) ;
				}
			} catch (Exception $err) {
            }
			if ( $item->object_id == 712 )
			{
				unset( $items[$key] );
			}
		}
	}
    return $items;
}

add_filter( 'wp_get_nav_menu_items', 'replace_class', null, 3 );

//add_action('wp_head', 'show_template');
function show_template() {
	global $template; print_r($template);
}


function ch_scripts() {

  wp_localize_script( "add_bp_group", 'bp_group_add',
        array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' )
        )
  );


   wp_enqueue_script( "chscript", "/wp-content/themes/wir_17/js/script.js", array( 'jquery' ));

	 $dir = get_stylesheet_directory_uri();
	 wp_enqueue_style('main', $dir . '/css/main.css' );
//	 wp_enqueue_style('x', $d . '/css/buddypress.css' );
//	 wp_enqueue_style('y', $d . '/css/twentythirteen.css' );
}




add_action( 'wp_enqueue_scripts', 'ch_scripts' );


function your_enqueue_scripts_function() {
    //add this below what you currently have in your enqueue scripts function.

}





function new_list_process() {
    // do whatever you need in order to process the form.
}
add_action("wp_ajax_new_list_form", "new_list_process");

//use this version for if you want the callback to work for users who are not logged in
add_action("wp_ajax_nopriv_new_list_form", "new_list_process");





function new_list() {


	die();
}


// Setup Ajax action hook
add_action( 'wp_ajax_new_list', 'new_list' );



function example_ajax_request() {

    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {

        $fruit = $_REQUEST['fruit'];

        $listname = $_REQUEST['listname'];
	    $echo = array();
	    $userid = get_current_user_id();

	    $defaults = array(
			'group_id'     => 0,
			'creator_id'   => $userid,
			'name'         => $listname,
			'description'  => '',
			'slug'         => '',
			'status'       => 'public',
			'enable_forum' => 0,
			'date_created' => bp_core_current_time()
		);
		$newgroupid = groups_create_group($defaults);

		echo $newgroupid;


    }

    // Always die in functions echoing ajax content
   die();
}

add_action( 'wp_ajax_example_ajax_request', 'example_ajax_request' );
