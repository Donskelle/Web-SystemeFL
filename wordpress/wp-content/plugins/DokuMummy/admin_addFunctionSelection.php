<?php

/**
 * Calls the class on the post edit screen.
 */
function init_customField() {
    new customField();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'init_customField' );
    add_action( 'load-post-new.php', 'init_customField' );
}

/** 
 * The Class.
 */
class customField {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box( $post ) {
        add_meta_box('so_meta_box', 'DokuMummy', array( $this, 'render_meta_box_content' ), $post->post_type, 'normal' , 'high');
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {


		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) )
				return $post_id;
	
		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) )
				return $post_id;
		}

		if(isset($_POST["custom_element_grid_class"])) {
	         //UPDATE: 
	        $meta_element_class = $_POST['custom_element_grid_class'];
	        //END OF UPDATE

	        update_post_meta($post_id, 'custom_element_grid_class_meta_box', $meta_element_class);
	        //print_r($_POST);
	    }


	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {
        $meta_element_class = get_post_meta($post->ID, 'custom_element_grid_class_meta_box', true); //true ensures you get just one value instead of an array
	    ?>   
	    <label>Welche Funktion :  </label>

	    <select name="custom_element_grid_class" id="custom_element_grid_class">
	    	<option value="Keine" <?php selected( $meta_element_class, 'Keine' ); ?>>Keine</option>
	    	<option value="Login" <?php selected( $meta_element_class, 'Login' ); ?>>Login</option>
	    	<option value="Dokumente" <?php selected( $meta_element_class, 'Dokumente' ); ?>>Dokumente</option>
	    	<option value="Gruppen" <?php selected( $meta_element_class, 'Gruppen' ); ?>>Gruppen</option>
	    </select>

	    <?php
	}
}
?>