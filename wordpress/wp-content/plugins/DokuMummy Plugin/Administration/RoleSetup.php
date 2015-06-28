<?php
/**
 * Created by PhpStorm.
 * User: KRa
 * Date: 01.06.2015
 * Time: 17:11
 */


/**
 * Class RoleManager
 *
 * Die Rollen sind: dokuAdmin, dokuModerator und dokuUser
 *
 *
 */
class RoleSetup {



	/*
	 * Die Rechte der verschiedenen Nutzer.
	 *
	 * Alles was ein User kann, kann ein Mod, kann ein Admin
	 *
	 * */


	/**
	 * Die Fähigkeiten des normalen Benutzers.
	 *
	 * Ref: https://codex.wordpress.org/Roles_and_Capabilities
	 *
	 * @var array
	 */
	private $dokuUserCapabilites = array(
		'read' => true,//wp cap.
		'edit_pages' => true,
		'delete_pages' => true,
		'upload_files' => true,
		'publish_pages' => true,
		'publish_posts' => true
	);

	private $dokuModeratorCapabilites = array(
		'edit_others_pages' => true,
		'delete_others_pages' => true,
		'delete_published_pages' => true,
        'manage_user' => true
	);


	private $dokuAdminCapabilites = array(
		'doku_manage_user' => true,
		'manage_groups' => true
	);


	public function __construct(){

		//Zusammenführen der Rechte
		$this->dokuModeratorCapabilites = array_merge($this->dokuModeratorCapabilites, $this->dokuUserCapabilites);
		$this->dokuAdminCapabilites = array_merge($this->dokuAdminCapabilites, $this->dokuModeratorCapabilites);

		$this->register_custom_roles();
		$this->remove_wp_roles();
	}

	/**
	 * Registriert die Custom Roles für das DokuMummy Plugin
	 */
	private function register_custom_roles(){
		add_role('dokuUser', 'DokuUser', $this->dokuUserCapabilites);
		add_role('dokuModerator', 'DokuModerator', $this->dokuModeratorCapabilites);
		add_role('dokuAdmin', 'DokuAdmin', $this->dokuAdminCapabilites);
	}

	/**
	 * Löscht alle StandardWP-Rollen bis auf den Administrator.
	 */
	private function remove_wp_roles(){
		remove_role('subscriber');
		remove_role('editor');
		remove_role('author');
		remove_role('contributor');
	}

}