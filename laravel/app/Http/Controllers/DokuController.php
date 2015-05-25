<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Validator;
use App\Models\document;

class DokuController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Home Controller
      |--------------------------------------------------------------------------
      |
      | This controller renders your application's "dashboard" for users that
      | are authenticated. Of course, you are free to change or remove the
      | controller as you wish. It is just here to get your app started!
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
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {
        return $this->param1("");
    }

    /**
     * Show the application dashboard to the user.
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
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function param2($access, $docuID) {        
        $document = document::where("id", "=", $docuID)->first();
        if($document==NULL)
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

    public function param3($access, $group, $docuID) {
        $document = document::where("id", "=", $docuID)->first();
          if($document==NULL)
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

        return redirect('/document/private/'.$document->id);
    }

    public function addDocu() {
        $data = Input::all();
        $filename = $data['pathDocu'] . "/source/" . $data['name'] . ".rst";
        if (file_exists($filename)) {
            return redirect('/document/add/'.$data['id'])->withInput();
        } else {
           $rstFile= fopen($filename, "w");
            fwrite($rstFile, "Neuer Abschnitt". PHP_EOL);
            fwrite($rstFile, "---------------". PHP_EOL);
            fclose($rstFile);
            $this->changeRechte();
            $this->makeHTML($data['pathDocu']);
        }
    return redirect($data['lastURL']);       
    }

    private function createSphinxDoc($path, $name, $authorName) {
        $shell_Befehl = "/var/www/sphinx/create ";
        $shell_Befehl.= sprintf(" %s", $path);    //Project name
        $shell_Befehl.= sprintf(" %s", $name);    //Project name
        $shell_Befehl.= sprintf(" %s", $authorName);     //Author name
        $shell_Befehl.= sprintf(" %s", "V1.0");          //Version of project

        $output = shell_exec("python " . $shell_Befehl);
        //dd($output);
    }

    private function changeRechte() {
        $output = shell_exec("sudo /var/www/sphinx/./rechte.sh");
        //dd($output);
    }

    private function changeValueInConf($olt, $new, $path) {
        $output = shell_exec("sudo perl -pi -e 's/$olt/$new/g' $path/source/conf.py");
        //dd($output);
    }

    private function makeHTML($path) {
        $output = shell_exec("sudo sphinx-build -b html $path/source/ $path/build/html");
        //dd($output);
    }

    private function makePDF($path) {
        $output = shell_exec("sphinx-build -b latex $path/source/ $path/build/latex");
        //dd($output);
    }

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

    public function readMardown($path) {
        $contents = file_get_contents($path);
        return $contents;
    }

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

    public function saveDocu() {
        $data = Input::all();

        $document = document::where("id", "=", $data['docuId'])->first();
        $path = $document->path . "/source/" . $data['name'] . ".rst";
        $rstFile = fopen($path, "w");
        fwrite($rstFile, $data['makedown']);
        fclose($rstFile);
        $this->makeHTML($document->path);
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
