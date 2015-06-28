<?php


new GroupView();

/**
 * Stellt die Gruppenansicht und Operation zur Verfügung
 */
class GroupView {

	public function __construct() {
        $groups = new Groups();
		$doc = new Documents();

        /**
         * Wenn gepostet wurde
         */
        if($_POST) {
            // Gruppe wird erstellt
            if(isset($_POST["group_name"])) {
                $groups->saveGroup($this->saveInputs($_POST["group_name"]), $this->saveInputs($_POST["group_description"]), get_current_user_id());
            }
            // User wird hinzugefügt
            if(isset($_POST["userToAdd"])) {
                $groups->addUser($this->saveInputs($_POST["group_id"]), $this->saveInputs($_POST["userToAdd"]));
            }

            if(isset($_POST["userToDelete"])) {
                $groups->deleteUser($this->saveInputs($_POST["group_id"]), $this->saveInputs($_POST["userToDelete"]));
            }
        }
        
        // Bestimmte ID wird abgefragt
        if(isset($_GET["id"]))
        {
            $user = wp_get_current_user();
            $detailGroup = $groups->getGroupAndUsers($this->saveInputs($_GET["id"]));

            $detailGroup->userToAdd = array();

            if($user->roles[0] == "dokuAdmin" || $user->roles[0] == "administrator" )
            {
                $detailGroup->userToAdd = $groups->getUserNotInGroup($this->saveInputs($_GET["id"]));
            }
            $documentsInGroup = $doc->getDocumentsInGroup($this->saveInputs($_GET["id"]));

            echo $this->detailView($detailGroup, $documentsInGroup);
        }

        // Allgemeine Gruppenansicht
        else {
            $arGroups = $groups->getAuthGroups();
            echo $this->groupView($arGroups);
        }
    }


    /**
     * Standardgruppenansicht
     *
     * @param $arGroups
     * @return string
     */
    private function groupView($arGroups) {
    	$output = array();
        $user = wp_get_current_user();

    	$output[] = "<div class='groupView'>";

        /**
         * Wenn Berechtigungen wird die erstell Form dargestellt.
         */
        if($user->roles[0] == "dokuAdmin" || $user->roles[0] == "administrator" )
        {
            $output[] = '<h2>Gruppe erstellen</h2>';
            $output[] = $this->viewFormCreateGroup();
        }



    	if(count($arGroups) > 0) {
            $output[] = '<h2>Aktive Gruppen</h2>';
    		foreach ( $arGroups as $group ) {
    			$output[] = "<div class='group'>";
    				$output[] = "<p class='groupName'><a href='" . $_SERVER["REQUEST_URI"] . "?id=" .  $group->id . "'>" . $group->name . "</a></p>";
    				$output[] = "<p>" . $group->description . "</p>";
    			$output[] = "</div>";
    		}
    	}
    	else {
            if($user->roles[0] == "dokuAdmin" || $user->roles[0] == "administrator" )
            {
                $output[] = "<p>Es wurden keine Gruppen erstellt.</p>";
            }
            else {
                $output[] = "<p>Sie sind in keiner Gruppe</p>";
            }
    	}

    	$output[] = "</div>";

    	return implode("\n", $output);
    }


