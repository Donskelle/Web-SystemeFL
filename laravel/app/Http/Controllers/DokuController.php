<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Validator;
use App\Models\document;

class DokuController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Doku Controller
      |--------------------------------------------------------------------------
      |
      |Dieser Controller dient zum Anzeigen der Verschiedenen Dokumenten,
      |so wie die Bearbeitung und Anzeige der entsprechenden Ausgabe Formate
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }
    
    /**
     * gibt das View zum anlegen eines neuen Dokuments zurück
     *
     * @return Response
     */
    public function newDocu() {
        $view = $this->getView("new");
        $view->Titel = "Neues Dokument";
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        return $view;
    }

    /**
     * Gibt ein Dokument zurück aus dem Privaten Bereich
     *
     * @return Response
     */
    public function param2($access, $docuID) {
        $document = document::where("id", "=", $docuID)->first();
        if ($document == NULL)
            return view('errors.404');
        $view = $this->getView($access);
        $view->docuAccess = $access;
        $view->document = $document;
        if ($access != "add") {
            $view->documentContent = $this->getDocument($view->document->path);
        }
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        return $view;
    }
    
    /**
     * Gibt ein Dokument zurück aus dem Public Bereich
     *
     * @return Response
     */
    public function param3($access, $group, $docuID) {
        $document = document::where("id", "=", $docuID)->first();
        if ($document == NULL)
            return view('errors.404');
        $view = $this->getView($access);
        $view->docuAccess = $access;
        $view->docuGroupAktive = $group;
        $view->document = $document;
        $view->documentContent = $this->getDocument($view->document->path);
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        return $view;
    }
    /**
     * Gibt ein das entsprechende View zurück
     *
     * @return Response
     */
    private function getView($access) {
        if ($access == "private") {
            return view('dokuViews.privateDoku');
        } elseif ($access == "public") {
            return view('dokuViews.publicDoku');
        } elseif ($access == "new") {
            return view('dokuViews.newDoku');
        } elseif ($access == "add") {
            return view('dokuViews.addDoku');
        } else {
            return view('home');
        }
    }

    /**
     * Erstellt ein neues Dokument auf dem Server mit den entsprechenden Paramenter im Post
     *
     * @return Response
     */
    public function createDocu() {
        $data = Input::all();
        if ($data["path"] == "") {
            $validator = Validator::make($data, ['name' => 'required|max:255']);
        } else {
            $validator = Validator::make($data, ['name' => 'required|max:255', 'path' => 'required|max:255|unique:documents',]);
        }
        if ($validator->fails()) {
            return redirect('/document/new/')->withErrors($validator)->withInput();
        }

        $data["path"] = "/var/www/sphinx/" . count(document::all()) . "_" . $data["name"];

        $document = new document;

        $document->name = $data["name"];
        $document->path = $data["path"];
        $document->layout = $data["layout"];
        $document->user_id = \Auth::user()->id;
        $document->save();

        $this->createSphinxDoc($document->path, $document->name, \Auth::user()->username);
        $this->changeRechte();
        $this->changeValueInConf("default", $document->layout, $document->path);
        $this->changeValueInConf("#language = None", "language =\"de\"", $document->path);
        $this->makeHTML($document->path);

        $this->addNewNews($document->id, 0, 1, "Neues Dokument angelegt mit dem namen" . $document->name);
        return redirect('/document/private/' . $document->id);
    }
    /**
     * Erstellt einen neuen Abschnitt für das entsprechende Dokument auf dem Server an
     *
     * @return Letzte Adresse
     */
    public function addDocu() {
        $data = Input::all();
        $filename = $data['pathDocu'] . "/source/" . $data['name'] . ".rst";
        if (file_exists($filename)) {
            return redirect('/document/add/' . $data['id'])->withInput();
        } else {
            $rstFile = fopen($filename, "w");
            fwrite($rstFile, "Neuer Abschnitt" . PHP_EOL);
            fwrite($rstFile, "---------------" . PHP_EOL);
            fclose($rstFile);
            $this->changeRechte();
            $this->makeHTML($data['pathDocu']);
        }
        $this->addNewNews($data['id'], 0, 1, "Neues Abschnitt für " . $data['pathDocu'] );
        return redirect($data['lastURL']);
    }

    /**
     * Erstellt ein neues Dokument mittels Sphinx über eine eigens geschriebende python Datei
     */
    private function createSphinxDoc($path, $name, $authorName) {
        $shell_Befehl = "/var/www/sphinx/create ";
        $shell_Befehl.= sprintf(" %s", $path);    //Project name
        $shell_Befehl.= sprintf(" %s", $name);    //Project name
        $shell_Befehl.= sprintf(" %s", $authorName);     //Author name
        $shell_Befehl.= sprintf(" %s", "V1.0");          //Version of project

        $output = shell_exec("python " . $shell_Befehl);
        //dd($output);
    }

    /**
     * Rechte auf dem Server neu setzten für eine Fest gesetzte Gruppe www 
     * und den beinhalteten Nutzeren pharao und www-data     
     */
    private function changeRechte() {
        $output = shell_exec("sudo /var/www/sphinx/./rechte.sh");
        //dd($output);
    }
    /**
     * Austauschen der Variablen der Config mittels fester Definitionen
     * Layout
     * Autor
     * Verison
     * 
     * @param type $olt
     * @param type $new
     * @param type $path
     */
    private function changeValueInConf($olt, $new, $path) {
        $output = shell_exec("sudo perl -pi -e 's/$olt/$new/g' $path/source/conf.py");
        //dd($output);
    }

    /**
     * Generierung der HTML Ausgabe mittels der myMake.sh
     * @param type $path
     */
    private function makeHTML($path) {
        $output = shell_exec("sudo /var/www/sphinx/./myMake.sh " . $path . " html");
        //$output = shell_exec("sudo sphinx-build -b html $path/source/ $path/build/html");
        //dd($output);
        $this->addNewNews(0, 0, 3, "Erstellt HTML");
    }
    
    /**
     * Generierung der PDF Ausgabe mittels der myMake.sh
     * @param type $path
     */
    private function makePDF($path) {
        $output = shell_exec("sudo /var/www/sphinx/./myMake.sh " . $path . " latexpdf");
        //dd($output);   
        $this->addNewNews(0, 0, 3, "Erstellt PDF");
    }

    /**
     * Generierung der PDF und Ausgabe 
     * @return type
     */
    public function getPDF() {
        $data = Input::all();
        $document = document::where("id", "=", $data['docuId'])->first();
        $this->makePDF($document->path);
        return str_replace("/var/www/sphinx/", "", $document->path) . "/build/latex/" . $document->name . ".pdf";
    }

    /**
     * Gibt alle Abschnitte als HTML und Markdown zurück 
     * um die entsprechenden Abschnitte zu berabeiten
     * 
     * 
     * @param type $docuPath
     * @return array
     */
    public function getDocument($docuPath) {
        $Document = [];
        $countID = 0;
        if (file_exists($docuPath . "/source/index.rst")) {

            array_push($Document, [
                'id' => "editor" . $countID++,
                'name' => "index",
                'html' => $this->readHtml($docuPath . "/build/html/index.html"),
                'mardown' => $this->readMardown($docuPath . "/source/index.rst")
            ]);
            $allRst = [];
            $weeds = array('.', '..');
            $directori = array_diff(scandir($docuPath . "/source/"), $weeds);

            foreach ($directori as $value) {
                if (pathinfo($value, PATHINFO_EXTENSION) == "rst")
                    array_push($allRst, [
                        'name' => pathinfo($value, PATHINFO_FILENAME),
                        'add' => FALSE
                    ]);
            }
            $index = file_get_contents($docuPath . "/source/index.rst");
            $pos = strpos($index, ".. toctree::");
            $index = substr($index, $pos); // gibt toctree zurück 


            $index = str_replace("   ", "@part@", $index);
            $schluesselwoerter = preg_split("/[\s,]+/", $index);
            foreach ($schluesselwoerter as $key => $value) {
                if (strpos($value, '@part@') !== FALSE) {
                    $value = str_replace("@part@", "", $value);
                    if (file_exists($docuPath . "/source/" . $value . ".rst")) {
                        foreach ($allRst as $key => $value2) {
                            if ($value2['name'] == $value) {
                                $allRst[$key]['add'] = TRUE;
                            }
                        }
                        array_push($Document, [
                            'id' => "editor" . $countID++,
                            'name' => $value,
                            'html' => $this->readHtml($docuPath . "/build/html/" . $value . ".html"),
                            'mardown' => $this->readMardown($docuPath . "/source/" . $value . ".rst")
                        ]);
                    }
                }
            }
            foreach ($allRst as $key => $value) {
                if ($value['add'] === FALSE) {
                    array_push($Document, [
                        'id' => "editor" . $countID++,
                        'name' => $value['name'],
                        'html' => $this->readHtml($docuPath . "/build/html/" . $value['name'] . ".html"),
                        'mardown' => $this->readMardown($docuPath . "/source/" . $value['name'] . ".rst")
                    ]);
                }
            }
        }
        return $Document;
    }

    /**
     * Liest die entsprechende rst Datei und gibt den Hinhalt zurück 
     * 
     * @param type $path
     * @return type
     */
    public function readMardown($path) {
        $contents = file_get_contents($path);
        return $contents;
    }

    /**
     * Liest die entsprechende HTML Datei und gibt nur den entsprechenden Content zurück
     * Der Conntent hengt vom Layout ab
     * 
     * Wichtig es werden nicht alle HTML Tags unterstüzt 
     * Selection
     * Nav
     * ...
     * 
     * @param type $path
     * @return type
     */
    public function readHtml($path) {
        $homepage = file_get_contents($path);
        $dom = new \DOMDocument();

        $dom->loadHTML($homepage);
        $xpath = new \DOMXpath($dom);

        $headerlink = $xpath->query('//a[@class="headerlink"]');
        $replaceString = $dom->saveHTML($headerlink->item(0));

        $result = $xpath->query('//div[@class="body"]');
        $htmlString = $dom->saveHTML($result->item(0));

        // return str_replace($replaceString, "", $htmlString);
        return $htmlString;
    }

    /**
     * Speichert die Änderungen an dem Dokument 
     * und generiert die entsprechende HTML Ausgabe neu
     * 
     * Wenn die geänderte Datei die Index Datei ist wird die Seite neu geladen 
     * 
     * Wenn nicht wird der entsprechende Teil mittel Ajax neu Ausgegeben
     * 
     * Es kann nur eine Datei zurzeit geändert werden 
     * wenn man an meherer arbeit die Index Datei als letztes Speicheren 
     * 
     * 
     * @return type
     */
    public function saveDocu() {
        $data = Input::all();

        $document = document::where("id", "=", $data['docuId'])->first();
        $path = $document->path . "/source/" . $data['name'] . ".rst";
        $rstFile = fopen($path, "w");
        fwrite($rstFile, $data['makedown']);
        fclose($rstFile);
        $this->makeHTML($document->path);
        $this->addNewNews($document->id, 0, 3, "Speichert Änderungen an Dokument");
        if ($data['name'] == "index") {
            return \Response::json([ "overwrite" => TRUE]);
        } else {
            $return = [ 'id' => $data['partId'],
                'name' => $data['name'],
                'html' => $this->readHtml($document->path . "/build/html/" . $data['name'] . ".html"),
                'mardown' => $this->readMardown($document->path . "/source/" . $data['name'] . ".rst"),
                "overwrite" => FALSE
            ];
            return \Response::json($return);
        }
    }

}
