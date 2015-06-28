<?php

require "DocumentAbschnitt.php";


/**
 * Class SphinxDocument
 *
 * Diese Klasse verwaltet ein Sphinxprojekt (Document).
 *
 *
 */
class SphinxDocument {

    /**
     * Speicherordner der Sphinxprojekte.
     * @var
     */
    private $sphinxDirPath = "SphinxProjects";


    /**
     * Pfad zu createDocument.py
     *
     * @var string
     */
    private $sphinxScriptCreateDocumentPath = "Scripts/createDocument.py";


    /**
     * Pfad zur rechte.sh
     *
     * @var string
     */
    private $sphinxScriptPermissionsPath = "Scripts/./changePermission.sh";


    /**
     * @var array
     */
    private $aAbschnitteDesDokuments = array();


    /**
     * Der Pfad zum Projektordner
     * @var string
     */
    private $sProjectPath = "";

    /**
     * @var bool
     */
    private $documentDeleted = false;

    /**
     * Die Id des Projektes in der Datenbank
     * @var string
     */
    private $sProjectId = "";

    /**
     * Counter für die Abschnitte des Dokumentes
     * @var int
     */
    private $internalAbschnittCounter = 0;

    /**
     * Erlaubt das Erstellen eines neuen , aber auch das Aufrufen eines alten Projektes.
     *
     * Wenn ein projectId, aber kein anderer Parameter übergeben wird, soll ein existierendes Projekt aufgerufen werden.
     *
     * Wenn projectName, authorName und projectId übergeben werden, wird ein neues Projekt angelegt.
     *
     * @param string $projectName
     * @param string $authorName
     * @param string $projectId
     */
    public function __construct($projectName="", $authorName="", $projectId = ""){
        $this->sphinxDirPath = plugin_dir_path( __FILE__ ) . $this->sphinxDirPath ;
        $this->sphinxScriptCreateDocumentPath = plugin_dir_path( __FILE__ ) . $this->sphinxScriptCreateDocumentPath ;
        $this->sphinxScriptPermissionsPath = plugin_dir_path( __FILE__ ) . $this->sphinxScriptPermissionsPath ;


        //Felder mit neuen Werten versorgen.
        $this->sProjectId = intval($projectId);
        $this->sProjectPath = $this->sphinxDirPath."/".$this->sProjectId;

        if($projectName !== "" AND $authorName !== "") {
            $this->createNewDocument($projectName, $authorName);
        }else {
            $this->sProjectPath = $this->sProjectPath."/".$this->extractProjectName();

            if ($this->isProjectExisting($this->sProjectPath)) {
                $this->aAbschnitteDesDokuments = $this->extractAbschnitte($this->sProjectPath);
            } else {
                //siehe isProjectExisting
                die("corrupted projectPath - SphinxDocument.php: $this->sProjectPath");
            }
        }
    }

    /**
     * Erzeugt einen neuen Abschnitt.
     *
     * Erzeugt ein Abschnittobjekt, schreibt es ins Filesystem, updatet die index.rst und hängt die Abschnitt ans interne Abschnittarray.
     *
     * @param  $content string
     * @return string Die Id des Abschnittes.
     */
    public function addAbschnitt($content){
        $this->isUnuseable();

        $id = intval($this->getNewAbschnittFileName());
        $abschnitt = new DocumentAbschnitt("doc".$id, $content, $id);
        $this->buildAbschnittFile($abschnitt);
        $this->addAbschnittToIndex($abschnitt);
        $this->makeHTML();
        return $abschnitt->getAbschnittId();
    }

    /**
     * Entfernt den Abschnitt mit der übergebenen ID vom Filesystem und von der index.rst
     *
     * @param $abschnittId
     * @throws Exception
     */
    public function removeAbschnitt($abschnittId){
        $this->isUnuseable();
        $abschnitt = null;

        foreach($this->aAbschnitteDesDokuments as $ab){
            if($ab->getAbschnittId() == $abschnittId){
                $abschnitt = $ab;
                break;
            }else{
                //die("Falsche Abschnitt ID - SphinxDocuments.php");
            }
        }
        $this->removeAbschnittFromIndexFile($abschnitt);
        $this->deleteAbschnittFromFS($abschnitt);
        $this->makeHTML();
    }


