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
        $this->middleware('guest');
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

        return $view;
    }

    public function param3($access, $group, $doku) {
        $x = Input::get("x");
        $view = $this->getView($access);
        $view->Titel = "sipgate";
        $view->DokuAccess = $access;
        $view->DokuGroupAktive = $group;
        $view->DokuAktive = $doku;
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
}
