<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class AdminController extends Controller {

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
      return $this->showAdmin("");
    }
    public function showAdmin($username) {
        $user = \DB::table('users')->where('username', $username)->first();
        $view = view('auth.adminSettings');
        $view->privateDokus = $this->getPrivateNav();
        $view->publicDokus = $this->getPublicNav();
        $view->userShow = $user;
        return $view;
    }
}