    /**
     *  Extrahiert den Projektnamen von den Projektpfad.
     *
     * Die Struktur des Projektes ist /var/www/wordpress..../SphinxProjects/id/projectName
     * Der Projektpfad geht bis id. Dann wird mit scandir und Ausfiltern der Name des Projektes gefunden.
     *
     * @return string Name des Projektes
     */
    private function extractProjectName(){
        $scanDir = array_diff(scandir($this->sProjectPath), array(".", "..")); //scandir gibt auch die verzeichnisse "." und ".." zurück. Diese müssen entfernt werden.
        return array_pop($scanDir); //das letzte u. einzige Element ist der Verzeichnisname.
    }


    /**
     * Updatet einen Abschnitt im Filesystem.
     *
     * @param $abschnittId string Die Id des Abschnitts.
     * @param $content string Neuer Inhalt des Abschnitts
     * @return bool Wurde ein Abschnitt gefunden und geupdated?
     * @throws Exception
     */
    public function updateAbschnitt($abschnittId, $content){
        $this->isUnuseable();

        //wurde ein Abschnitt geupdated?
        $bUpdated = false;
        //Der Abschnitt, der upgedated werden soll.
        $updateAbschnitt = null;
        foreach($this->aAbschnitteDesDokuments as $abschnitt){
            if($abschnitt->getAbschnittId() == $abschnittId){
                $abschnitt->setAbschnittContent($content);
                $this->buildAbschnittFile($abschnitt);
                $bUpdated = true;
                break;
            }
        }
        $this->makeHTML();
        return $bUpdated;
    }


    /**
     * Fügt einen Abschnitt dem Dokument zu.
     *
     * Fügt es dem Objekt und der index.rst zu.
     *
     * @param $abschnitt DocumentAbschnitt
     */
    private function addAbschnittToIndex($abschnitt){
        $indexContent = file_get_contents($this->sProjectPath."/source/index.rst");

        $search_string = "   :maxdepth: 2".PHP_EOL.PHP_EOL; //Enspricht dem Keyword unter toctree in der index.rst
        foreach($this->aAbschnitteDesDokuments as $ab){
            $search_string .= "   ".$ab->getFileName().PHP_EOL;
        }

        $replace_str = $search_string . "   " . $abschnitt->getFileName().PHP_EOL;


        $this->aAbschnitteDesDokuments[]=$abschnitt;//Füge den Abschnitt dem internen Verzeichnis zu.

        $str = preg_replace("/$search_string/s", $replace_str, $indexContent);
        file_put_contents($this->sProjectPath."/source/index.rst", $str);
    }


    /**
     *  Entfernt einen Abchnitt von dem Dokument.
     *
     *  Entfern von dem Objekt und der index.rst.
     *
     * @param $abschnitt DocumentAbschnitt
     */
    private function removeAbschnittFromIndexFile($abschnitt){
        //Der erste Teil ist wie addAbschnittToIndexFile. Zuerst wird das alte Abschnittsverzeichnis aufgebaut.
        $indexContent = file_get_contents($this->sProjectPath."/source/index.rst");

        $search_string = "   :maxdepth: 2".PHP_EOL.PHP_EOL; //Enspricht dem Keyword unter toctree in der index.rst
        $replace_string = $search_string;

        foreach($this->aAbschnitteDesDokuments as $ab){
            $search_string .="   ".$ab->getFileName().PHP_EOL;
        } //search_string hat jetzt alle Abschnitte als Einträge.


        //Entferne $abschnitt vom Verzeichnis.
        $tmp_arr = [];
        foreach($this->aAbschnitteDesDokuments as $ab){
            if($ab->getAbschnittId() != $abschnitt->getAbschnittId()){
                $tmp_arr[]=$ab;
            }
        }
        $this->aAbschnitteDesDokuments = $tmp_arr;

        //Baue den replace_str auf
        foreach($this->aAbschnitteDesDokuments as $ab){
            $replace_string .="   ".$ab->getFileName().PHP_EOL;
        } //replace_str hat jetzt alle aktuellen Abschnitte als Einträge.


        $str = preg_replace("/$search_string/s", $replace_string, $indexContent);
        file_put_contents($this->sProjectPath."/source/index.rst", $str);

    }


