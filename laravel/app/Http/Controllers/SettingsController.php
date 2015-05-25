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
use App\Models\document_in_group;

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
        $allDocuments = document::all();
        $view = view('settings.adminSettings');
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        $view->allUsers = $allUsers;
        $view->allGroups = $allGroups;
        $view->allDocuments = $allDocuments;
        return $view;
    }

    public function showDocument($document_id) {
        $document = document::where('id', '=', $document_id)->first();
        $allGroups = group::all();
        $view = view('settings.documentSettings');
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        $view->document = $document;
        $view->allGroups = $allGroups;
        return $view;
    }

    public function saveDocument($document_id) {
        $data = Input::all();     
        //Dokument Einstellungen
        $document = document::where('id', '=', $document_id)->first();
        
       
        $this->changeValueInConf($document->name,$data['name'],$document->path);
        $document->name = $data['name'];
        $document->path = $data['path'];
        $this->changeValueInConf($document->layout,$data['layout'],$document->path);
        $document->layout = $data['layout'];
        $document->save();

        //Dokument in Gruppen
        document_in_group::where('document_id', '=', $document_id)->delete();
        $document_in_group = new document_in_group;
        $document_in_group->document_id = $document_id;
        $document_in_group->group_id = $data['group_id'];        
        $document_in_group->save();
        return redirect($data['lastURL']);        
    }
    private function changeValueInConf($olt, $new, $path) {
        $output = shell_exec("sudo perl -pi -e 's/$olt/$new/g' $path/source/conf.py");
        //dd($output);
    }
    public function showGroup($group_id) {
        $group = group::find($group_id);
        $allDocuments = document::all();
        $allUsers = user::all();
        $view = view('settings.groupSettings');
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        $view->group = $group;
        $view->allDocuments = $allDocuments;
        $view->allUsers = $allUsers;
        return $view;
    }

    public function saveGroup($group_id) {
        $data = Input::all();
        //Gruppen Einstellungen
        $group = group::where('id', '=', $group_id)->first();
        $group->name = $data['name'];
        $group->description = $data['description'];
        if (array_key_exists("active", $data)) {
            $group->active = $data['active'];
        }
        $group->save();

        //Benutzer in Gruppen
        user_in_group::where('group_id', '=', $group_id)->delete();
        $userID = 0;
        foreach ($data as $key => $value) {
            if (strpos($key, 'userID') !== false) {
                $userID = $value;
            }
            if (strpos($key, 'usercheckbox') !== false) {
                $user_in_group = new user_in_group;
                $user_in_group->user_id = $userID;
                $user_in_group->group_id = $group_id;
                $user_in_group->save();
            }
        }

        //Dokumente in Gruppen
        document_in_group::where('group_id', '=', $group_id)->delete();
        $documentID = 0;
        foreach ($data as $key => $value) {
            if (strpos($key, 'documentID') !== false) {
                $documentID = $value;
            }
            if (strpos($key, 'documentcheckbox') !== false) {
                document_in_group::where('document_id', '=', $documentID)->delete();
                $document_in_group = new document_in_group;
                $document_in_group->document_id = $documentID;
                $document_in_group->group_id = $group_id;
                $document_in_group->save();
            }
        }
        return $this->showAdminSettings();
    }

    public function addNewGroup() {
        $data = Input::all();
        $validator = Validator::make($data, ['name' => 'required|min:4|max:255|unique:groups',]);
        if ($validator->fails()) {
            return $this->showAdminSettings();
        } else {
            $newGroup = group::create([
                        'name' => $data['name'],
                        'description' => "Gruppe hat noch keine Beschreibung",
                        'active' => 1
            ]);
            return $this->showGroup($newGroup->id);
        }
    }

    public function showProfil($username) {
        $user = \DB::table('users')->where('username', $username)->first();
        $view = view('settings.profileSettings');
        $view->privateDocus = $this->getAuthDocuments();
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
        
        
        $user = user::where('username', '=', $username)->first();         
        $user->name = $data['name'];
        $user->extra = $data['extra'];    
        $user->browser_layout = $data['browser_layout'];
        $user->editor_layout = $data['editor_layout'];
        if (array_key_exists("permission", $data)) {
             $user->permission = $data["permission"];
        }
        if (array_key_exists("active", $data)) {

            $user->active = $data["active"];
        }
        if ($data["password"] !== "") {
            $user->password = bcrypt($data["password"]);
        }
          $user->save();
       
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
