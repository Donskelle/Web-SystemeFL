<?php
/*
Plugin Name: Doku Mummy Plugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: DokuMummy
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/


require_once( 'Administration/RoleSetup.php' );

require_once( 'Models/Groups.php' );
require_once( 'Models/Documents.php' );
require_once('Sphinx/SphinxDocument.php');

require_once('GUI_Backend/Widgets/CustomField.php');

require_once('GUI_Frontend/FrontendController.php');
require_once('GUI_Frontend/Widgets/Menu_Widget.php');

require_once('GUI_Backend/Widgets/CustomWidget.php');
require_once('GUI_Backend/Widgets/Newsfeed_Widget.php');
require_once('GUI_Frontend/Widgets/AdminBarDeleteButton.php');
require_once('GUI_Frontend/Widgets/AdminBarLayoutSelection.php');
require_once('GUI_Frontend/Widgets/AdminBarGroupSelection.php');
require_once('GUI_Frontend/Widgets/AdminBarDownloadOption.php');


/*
 * register_activation_hook aktiviert sich, wenn das Plugin aktiviert wird.
 * */
register_activation_hook(__FILE__,  'dokumummy_activated');


/**
 * Diese Funktions erstellt Rollen und DB-Tables bei der Aktivierung des Plugins im WP-Backend.
 */
function dokumummy_activated() {
    /**
     * Rolen registrieren
     */
    new RoleSetup();

    /**
     * Datenbanken für Gruppen erstellen
     */
    $groups = new Groups();
    $groups->initDatabase();

    /**
     * Datenbanken für Dokumente erstellen
     */
    $documents = new Documents();
    $documents->initDatabase();
}

/**
 *  Wenn ein Benutzer nicht eingelogged ist, wird er auf die Loginpage redirected.
 *  Der Test auf is_user_logged_in ist wichtig, da sonst immer auf die Loginpage verviesen wird.
 */
add_action('template_redirect', 'login_redirect');
/**
 * Redirected den Besucher zur Login page, aber nur, wenn dieser nicht eingelogged ist.
 */
function login_redirect() {
	if ( ! is_user_logged_in() ) {
		auth_redirect(); //https://codex.wordpress.org/Function_Reference/auth_redirect
    }
}

/**
 * Wird beim Init des Plugins ausgeführt
 */
add_action('init', 'initPlugin');
/**
 * Wird beim Initieren des Plugins ausgeführt.
 */
function initPlugin() {
    controllerInit();
}


/**
 * Initaliesiert die Controller.
 */
function controllerInit(){
    if(is_admin()) {
        add_action('load-post.php', 'init_customfield');
        add_action('load-post-new.php', 'init_customfield');
    } else {
        frontendController();
    }
}


/**
 * Gibt die Rolle des Users als String zurück.
 * @param WP_USER $user
 * @return string
 */
function get_user_role($user){
    return $user->roles[0];
}


/**
 * Entferne alle unnötigen admin_bar Functionen.
 */
function remove_admin_bar_functionality(){
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('new-content');
    $wp_admin_bar->remove_node('edit');
    $wp_admin_bar->remove_node('search');
}
add_action( 'wp_before_admin_bar_render', 'remove_admin_bar_functionality' );



/**
 * Löscht alle default widgets.
 *
 * Quelle: http://www.paulund.co.uk/how-to-remove-default-wordpress-widgets
 */
function remove_default_widgets(){
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Links');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Search');
	unregister_widget('WP_Widget_Text');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Nav_Menu_Widget');
}
add_action('widgets_init', 'remove_default_widgets', 11);



/**
 * Entfernt activity und wordpress press Widget im Backend
 */
function remove_admin_widgets(){
    remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');//since 3.8
}

add_action('admin_init', 'remove_admin_widgets');


//echo plugins_url("/GUI_Frontend/JS/AceEditor/src-min/ace.js", __FILE__);
add_action('wp_enqueue_scripts', 'addScripts'); //wp_enque_script hooked nur im front-end.
/**
 * Fügt Javascript für das Frontend hinzu.
 */
function addScripts(){
    wp_enqueue_script('dm_script.js', plugins_url("GUI_Frontend/Views/js/dm_script.js",  __FILE__), array(), '1.0.0', true );
}

add_action('admin_enqueue_scripts', 'addAdminScripts');
/**
 * Fügt Javscript für das Backend hinzu.
 */
function addAdminScripts(){
    wp_enqueue_script('socket.io.js', plugins_url("GUI_Backend/Widgets/Scripts/socket.io.js",  __FILE__));
}

//Initialisiert das Newsfeed_Widget für das Backend.
new Newsfeed_Widget();