    /**
     * Schreibt den Inhalt des Abschnittes in eine Datei mit dem Filenamen, der im Abschnitt gespeichert ist.
     *
     * Die File liegt im source-Ordner des Projektes.
     *
     * @param $abschnitt DocumentAbschnitt
     */
    private function buildAbschnittFile($abschnitt){
        $abschnittFile = fopen(''.$this->sProjectPath.'/source/'.$abschnitt->getFileName().'.rst', 'w');
        fwrite($abschnittFile, $abschnitt->getAbschnittContent());
        fclose($abschnittFile);
    }

    /**
     * @return int
     */
    private function getNewAbschnittFileName(){
        $newId = 0;

        if(count($this->aAbschnitteDesDokuments) != 0){
            //Der idWert des letzten Abschnittes + 1
            $newId = intval(substr($this->aAbschnitteDesDokuments[count($this->aAbschnitteDesDokuments) -1]->getFileName(), 3)) + 1;
        }


        return $newId;
    }

    /**
     * Erzeugt eine AbschnittId.
     *
     * Diese Id ist im jeweiligen Abschnitt gespeichert und soll an den Client geschickt werden.
     *
     * @return int
     */
    private function generateAbschnittId(){
        $tmp = $this->internalAbschnittCounter;
        $this->internalAbschnittCounter++;
        return $tmp;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getProjektPfad(){
        $this->isUnuseable();
        return $this->sProjectPath;
    }

    /**
     * Ertellt eine neues Sphinxproject im Filesystem und legt einen ersten Abschnitt an.
     *
     * Wichtig: Keine DB Registrierung an dieser Stelle. Nur ausführen, nachdem das Projekt in der DB erstellt wurde.
     *
     * @param $project_name string Name des Projektes.
     * @param $authorName string Autorname, wahrscheinlich wp nicename. */
    private function createNewDocument( $project_name, $authorName){

        //Erstellt das Verzeichnis basierend auf der im construktor übergebenen ID.
        $res = mkdir("$this->sphinxDirPath/$this->sProjectId");
        if(!$res){
            die("Verzeichnis nicht erstellt - SphinxDocument.php");
        }
        //var/www/wordpress/...../Sphinx/SphinxProjects/id/projectName
        $this->sProjectPath = $this->sphinxDirPath."/".$this->sProjectId."/".$project_name;

        $command = "python ". $this->sphinxScriptCreateDocumentPath ."  \"".$this->sProjectPath."\" "."\"$project_name\""." ".$authorName;
        $output = shell_exec($command);
        //Erstelle den ersten Abschnitt.
        $this->addAbschnitt("h1".PHP_EOL."==");

        $this->changePermissions(); //gibt dem webserver schreib rechte für das neue Projekt.
    }


    /**
     * Überprüft ob ein Projekt bereits vorhanden ist.
     *
     *
     * @param $projekt_path string Projektpfad.
     * @return bool
     */
    private function isProjectExisting($projekt_path){
        $str = ''.$projekt_path.'/source/index.rst';
        return file_exists($str); //file_exists scheint Probleme mit dem erkennen von Dateien mit whitespaces im Pfad.
    }

    /**
     * Auch die Permission der Parentfolder muss geändert werden, damit Scripte ausgefüht werden können.
     * Führe dies nach jeder Änderung aus.
     */
    private function changePermissions(){
        shell_exec('sudo '.$this->sphinxScriptPermissionsPath.'');
    }


    /**
     * Erstellt das zugehörige HTML in projectid/name/build/html
     */
    private function makeHTML(){
        $this->changePermissions();
        shell_exec('cd "'.$this->sProjectPath.'" && make html');
    }

    /**
     * Erstellt das zugehörige PDF in projectid/project_name/latex
     */
    private function makePDF(){
        $this->changePermissions();
        shell_exec('cd "'.$this->sProjectPath.'" && make latexpdf');
    }


    /**
     * Gibt den HTML-Pfad aus.
     *
     * @param $abschnittId string Id des Abschnitt
     * @return string Pfad zum HTML des Abschnitts
     */
    private function getHTMLPath($abschnittId){
        //finde den Dateinamen des Abschnitts. 
        $fileName = "";
        try{
            if(count($this->aAbschnitteDesDokuments) == 0){
                throw new Exception("keine Abschnitte");
            }
            foreach($this->aAbschnitteDesDokuments as $ab){
                if($ab->getAbschnittId() == $abschnittId){
                    $fileName = $ab->getFileName();
                    break;
                }
            }
        }catch (Exception $e){
//            echo $e->getMessage();
        }

        return ''.$this->sProjectPath.'/build/html/'. $fileName.'.html';
    }

    /**
     * Public Methode zur Ausgabe des HTML-Phfades
     *
     * @param $abschnitt_id string Abschnitt Id
     * @return string HTML Pfad
     */
    public function getHTML($abschnitt_id){
        return $this->getHTMLPath($abschnitt_id);
    }


    /**
     * Löscht das Dokument dieses Objektes.
     *
     * Wichtig: Das Dokument ist hier nach unbrauchbar.
     *
     * @param $project_id Id des Projektes.
     */
    public function deleteDocument(){
        $this->isUnuseable();
        $command = "rm -rf \"$this->sProjectPath\"";
        shell_exec("$command");
        $this->documentDeleted = true;
    }

    /**
     * Wenn das referenzierte Dokument gelöscht wurde, aber das Objekt wieder verwendet wird, wird eine Excepton geworfen.
     *
     * Alle Publicmethods dieser Klasse haben diese Methode im Funktionskörper.
     *
     * @throws Exception 
     */
    private function isUnuseable(){
        if($this->documentDeleted){
            throw new Exception("Das referenzierte Dokument besteht nicht mehr");
        }
    }

    /**
     * Diese Methode liest die Abschnitte des Sphinx-Projektes aus der index.rst und gibt sie als Array von DocumentAbschnit zurück.
     *
     * Wenn der übergebene Parameter nicht stimmt, wird die() ausgelöst.
     *
     * @param $project_path string Projektpfad
     * @return array Die Abschnitte des Dokuments.
     */
    private function extractAbschnitte($project_path){
        //Abschnitte definiert in der index.rst unter Contents
        $abschnitte = array();

        $filePath = ''.$project_path.'/source/index.rst';

        //Auslesen der Index.rst um an Contents zu kommen.
        $data = file_get_contents("$filePath");

        //toctree ist ein Element in der index.rst. Unter Toctree werden die verlinkten Datein aufgeführt.
        $toc_tree = ".. toctree::".PHP_EOL.PHP_EOL;
        $toc_tree_pos = strpos($data ,$toc_tree);
        $indices = "Indices"; //das Element unter dem Contentsabschnitt.
        $indices_pos = strpos($data, $indices);

        //Auschneiden von Contents, hat noch andere Elemente.
        $content_with_other_stuff = substr($data, $toc_tree_pos, $indices_pos - $toc_tree_pos);


        //Alle im Contents referenzierten Dateien fangen mit doc1, doc2 usw an.
        $doc_results_array = array();
        preg_match_all("/doc[0-9]+/", $content_with_other_stuff, $doc_results_array); //Pro Treffer wird ein Array mit dem Ergebnis in das Ergebnis array gepushed


        //Reduzieren des Arrays.
        $doc_results = array();
        if(count($doc_results_array) > 0){ //gibt ein leeres array im array zurück. Die Länge zählt als 1.
            foreach($doc_results_array[0] as $val ){
                $doc_results[] = $val;
            }
        }

        //Erzeugen des Abschnittarrayss
        foreach($doc_results as $res){
            $id = $this->generateAbschnittId();
            $abschnitte[] = new DocumentAbschnitt($res, file_get_contents($this->sProjectPath."/source/$res".".rst"), $id);
        }
        return $abschnitte;
    }


    /**
     * Löscht die rst- und HTML-Datei vom Filesystem, die zu diesem Abschnitt gehört.
     *
     * @param $abschnitt DocumentAbschnitt
     */
    private function deleteAbschnittFromFS($abschnitt){
        shell_exec('sudo rm "'.$this->sProjectPath.'/source/'.$abschnitt->getFileName().'.rst"');
        shell_exec('sudo rm "'.$this->sProjectPath.'/build/html/'.$abschnitt->getFileName().'.html"'); //auch die html Datei muss entfernt werden.
    }

    /**
     * Gib die Abschnitte als Array zurück.
     *
     * @return array
     * @throws Exception
     */
    public function getAbschnitte(){
        $this->isUnuseable();

        $abschnitteContent = [];

        try{
            if(count($this->aAbschnitteDesDokuments) == 0){
                throw new Exception("keine Abschnitte");
            }
            foreach($this->aAbschnitteDesDokuments as $ab){
                $abschnitteContent[] = array(
                    "id" => $ab->getAbschnittId(),
                    "filename" => $ab->getFileName(),
                    "content" => $ab->getAbschnittContent()
                );
            }
        }catch (Exception $e){
//            echo $e->getMessage();
        }

        return $abschnitteContent;
    }


    /**
     * Baut die ZIP-Datei auf.
     */
    private function buildZipFile(){
        $this->changePermissions();
        shell_exec('cd "'.$this->sProjectPath.'" && sudo make singlehtml');

        $zip = new ZipArchive();

        if($zip->open(''.$this->sProjectPath.'/html.zip', ZipArchive::OVERWRITE)){
            $zip->addFile(''.$this->sProjectPath.'/build/singlehtml/index.html', "index.html");
            $zip->addFile(''.$this->sProjectPath.'/build/singlehtml/objects.inv', "objects.inv");
            $zip->addGlob(''.$this->sProjectPath.'/build/singlehtml/_static/*', GLOB_ERR, array(
                    'add_path' => '_static/',
                    'remove_all_path' => TRUE
            ));
            $zip->close();
        }else{
            die("zip erstellung ");
        }
    }

    /**
     * Stellt den Link zum ZIP-Download zur Verfügung.
     *
     * @param $project_name
     * @return mixed
     */
    public function invokeZipDownload($project_name){
        $this->buildZipFile();
        return plugins_url('SphinxProjects/'.$this->sProjectId.'/'.$project_name.'/html.zip', __FILE__);
    }

    /**
     * Stellt den Link zum PDF Download zur Verfügung.
     *
     * @param $project_name
     * @return mixed
     */
    public function invokePDFDownload($project_name){
        $this->makePDF();
        $pdf_name = str_replace(' ', '', $project_name);
        return plugins_url('SphinxProjects/'.$this->sProjectId.'/'.$project_name.'/build/latex/'.$pdf_name.'.pdf', __FILE__);
    }


    /**
     * Wechselt das Layout.
     *
     * Zum Wechsel des Layouts muss ein bestimmter Teil der conf.py geändert werden. Dazu wird dieser mit preg_replace
     * ausgetauscht.
     *
     * @param $oldLayout string Name des alten Layouts
     * @param $newLayout string Name des neuen Layouts
     */
    public function changeConfig($oldLayout, $newLayout){
        $content = file_get_contents(''.$this->sProjectPath.'/source/conf.py');
        $match_str = "/^html_theme = '$oldLayout'/m";
        $replace_str = "html_theme = '$newLayout'";
        $newContent = preg_replace($match_str, $replace_str, $content);
        file_put_contents(''.$this->sProjectPath.'/source/conf.py', $newContent);

        $this->makeHTML();
    }



}