    /**
     * Einzel Gruppenansicht
     * @param $arDetailGroup
     * @return string
     */
    private function detailView($arDetailGroup, $documents) {
        $output = array();
        $currentUser = wp_get_current_user();

        $output[] = "<div class='groupView'>";
            $output[] = "<h2>" . $arDetailGroup->name . "</h2>";

            if($arDetailGroup->description != "" && $arDetailGroup->description != null)
                $output[] = "<p>" . $arDetailGroup->description . "</p>";



            $output[] = "<div class='dm_documents'>";
            $output[] = "<h2>Dokumente von " . $arDetailGroup->name . "</h2>";
            if(count($documents) > 0)
            {   
                $pagesFilter = array(
                    'post_type' => 'page',
                    'meta_key' => 'custom_element_grid_class_meta_box',
                    'meta_value' => 'Dokumente'
                );

                $pages = get_posts($pagesFilter);
                $documentLink = get_permalink($pages[0]->ID);



                foreach($documents as $document) {
                    $output[] = "<p><a href='" . $documentLink . "?id=" . $document->id . "'>" . $document->name . "</a></p>";
                }
            }
            else {
                $output[] = "<p>Bisher keine Dokumente hinzugefügt</p>";
            }
            $output[] = "</div>";

            $output[] = "<div class='users'>";
            $output[] = "<h2>Benutzer von " . $arDetailGroup->name . "</h2>";
            
            foreach($arDetailGroup->user as $user) {
                $output[] = "<p>";
                //Wenn Admin Form zum Entfernen von Usern hinzufügen
                //Worpress Custom Attribute
//                if($currentUser->roles[0] == "dokuAdmin" || $currentUser->roles[0] == "administrator" ) {
//                    $output[] = $this->viewFormDeleteUser($_GET["id"], $user->user_id, $user->user_nicename);
//                }
                if(current_user_can('manage_user') || $currentUser->roles[0] == "administrator"){
                    $output[] = $this->viewFormDeleteUser($_GET["id"], $user->user_id, $user->user_nicename);
                }
                // Wenn kein Admin nur Namen
                else {
                    $output[] = $user->user_nicename;
                }
                $output[] = "</p>";
            }

           

            if(count($arDetailGroup->userToAdd) > 0)
            {
                $output[] = "<h2>Nutzer hinzufügen</h2>";
                foreach($arDetailGroup->userToAdd as $user) {
                    $output[] = "<p>";
                    $output[] = $this->viewFormAddUser($_GET["id"], $user->ID, $user->user_nicename);
                    $output[] = "</p>";
                }
            }

            $output[] = "</div>";
        $output[] = "</div>";

        return implode("\n", $output);
    }

    /**
     * Form für die Erstellung einer neuen Gruppe
     * @return string
     */
    private function viewFormCreateGroup() {
        $response = array();
        $response[] = '<form action="" method="post">';
            $response[] = '<input type="text" name="group_name" class="dm_formInputs" value="" placeholder="Gruppenname" required maxlength="250"/>';
            $response[] = '<input type="text" name="group_description" class="dm_formInputs" Placeholder="Beschreibung" maxlength="250"/>';
            $response[] = '<input type="submit" name="submit" value="Erstellen" class="button" />';
        $response[] = '</form>';
        return implode("\n", $response);
    }

    /**
     * Füge einer Gruppe einen neuen User zu.
     *
     * @param $group_id
     * @param $user_id
     * @param $user_name
     * @return string
     */
    private function viewFormAddUser($group_id, $user_id, $user_name) {
        $response = array();
        $response[] = '<form action="" method="post">';        
            $response[] = '<input type="hidden" name="group_id" value="' . $group_id . '"/>';
            $response[] = '<input type="hidden" name="userToAdd" value="' . $user_id . '"/>';
            $response[] = '<span class="userNameForm">' . $user_name . '</span>';
            $response[] = '<input type="submit" name="submit" value="Hinzufügen" class="button" />';
        $response[] = '</form>';
        return implode("\n", $response);
    }

    /**
     * Form: Lösche einen Nutzer von einer Gruppe.
     * @param $group_id
     * @param $user_id
     * @param $user_name
     * @return string
     */
    private function viewFormDeleteUser($group_id, $user_id, $user_name) {
        $response = array();
        $response[] = '<form action="" method="post">';        
            $response[] = '<input type="hidden" name="group_id" value="' . $group_id . '"/>';
            $response[] = '<input type="hidden" name="userToDelete" value="' . $user_id . '"/>';
            $response[] = '<span class="userNameForm">' . $user_name . '</span>';
            $response[] = '<input type="submit" name="submit" value="Entfernen" class="button" />';
        $response[] = '</form>';
        return implode("\n", $response);
    }


    /**
     * [saveInputs description]
     * Escaped des übergebenen String
     * @param  [string] $str [description]
     * @return [string]      [description]
     * Sicherer String
     */
    public function saveInputs($str) {
        $str = esc_sql ($str);
        return $str;
    }
}
?>