<?php

namespace App\Http\Controllers;

class HomeController extends Controller {
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
        return $this->param("");
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function param($parameter) {
        $view = view('home');      
        $view->p = $parameter;
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        return $view;
    }
    
    public function showAllNews() {
        $view = view('news.allNews'); 
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        $view->allNews = [];
        return $view;
    }
}
