<?php
/**
 * Verwaltet Gruppen Zugriffe und Abfragen
 */
class Groups {

	private $dbTableGroup = "dokumummy_groups";
	private $dbTableUserInGroup = "dokumummy_users_in_groups";
	private $dbTableDocumentInGroup = "dokumummy_documents_in_groups";

	function __construct() {
		global $wpdb;
		
		$this->dbTableDocumentInGroup = $wpdb->prefix . $this->dbTableDocumentInGroup;
		$this->dbTableGroup = $wpdb->prefix . $this->dbTableGroup;
		$this->dbTableUserInGroup = $wpdb->prefix . $this->dbTableUserInGroup;
	}


	/**
	 * [selectGroup description]
	 * Dokument wird Gruppe zugewiesen
	 * @param  [int] $group_id [description]
	 * @param  [int] $doc_id   [description]
	 */
	public function selectGroup($group_id, $doc_id) {
		global $wpdb;
		$sql = $wpdb->delete(
			$this->dbTableDocumentInGroup, 
			array( 
				'document_id' => $doc_id
			)
		);
		if($group_id == "none")
			return; 
		$sql = $wpdb->insert(
			$this->dbTableDocumentInGroup, 
			array( 
				'group_id' => $group_id, 
				'document_id' => $doc_id
			)
		);
	}

	/**
	 * [getDocumentGroups description]
	 * Gibt ein Array aus Dokumenten der Gruppe zurück
	 * @param  [int] $doc_id [description]
	 * @return [array]         [description]
	 */
	public function getDocumentGroups($doc_id) {
		global $wpdb;
		$groups = array();
		$groups["groups"] = $this->getUserGroups(get_current_user_id());
		$groups["active"] = $wpdb->get_row( "SELECT * FROM  $this->dbTableDocumentInGroup WHERE document_id=$doc_id");
		return $groups;
	}


    /**
     * @return mixed Alle vorhandenen Gruppen.
     */
	public function getAllGroups() {
		global $wpdb;

	    $results = $wpdb->get_results( 'SELECT * FROM ' . $this->dbTableGroup);

	    return $results;
	}


	/**
	 * [getAuthGroups description]
	 * Gibt ein Array der Gruppen die der Nutzerrolle entsprechenden  zurück
	 * @return [array] [description]
	 */
	public function getAuthGroups() {
		$user = wp_get_current_user();

		if($user->roles[0] == "dokuAdmin" || $user->roles[0] == "administrator") {
			return $this->getAllGroups();
		}
		else {
			return $this->getUserGroups($user->ID);
		}
	}

