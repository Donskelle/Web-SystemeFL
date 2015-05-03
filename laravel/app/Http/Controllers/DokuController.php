<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

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
    public function param1($access) {
        $view = $this->getView($access);
        $view->DokuAccess = $access;
        $view->Titel = "sipgate";
         $view->privateDokus = $this->getPrivateNav();
        $view->publicDokus = $this->getPublicNav();
        return $view;
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function param2($access, $doku) {
        $x = Input::get("x");
        $view = $this->getView($access);
        $view->Titel = "sipgate";
        $view->DokuAccess = $access;
        $view->DokuAktive = $doku;
        $view->Dokument = $this->getDokument($doku);
         $view->privateDokus = $this->getPrivateNav();
        $view->publicDokus = $this->getPublicNav();
        return $view;
    }

    public function param3($access, $group, $doku) {
        $x = Input::get("x");
        $view = $this->getView($access);
        $view->Titel = "sipgate";
        $view->DokuAccess = $access;
        $view->DokuGroupAktive = $group;
        $view->DokuAktive = $doku;
        $view->Dokument = $this->getDokument($doku);
         $view->privateDokus = $this->getPrivateNav();
        $view->publicDokus = $this->getPublicNav();
        return $view;
    }

    private function getView($access) {
        if ($access == "private") {
            return view('dokuViews.privateDoku');
        } elseif ($access == "public") {
            return view('dokuViews.publicDoku');
        } elseif ($access == "new") {
            return view('dokuViews.newDoku');
        } else {
            return view('home');
        }
    }

    public function getDokument($doku) {
        $sphinxPath = "/var/www/sphinx/";
        $dokuPath = $sphinxPath . "Nagios jb";
        $Dokument = [];
        $countID = 0;
        if (file_exists($dokuPath . "/source/index.rst")) {

            array_push($Dokument, [
                'id' => "editor" . $countID++,
                'html' => $this->readHtml($dokuPath . "/build/html/index.html"),
                'mardown' => $this->readMardown($dokuPath . "/source/index.rst")
            ]);
            $allRst = [];
            $weeds = array('.', '..');
            $directori = array_diff(scandir($dokuPath . "/source/"), $weeds);

            foreach ($directori as $value) {
                if (pathinfo($value, PATHINFO_EXTENSION) == "rst")
                    array_push($allRst, [
                        'name' => pathinfo($value, PATHINFO_FILENAME),
                        'add' => FALSE
                    ]);
            }
            $index = file_get_contents($dokuPath . "/source/index.rst");
            $pos = strpos($index, ".. toctree::");
            $index = substr($index, $pos); // gibt toctree zurück 


            $index = str_replace("   ", "@part@", $index);
            $schluesselwoerter = preg_split("/[\s,]+/", $index);
            foreach ($schluesselwoerter as $key => $value) {
                if (strpos($value, '@part@') !== FALSE) {
                    $value = str_replace("@part@", "", $value);
                    if (file_exists($dokuPath . "/source/" . $value . ".rst")) {
                        foreach ($allRst as $key => $value2) {
                            if ($value2['name'] == $value) {
                                $allRst[$key]['add'] = TRUE;
                            }
                        }
                        array_push($Dokument, [
                            'id' => "editor" . $countID++,
                            'html' => $this->readHtml($dokuPath . "/build/html/" . $value . ".html"),
                            'mardown' => $this->readMardown($dokuPath . "/source/" . $value . ".rst")
                        ]);
                    }
                }
            }
            foreach ($allRst as $key => $value) {
                if ($value['add'] === FALSE) {
                    $this->debug_to_console($value2['add']);
                    array_push($Dokument, [
                        'id' => "editor" . $countID++,
                        'html' => $this->readHtml($dokuPath . "/build/html/" . $value['name'] . ".html"),
                        'mardown' => $this->readMardown($dokuPath . "/source/" . $value['name'] . ".rst")
                    ]);
                }
            }
        }
        return $Dokument;
    }

    function debug_to_console($data) {

        if (is_array($data))
            $output = "<script>console.log( 'Debug Objects: " . implode(',', $data) . "' );</script>";
        else
            $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

        echo $output;
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

        return str_replace($replaceString, "", $htmlString);
    }

}
