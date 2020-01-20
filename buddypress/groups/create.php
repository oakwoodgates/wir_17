<?php
/**
 * BuddyPress - Groups Create
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<div id="buddypress">

	<form method="post" id="wir-create-group-form" class="standard-form" enctype="multipart/form-data">

		<div id="template-notices" role="alert" aria-atomic="true">
			<?php
			/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
			do_action( 'template_notices' );

			if ( !empty($_POST['create'])){

				$s = $m = 0;

				$user_id = get_current_user_id();

				if ( $user_id && !empty($_POST['group-name']) )
					$m = 1;

				if ( $m ) {
					$name = $_POST['group-name'];

					$group_args = array(
						'group_id' => 0,
						'creator_id' => $user_id,
						'name' => $name
					);

					$id = groups_create_group( $group_args );

					if ( $id ) {
						do_action( 'groups_group_create_complete', $id );
						bp_core_redirect( bp_get_group_permalink( groups_get_group( $id ) ) . 'locations/create/' );
					} else {
						echo '<div id="message" class="error"><p>List not created</p></div>';
					}

				}
			}
			?>

			<div id="message" class="bp-template-notice updated">
			</div>

		</div>

		<div class="item-body" id="group-create-body">

			<h2 class="bp-screen-reader-text">List Details</h2>

			<div>
				<label for="group-name"><?php _e( 'List Name (required)', 'buddypress' ); ?></label>
				<input type="text" name="group-name" id="group-name" aria-required="true" value="<?php bp_new_group_name(); ?>" required />
			</div>

			<?php
			wp_nonce_field( 'groups_create_save_group-details' ); ?>


			<div class="submit" >
				<?php /* Finish Button */ ?>
				<input type="submit" name="create" value="Create List" id="wir-group-creation-finish" />
			</div>

		</div><!-- .item-body -->
	</form>
</div>
