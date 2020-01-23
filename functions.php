<?php


//Setup Default Groups Avatar
function my_default_get_group_avatar( $avatar ) {
	global $bp, $groups_template;

	if ( strpos( $avatar, 'group-avatars' ) ) {
		return $avatar;
	} else {
		$dir = get_stylesheet_directory_uri();
		$def = $dir . '/images/default-list-image.png';

		if ( $bp->current_action == "" )
			return '<img class="avatar" alt="' . esc_attr( $groups_template->group->name ) . '" src="'.$def.'" width="'.BP_AVATAR_THUMB_WIDTH.'" height="'.BP_AVATAR_THUMB_HEIGHT.'" />';
		else
			return '<img class="avatar" alt="' . esc_attr( $groups_template->group->name ) . '" src="'.$def.'" width="'.BP_AVATAR_FULL_WIDTH.'" height="'.BP_AVATAR_FULL_HEIGHT.'" />';
	}
}
add_filter( 'bp_get_group_avatar', 'my_default_get_group_avatar');


function roam_theme_scripts() {
	$dir = get_stylesheet_directory_uri();
	wp_enqueue_script( 'custom', $dir . '/js/custom.js', array( 'jquery' ) );
	wp_enqueue_style( 'main', $dir . '/css/main.css', array( 'twentythirteen-fonts', 'genericons', 'twentythirteen-style' ) );
}
add_action( 'wp_enqueue_scripts', 'roam_theme_scripts' );


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
    if ( isset( $_REQUEST ) ) {

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
