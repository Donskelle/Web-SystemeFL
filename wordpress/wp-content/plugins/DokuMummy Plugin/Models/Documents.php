<?php
/**
 * Verwaltet Dokumenten Zugriffe und Abfragen
 */
class Documents {

    /**
     * @var string
     */
    private $dbTableNameDocuments = "dokumummy_documents";
    /**
     * @var string
     */
    private $dbTableNameDocumentInGroup = "dokumummy_documents_in_groups";

    private $dbTableNameUpdateFeed = "dokumummy_update_feed";



    public function __construct(){
        global $wpdb;

        $this->dbTableNameDocuments = $wpdb->prefix . $this->dbTableNameDocuments;
        $this->dbTableNameDocumentInGroup = $wpdb->prefix . $this->dbTableNameDocumentInGroup;
        $this->dbTableNameUpdateFeed = $wpdb->prefix . $this->dbTableNameUpdateFeed;
    }


    /**
     * [getAllDocuments description]
     * Gibt ein Array aus Objekten aller Dokumente zurück
     * @return [array] [description]
     */
    public function getAllDocuments(){
        global $wpdb;

        $documents = $wpdb->get_results("SELECT * FROM  $this->dbTableNameDocuments");
        return $documents;

    }

    /**
     * [getDocument description]
     * Liest das angebene Dokument
     * @param  [type] $id [description]
     * @return [object]     [description]
     * Gibt ein Object aus der Datenbnak zurücl
     */
    public function getDocument($id) {
        global $wpdb;

        $document = $wpdb->get_row("SELECT * FROM $this->dbTableNameDocuments WHERE id=$id");
        return $document;
    }

    /**
     * [getDocumentsCreatedByUser description]
     * Gibt Dokumente vom aktiven User zurück
     * @param  [int] $user_id [description]
     * @return [array]          [description]
     * Gibt ein Array aus Dokumenten zurück
     */
    public function getDocumentsCreatedByUser($user_id){
        global $wpdb;

        $documents = $wpdb->get_results("SELECT * FROM $this->dbTableNameDocuments WHERE user_id=$user_id");
        return $documents;
    }


