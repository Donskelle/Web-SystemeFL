<?php


new DocumentView();
/**
 * Stellt Dokumenten dar
 */
class DocumentView {

    /**
     * [__construct description]
     * Stellt entsprechend der Anfrage das Dokument dar
     */
    public function __construct(){
        $doc = new Documents();
        // Neues Dokument erstellen
        if(isset($_POST["operation"]))
        {   
            if($_POST["operation"] == "create") {
                $current_user = wp_get_current_user();
                $doc->createNewDocument($this->saveInputs($_POST["project_name"]), $current_user->display_name, get_current_user_id(), $this->saveInputs($_POST["layout"]));
            }
            else if ($_POST["operation"] == "delete") {
                $doc->deleteDocument($this->saveInputs($_POST["id"]));
            }
            else if($_POST["operation"] == "selectGroup") {
                $group = new Groups();
                $group->selectGroup($this->saveInputs($_POST["selectedGroup"]), $this->saveInputs($_POST["document_id"]));
            }
            else if($_POST["operation"] == "addAbschnitt") {
                $doc->addAbschnitt(esc_textarea($_POST["content"]), $this->saveInputs($_POST["document_id"] ));
            }
            else if($_POST["operation"] == "setContentAbschnitt") {
                $doc->updateAbschnitt($this->saveInputs($_POST["document_id"]), $this->saveInputs($_POST["abschnitt_id"]), esc_textarea($_POST["content"]));
            }
            else if($_POST["operation"] == "deleteAbschnitt") {
                $doc->deleteAbschnitt($this->saveInputs($_POST["document_id"]), $this->saveInputs($_POST["abschnitt_id"]));
            }
            else if($_POST["operation"] == "selectLayout")
                $doc->selectLayout($this->saveInputs($_POST["document_id"]), $this->saveInputs($_POST["selectedLayout"]), $this->saveInputs($_POST["old_layout"]));
        }

        if(isset($_GET["id"]))
        {


            $document = $doc->getDocument($this->saveInputs($_GET["id"]));

            $document->abschnitte = array();
            $document->abschnitte = $doc->getAbschnitte($document->id);

            $downloadLinks = $doc->getDownloadLinks($document);
            $document->downloadZip = $downloadLinks["zip"];
            $document->downloadPdf = $downloadLinks["pdf"];

            $this->viewDocument($document);
        }
        else if(isset($_GET["create"])) {
            $this->viewDocumentCreateForm($_GET["create"]);
        }
        else {
            $authDocs = $doc->getDocumentsCreatedByUser(get_current_user_id());
            $this->viewShortDoc($authDocs); 
            $this->viewDocumentCreateForm();
        }
    }
    
    /**
     * [viewDocument description]
     * Stellt das übergebene Dokument dar
     * @param  [object] $document [description]
     */
    public function viewDocument($document) {
        $user = wp_get_current_user();

        new AdminBarLayoutSelection($document->id, $document->layout); //Die Layoutauswahl soll immer angezeugt werden.
        new AdminBarDownloadOption($document->downloadPdf, $document->downloadZip, $document->name);

        echo "<h2>$document->name</h2>";



        if($user->ID == $document->user_id)
        {
            new AdminBarDeleteButton($document->id); //Das Dokument löschen Feld soll nur angezeigt werden, wenn ein Dokument vorhanden ist und wenn der Benutzer erlaubt ist, dies zu tuen.
            $this->viewFormSelectGroup($document->id);
            //$this->viewFormSelectLayout($document);
        }
        if($user->ID == $document->user_id || $user->roles[0] == "dokuAdmin" || $user->roles[0] == "administratorf" )
        {

            $this->viewAbschnitte($document->abschnitte, $document->id, true);

        }
        else {
            $this->viewAbschnitte($document->abschnitte, $document->id, false);
        }
        $this->viewAddAbschnitt($document->id);
    }


    /**
     * [viewAbschnitte description]
     * Gibt ddie Abschnitte inklusive einer Form aus
     * @param  [array] $abschnitte [description]
     * @param  [int] $doc_id     [description]
     */
    public function viewAbschnitte($abschnitte, $doc_id, $boolAdmin) {
        $output = array();
        $output[] = "<h2>Abschnitte</h2>";
        foreach ($abschnitte as $ab) {
            $output[] = "<div class='abschnitt'>";
            $output[] = "<form action='' method='post'>";
            $output[] = '<input type="hidden" name="document_id" value="' . $doc_id . '"/>';
            $output[] = '<input type="hidden" name="abschnitt_id" value="' . $ab["id"] . '"/>';
            $output[] = '<input type="hidden" name="operation" value="setContentAbschnitt"/>';

            $output[] = "<textarea name='content' rows='15' >" . $ab["content"] . "</textarea>";
            $output[] = "<button type='submit'>Ändern</button>";
            $output[] = "</form>";

            // Wenn Admin können Abschnitte gelöscht werden
            if($boolAdmin) {
                $output[] = "<form action='' method='post' class='inlineForm'>";
                $output[] = '<input type="hidden" name="document_id" value="' . $doc_id . '"/>';
                $output[] = '<input type="hidden" name="abschnitt_id" value="' . $ab["id"] . '"/>';
                $output[] = '<input type="hidden" name="operation" value="deleteAbschnitt"/>';
                $output[] = "<button type='submit'>Löschen</button>";
                $output[] = "</form>";
            }
            
            
            
            $output[] = "<a target='_blank' class='viewDoc' href='" . $ab["htmlUrl"] . "'>Ansehen</a>";
            $output[] = "</div>";
        }
        echo implode("\n", $output);
    }

