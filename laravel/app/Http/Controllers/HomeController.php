<?php

namespace App\Http\Controllers;

class HomeController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Home Controller
      |--------------------------------------------------------------------------
      |
      | Benihalte die Startseite 
      | Eine übersicht der Letzten änderung für den Admin
      | und eine Help Seite mit Tips und Tricks
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
     * Gibt die Startseite zurück 
     * auf der eine kleine Einleitung verfasst ist
     *
     * @return Response
     */
    public function index() {      
        $view = view('home');     
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        return $view;
    }

    /**
     * Gibt Die Admin Seite für die Übersicht der Änderung zurück
     * @return type
     */
    public function showNews() {

        $view = view('news.allNews');
        $view->Titel = "Neues Dokument";
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        $view->allNews = $this->getAllNews();
        return $view;
    }

    /**
     * Gibt die Help Seite zurück in der Wichtige Tips stehen
     * @return type
     */
    public function showHelp() {
        $view = view('help');
        $view->Titel = "Neues Dokument";
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        return $view;
    }

}