    /**
     * [getDocumentsInGroup description]
     * Gibt ein Array aus Dokumenten der Gruppe zurück
     * @param  [int] $groupId [description]
     * @return [type]          [description]
     */
    public function getDocumentsInGroup($groupId){
        global $wpdb;
        $documents = $wpdb->get_results("SELECT * FROM $this->dbTableNameDocumentInGroup dig
                                  INNER JOIN $this->dbTableNameDocuments d on dig.document_id = d.id
                                  WHERE dig.group_id = $groupId");

        return $documents;
    }

    /**
 * [removeDocumentFromGroup description]
     * Dokument wird von Gruppe entfernt
     * @param  [int] $doc_id   [description]
     * @param  [int] $group_id [description]
     */
    public function removeDocumentFromGroup($doc_id, $group_id){
        global $wpdb;
        $wpdb->delete($this->dbTableNameDocumentInGroup, array(
            'document_id' => $doc_id,
            'group_id' => $group_id
        ));
    }


    public function deleteAbschnitt($doc_id, $abschnitt_id) {
        $sphinx = new SphinxDocument("", "", $doc_id);
        $sphinx->removeAbschnitt($abschnitt_id);

        $userName = $this->getUserName();
        $this->updateNewsFeed("Ein Abschnitt wurde von $userName entfernt.");
    }


    /**
     * [getAbschnitte description]
     * Holt alle Abschnitte des angebenen Dokuments
     * @param  [int] $docId [description]
     * @return [array]        [description]
     * Array aller Abschnitte
     */
    public function getAbschnitte($docId) {
        $sphinx = new SphinxDocument("", "", $docId);

        
        $abschnitte = $sphinx->getAbschnitte();
        $url = site_url();

        for ($i=0; $i < count($abschnitte); $i++) {
          $abschnitte[$i]["htmlUrl"] = $url . "/" . str_replace(ABSPATH , "", $sphinx->getHTML($abschnitte[$i]["id"]));
        }

        return $abschnitte;
    }

    /**
     * [addAbschnitt description]
     * Abschnitt mit Content wird erstellt
     * @param [type] $content [description]
     * @param [type] $doc_id  [description]
     */
    public function addAbschnitt ($content, $doc_id) {
        $sphinx = new SphinxDocument("", "", $doc_id);
        $sphinx->addAbschnitt($content);

        $userName = $this->getUserName();
        $this->updateNewsFeed("Ein Abschnitt wurde von $userName hinzugefügt.");
    }

    /**
     * [updateAbschnitt description]
     * Content eines Abschnitts wird überschrieben
     * @param  [int] $doc_id       [description]
     * @param  [int] $abschnitt_id [description]
     * @param  [string] $content      [description]
     * @return [type]               [description]
     */
    public function updateAbschnitt($doc_id, $abschnitt_id, $content) {
      $sphinx = new SphinxDocument("", "", $doc_id);
      $sphinx->updateAbschnitt($abschnitt_id, $content);

      $userName = $this->getUserName();
      $this->updateNewsFeed("Ein Abschnitt wurde von $userName aktualisiert.");
    }


    /**
     * [selectLayout description]
     * Dokument wird Layout zugewiesen
     * @param  [int] $group_id [description]
     * @param  [int] $doc_id   [description]
     */
    public function selectLayout($doc_id, $layout, $oldLayout) {
        global $wpdb;
        $sql = $wpdb->update(
            $this->dbTableNameDocuments,
            array(
                "layout" => $layout
            ),
            array( 
                'id' => $doc_id
            )
        );
        $sphinx = new SphinxDocument("", "", $doc_id);
        $sphinx->changeConfig($oldLayout, $layout);
    }

    /**
     * @param $document_id
     * Dokument wird gelöscht
     */
    public function deleteDocument($document_id){
        global $wpdb;
        $document = $wpdb->get_row("SELECT * FROM $this->dbTableNameDocuments WHERE id=$document_id");

        $sphinx = new SphinxDocument("", "", $document->id);
        $sphinx->deleteDocument();
        $wpdb->delete($this->dbTableNameDocuments, array(
            'id' => $document_id
        ));

        $this->updateNewsFeed("Dokument $document->name wurde gelöscht.");
    }


    /**
     * [createNewDocument description]
     * Erstellt Neues Dokument in der Datenbank und im Dateisystem
     * @param  [string] $project_name [description]
     * @param  [string] $authorName   [description]
     * @param  [int] $userId       [description]
     * @return [type]               [description]
     */
    public function createNewDocument($project_name, $authorName, $userId, $layout) {
        global $wpdb;

        //$project_path = $sphinx->getProjektPfad();

        if(!$wpdb->insert($this->dbTableNameDocuments, array(
                'name' => $project_name,
                'layout' => $layout,
                'updated_at' => current_time('mysql'),
                'user_id' => $userId
        ))) {
            die("Eintrag in der DB nicht erfolgreich - Models/Documents.php");
        }else{
            $sphinx = new SphinxDocument($project_name, $authorName, $wpdb->insert_id);//die id nicht vergessen!
            $sphinx->changeConfig("default", $layout);

            $this->updateNewsFeed("$authorName hat ein neues Dokument $project_name erstellt.");
        }
    }


	/**
	 * [initDatabase description]
	 * Erstellt Datenbanken für Dokumente
	 * Datenbanken:
	 * Prefix + dokumummy_documents
	 * Prefix + dokumummy_documents_in_groups
	 */
	public function initDatabase() {

		global $wpdb;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		/**
		 * Datenbank für Dokumente
		 */
		$documents_table = $this->dbTableNameDocuments;
		$wps_usertable = $wpdb->prefix . "users";

	    $sql = "CREATE TABLE IF NOT EXISTS $documents_table (
			id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			name varchar(255) NOT NULL,
			layout varchar(255) NOT NULL,
			user_id bigint(20) UNSIGNED NOT NULL,
			created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
			updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (id),
			FOREIGN KEY (user_id) references $wps_usertable(ID) on update cascade on delete cascade
	    );";
		dbDelta( $sql );
		
		/**
         * Datenbanken für Verbindung von Gruppen zu Dokumenten
         */
        $documents_in_groups_table = $this->dbTableNameDocumentInGroup;
        $group_table = $wpdb->prefix . "dokumummy_groups";

        $sql = "CREATE TABLE IF NOT EXISTS $documents_in_groups_table (
            id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            document_id int(11) UNSIGNED NOT NULL,
            group_id int(11) UNSIGNED NOT NULL,
            created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
            updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY (id),
            FOREIGN KEY (document_id) references $documents_table(id) on update cascade on delete cascade,
            FOREIGN KEY (group_id) references $group_table(id) on update cascade on delete cascade
        );";
        dbDelta( $sql );

        /**
		 * Datenbanken zur Darstellung der aktuellen Änderungen
		 */
        $table = $this->dbTableNameUpdateFeed;
		$sql = "CREATE TABLE IF NOT EXISTS $table (
			id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
			news varchar(255) NOT NULL,
			created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id)
	    );";
		dbDelta( $sql );


        $this->updateNewsFeed("Vielen Dank für die Installation !");
	}


    public function getDownloadLinks($doc) {
        $response = array();
        $sphinx = new SphinxDocument("", "", $doc->id);
        $response["zip"] = $sphinx->invokeZipDownload($doc->name);
        $response["pdf"] = $sphinx->invokePDFDownload($doc->name);
        return $response;
    }

    private function updateNewsFeed($news) {
        global $wpdb;
        $wpdb->insert($this->dbTableNameUpdateFeed, array(
            'news' => $news
        ));
    }

    private function getUserName() {
        $current_user = wp_get_current_user();
        return $current_user->user_login;
    }
}

?>