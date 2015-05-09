<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

class SettingsController extends Controller {

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
        return $this->showProfil("");
    }
    
     public function showAdminSettings() {
        $allUser = \DB::table('users')->get(); 
        $view = view('settings.adminSettings');
        $view->privateDokus = $this->getPrivateNav();
        $view->publicDokus = $this->getPublicNav();
        $view->allUser = $allUser;
        return $view;
    }

    public function showProfil($username) {
        $user = \DB::table('users')->where('username', $username)->first();
        $view = view('settings.profileSettings');
        $view->privateDokus = $this->getPrivateNav();
        $view->publicDokus = $this->getPublicNav();
        $view->userShow = $user;
        return $view;
    }

    public function saveProfil($username) {
        $data = Input::all();
        if ($data["password"] === "" && $data["password_confirmation"] === "") {
            $validator = Validator::make($data, [
                        'name' => 'required|max:255',
                        'extra' => 'required|max:255',
            ]);
        } else {
            $validator = Validator::make($data, [
                        'name' => 'required|max:255',
                        'extra' => 'required|max:255',
                        'password' => 'required|confirmed|min:4',
            ]);
        }
        if ($validator->fails()) {
            return redirect('settings/profile/' . $username)->withErrors($validator)->withInput();
        }


//        if(file_exists("/var/www/laravel/public/img/".$data['imagePath']));
//            return redirect('auth/profileSettings')->withInput($data->only('name','imagePath','extra','permission','active'))
//                        ->withErrors([
//                            'imagePath' => 'Es ist kein Bild vorhanden.',]);
        $update = [];
        $update["name"] = $data["name"];       
        $update["extra"] = $data["extra"];
        if (array_key_exists("permission", $data)) {
            $update["permission"] = $data["permission"];
        }     
        if (array_key_exists("active", $data)) {
            
            $update["active"] = $data["active"];
        }
        if ($data["password"] !== "") {
            $update["password"] = bcrypt($data["password"]);
        }
        \DB::table('users')->where('username', $username)->update($update);
        if (\Auth::user()->username === $username) {
            return redirect('/');
        } else {
            return redirect('/settings/admin');
        }
    }

    public function fileupload($username) { 
        Input::file('file')->move('/var/www/laravel/public/img/', $username . '.png');
        $update = [];
        $update["imagePath"] = $username . '.png';
        \DB::table('users')->where('username', $username)->update($update);
        return redirect('settings/profile/' . $username);
    }
}