    /**
     * [viewAddAbschnitt description]
     * Gibt eine Form zum Erstellen eines Abschnitts aus
     * @param  [int] $doc_id [description]
     */
    public function viewAddAbschnitt($doc_id){
        $output = array();
        $output[] = "<h2>Abschnitt hinzufügen</h2>";
        $output[] = '<form action="" method="post"  class="abschnitt">';
        $output[] = '<input type="hidden" name="operation" value="addAbschnitt"/>';
        $output[] = '<input type="hidden" name="document_id" value="' . $doc_id . '"/>';
        $output[] = '<textarea name="content" rows="15" value=""></textarea>';
        $output[] = '<button type="submit">Hinzufügen</button>';
        $output[] = '</form>';

        echo implode("\n", $output);
    }


    public function viewFormSelectLayout($doc) {
        $output = array();
        $output[] = "<h2>Layout auswählen</h2>";
        $output[] = "<form action=\"\" method=\"post\">";
        $output[] = '<input type="hidden" name="operation" value="selectLayout"/>';
        $output[] = '<input type="hidden" name="old_layout" value="' . $doc->layout . '"/>';
        $output[] = '<input type="hidden" name="document_id" value="' . $doc->id . '"/>';
        $output[] = "<select name='selectedLayout'>";

        if($doc->layout == "default")
        {
            $output[] = '<option selected value="default">Layout1</option>';
        }
        else {
            $output[] = '<option value="default">Layout1</option>';
        }

        if($doc->layout == "sphinxdoc") 
        {
            $output[] = '<option selected value="sphinxdoc">Layout2</option>';
        }
        else {
            $output[] = '<option value="sphinxdoc">Layout2</option>';
        }

        if($doc->layout == "agogo") 
        {
            $output[] = '<option selected value="agogo">Layout3</option>';
        }
        else {
            $output[] = '<option value="agogo">Layout3</option>';
        }

        if($doc->layout == "nature") 
        {
            $output[] = '<option selected value="nature">Layout4</option>';
        }
        else{
            $output[] = '<option value="nature">Layout4</option>';
        }

        if($doc->layout == "scrolls") 
        {
            $output[] = '<option selected value="scrolls">Layout5</option>';
        }
        else {
            $output[] = '<option value="scrolls">Layout5</option>';
        }

        $output[] = "</select>";
        $output[] = '<button type="submit" >Layout ändern</button>';
        $output[] = "</form>";
        
        echo implode("\n", $output);
    }

    /**
     * [viewFormSelectGroup description]
     * Form zum Wählen einer Gruppe für ein Dokument darstellen
     */
    public function viewFormSelectGroup($id) {
        $group = new Groups();
        $groups = $group->getDocumentGroups($id);

        new AdminBarGroupSelection($groups, $id);
    }


    /**
     * [viewShortDoc description]\
     * Gibt eine Übersicht der übergebenen Dokumente aus
     * @param  [array] $documents [description]
     */
    public function viewShortDoc($documents) {
        $output = array();
        $output[] = '<h2>Deine Dokument</h2>';
        foreach ($documents as $doc) {
            $output[] = "<div>";
            $output[] = "<a href='?id=$doc->id'>$doc->name</a>";
            $output[] = "</div>";
        }
        echo implode("\n", $output);
    }



    /**
     * [viewDocumentCreateForm description]
     * Gibt eine Form zum Erstellen eines Dokuments aus
     */
    public function viewDocumentCreateForm() {
        $output = array();
        $output[] = '<h2>Dokument erstellen</h2>';
        $output[] = '<form action="./" method="post">';
            $output[] = '<input type="hidden" name="operation" value="create"/>';
            $output[] = '<input type="text" name="project_name" value="" placeholder="Dokumentenname" class="inputNameCreate" required maxlength="250"/>';
            $output[] = '<select class="form-control" name="layout">';
                $output[] = '<option value="default">Layout1</option>';
                $output[] = '<option value="sphinxdoc">Layout2</option>';
                $output[] = '<option value="agogo">Layout3</option>';
                $output[] = '<option value="sphinx_rtd_theme">Layout4</option>';
                $output[] = '<option value="scrolls">Layout5</option>';
            $output[] = '</select>';
            $output[] = '<button type="submit" class="buttonCreate">Erstellen</button>';
        $output[] = '</form>';
        echo implode("\n", $output);
    }


    /**
     * [saveInputs description]
     * Escaped des übergebenen String
     * @param  [string] $str [description]
     * @return [string]      [description]
     * Sicherer String
     */
    public function saveInputs($str) {

        $str = esc_sql($str);
        return $str;
    }
}