	/**
	 * [getUserGroups description]
	 * Gibt ein Array der Gruppen zurück, in welcher der User sich befindet.
	 * @param  [type] $user_id [description]
	 * @return [type]          [description]
	 */
	public function getUserGroups($user_id) {
        global $wpdb;

        //Gruppen, denen der User angehört
        $groups = $wpdb->get_results("SELECT g.name, g.id, g.description FROM  $this->dbTableUserInGroup uig
                        INNER JOIN $this->dbTableGroup g on uig.group_id = g.id
                        WHERE uig.user_id=$user_id");
        return $groups;
   	}



    /**
     * Fügt einen Nutzer einer Gruppe hinzu.
     * @param $group_id
     * @param $user_id
     */
	public function addUser($group_id, $user_id) {
		global $wpdb;
		$sql = $wpdb->insert(
			$this->dbTableUserInGroup, 
			array( 
				'user_id' => $user_id, 
				'group_id' =>  $group_id
			)
		);
	}

    /**
     * Löscht einen Benutzer aus einer Gruppe.
     *
     * @param $group_id
     * @param $user_id
     */
	public function deleteUser($group_id, $user_id) {
		global $wpdb;
		$sql = $wpdb->delete(
			$this->dbTableUserInGroup, 
			array( 
				'user_id' => $user_id, 
				'group_id' =>  $group_id
			)
		);
	}

	public function getFields( array $meta_boxes ) {

	}

    /**
     * Testet, ob  ein Benutzer überhaupt in einer Gruppe ist.
     *
     * @param $id
     * @return mixed
     */
	public function getUserNotInGroup($id) {
		global $wpdb;

	    $table_useringroup = $this->dbTableUserInGroup;
	    $table_wpuser = $wpdb->prefix . "users";

	    $results = $wpdb->get_results("SELECT $table_wpuser.user_nicename, $table_wpuser.ID FROM wp_users
                                      LEFT OUTER JOIN $table_useringroup ON wp_users.ID = $table_useringroup.user_id
                                          AND $table_useringroup.group_id=$id
                                      WHERE $table_useringroup.group_id IS NULL ");

	    return( $results);
	}

    /**
     * Holt eine Gruppe von der Datenbank.
     * @param $id DB-Id der Gruppe
     * @return mixed Gruppeninformationen
     */
	public function getGroup($id) {
		global $wpdb;

	    $table_name = $this->dbTableGroup;

	    $results = $wpdb->get_row( "SELECT * FROM  $table_name WHERE id=$id");

	    return( $results);
	}

    /**
     * Gibt die Benutzer einer Gruppe zurück.
     * @param $id
     * @return mixed
     */
	public function getGroupAndUsers($id) {
		global $wpdb;
		$table_useringroup = $this->dbTableUserInGroup;
		$table_wpuser = $wpdb->prefix . "users";

		$group = $this->getGroup($id);
		$group->user = $wpdb->get_results("SELECT user_id, $table_wpuser.user_nicename FROM  $table_useringroup
                                            right outer join $table_wpuser on $table_useringroup.user_id=$table_wpuser.ID
                                            WHERE $table_useringroup.group_id=$id");
		return $group;
	}

    /**
     * Speichert/Erstellt eine Gruppe
     *
     * @param $name
     * @param $description
     * @param $user_id
     */
	public function saveGroup($name, $description, $user_id) {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$table_name = $this->dbTableGroup;

		$sql = $wpdb->insert(
			$table_name, 
			array( 
				'name' => $name, 
				'description' => $description
			)
		);

		// Person, welche die Gruppe erstellt hat, wird zur Gruppe hinzugefügt
		$table_connect = $this->dbTableUserInGroup;
		$sql = $wpdb->insert(
			$table_connect, 
			array( 
				'user_id' => $user_id, 
				'group_id' =>  $wpdb->insert_id 
			)
		);
	}

	/**
	 * [initDatabase description]
	 * Erstellt Datenbanken für die Gruppen
	 * Datenbanken:
	 * Prefix + dokumummy_groups
	 * Prefix + dokumummy_users_in_groups
	 */
	public function initDatabase()
	{
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


		/**
		 * Datenbank für Gruppen
		 */
		$tableGroups = $this->dbTableGroup;

	    $sql = "CREATE TABLE IF NOT EXISTS $tableGroups (
			id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			name varchar(255) DEFAULT NULL,
			description varchar(255) DEFAULT NULL,
			active int(1) DEFAULT 1,
			created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 			updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
 			PRIMARY KEY (id)
	    )";
		dbDelta( $sql );


	    /**
		 * Datenbank für die Verbindung von User zu Gruppen
		 */
		$tableUserInGroups = $this->dbTableUserInGroup ;
		$wps_usertable = $wpdb->prefix . "users";

	    $sql = "CREATE TABLE IF NOT EXISTS $tableUserInGroups (
			id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			user_id bigint(20) UNSIGNED NOT NULL,
			group_id int(11) UNSIGNED NOT NULL,
			created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
 			updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (id),
			FOREIGN KEY (user_id) references $wps_usertable(ID) on update cascade on delete cascade,
			FOREIGN KEY (group_id) references $tableGroups(id) on update cascade on delete cascade
	    )";
		dbDelta( $sql );
	}
}

?>