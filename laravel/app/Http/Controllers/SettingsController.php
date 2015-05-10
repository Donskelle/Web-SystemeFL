<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use App\Models\document;
use App\Models\group;
use App\Models\user;
use App\Models\user_in_group;

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

        $allUsers = user::all();
        $allGroups = group::all();
//        dd( $allUsers[1]->documents);
//         
//               
//      dd(group::find(1),group::find(1)->users()->get(),group::find(1)->users()->find(1)->user);
//        dd(user::find(2)->groups);
//        dd(document::find(1)->group()->get() , document::find(1)->group);
//        
//        
//        $allUser = \DB::table('users')->get();   
//        
//        $users = user::all();
//        $user = user::find(1);
//         //dd($allUser, $users[0], $user->name);
//        
//          $allUser = user_in_group::find(2)->user;
//       
//        $allUser = user::find(2)->groups;
//        
//        $allUser = user_in_group::has('user')->get();
//         dd($allUser);
//         $allUser = group::has('users')->get();
//         dd($allUser);
//         
//        $allGroups = \DB::table('user_in_group')->select('group_id', \DB::raw('count(user_id) as total_user'))->groupBy('group_id')->get();
//        
//        $allGroups = \DB::table('document_in_group')->select('group_id', \DB::raw('count(document_id) as total_docu'))->groupBy('group_id')->get();
//       
//        $allGroups = \DB::table('groups')
//                ->select('groups.id', 'name', 'description','active')
//                ->leftJoin(\DB::table('user_in_group')->select('group_id', \DB::raw('count(user_id) as total_user'))->groupBy('group_id')->get(), 'group_id', '=', 'groups.id')
//                ->leftJoin(\DB::table('document_in_group')->select('group_id', \DB::raw('count(document_id) as total_docu'))->groupBy('group_id')->get(), 'group_id', '=', 'groups.id')
//                ->groupBy('groups.id')
//                ->get();
//
//        dd($allGroups);
        $view = view('settings.adminSettings');
        $view->privateDokus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        $view->allUsers = $allUsers;
        $view->allGroups = $allGroups;
        return $view;
    }

    public function showGroup($group_id) {

        $group = group::find($group_id);
        $allDocuments = document::all();
        $allUsers = user::all();
        $view = view('settings.groupSettings');
        $view->privateDokus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        $view->group = $group;
        $view->allDocuments = $allDocuments;
        $view->allUsers = $allUsers;
    }

    public function showProfil($username) {
        $user = \DB::table('users')->where('username', $username)->first();
        $view = view('settings.profileSettings');
        $view->privateDokus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
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
