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
     * Gibt die User Einstellungs Seite zurück
     *
     * @return Response
     */
    public function index() {
        return $this->showProfil("");
    }

    /**
     * Gibt die Einstellungs Seite für alle bereiche für Admins zurück
     * @return type
     */
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

    /**
     * Gibt die entsprechende Dokumenten Einstellungs Seite zurück
     * Das anzuzeigende Dokument wird mittels der ID unterschieden die im Header stehet
     * 
     * @param type $document_id
     * @return type
     */
    public function showDocument($document_id) {
        $document = document::where('id', '=', $document_id)->first();
        $allGroups = group::all();
        $view = view('settings.documentSettings');
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        $view->document = $document;
        $view->allGroups = $allGroups;
        $view->images = $this->countImage($document->path);
        return $view;
    }

    /**
     * Speicheret die Äanderungen an dem enstprechendem Dokument
     * Dazu gehören
     * Name 
     * path
     * Layout
     * Gruppe 
     * 
     * @param type $document_id
     * @return type
     */
    public function saveDocument($document_id) {
        $data = Input::all();
        //Dokument Einstellungen
        $document = document::where('id', '=', $document_id)->first();
        $this->changeValueInConf($document->name, $data['name'], $document->path);
        $document->name = $data['name'];
        $document->path = $data['path'];
        $this->changeValueInConf($document->layout, $data['layout'], $document->path);
        $document->layout = $data['layout'];
        $document->save();

        //Dokument in Gruppen
        document_in_group::where('document_id', '=', $document_id)->delete();
        $document_in_group = new document_in_group;
        $document_in_group->document_id = $document_id;
        $document_in_group->group_id = $data['group_id'];
        $document_in_group->save();
        $this->addNewNews(0, 0, 2, "Benutzer Einstellungen angepasst bei " . $data['name']);
        return redirect($data['lastURL']);
    }

    /**
     * Austauschen der Variablen der Config mittels fester Definitionen
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
     * Gibt die entsprechende Gruppen Einstellung zurück
     * Welche Dokumente in der Gruppe sind 
     * und welche Nutzer Rechte haben, in der Gruppe zu berabeiten.
     * @param type $group_id
     * @return type
     */
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

    /**
     * Speichert die änderungen in der Gruppe
     * Beim Speicheren wird die zugehörigkeit der Dokumnte und Nutzer etsprechend angepasst.
     * 
     * Ein Nutzer darf in einer oder mehrerin Gruppen sein 
     * ein Dokument kann nur in einer Gruppe sein.
     * 
     * @param type $group_id
     * @return type
     */
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
        $this->addNewNews(0, $group_id, 2, "Änderung an der Gruppe Eisntellung");
        return $this->showAdminSettings();
    }

    /**
     * Anlegen einer neuen Gruppe 
     * die anschliessen weiter bearbeitet werden kann.
     * @return type
     */
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
            $this->addNewNews(0, $newGroup->id, 1, "Neue Gruppe Hinzugefügt");
            return $this->showGroup($newGroup->id);
        }
    }

    /**
     * Gibt ein entsprechendes Profil aus dem Admin Einstellungs bereich zurück
     * welches der Admin bearbeiten kann.
     * @param type $username
     * @return type
     */
    public function showProfil($username) {
        $user = \DB::table('users')->where('username', $username)->first();
        $view = view('settings.profileSettings');
        $view->privateDocus = $this->getAuthDocuments();
        $view->publicGroups = $this->getAuthGroups();
        $view->userShow = $user;
        return $view;
    }

    /**
     * Speichert die Änderungen am enstprechendem User Profil und kert zur Admin übersicht zurück
     * 
     * @param type $username
     * @return type
     */
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
        if (array_key_exists("browser_layout", $data)) {
            $user->browser_layout = $data['browser_layout'];
        }
        if (array_key_exists("editor_layout", $data)) {
            $user->editor_layout = $data['editor_layout'];
        }
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
        $this->addNewNews(0, 0, 2, "Benutzer Einstellungen angepasst bei " . $data['name']);
        if (\Auth::user()->username === $username) {
            return redirect('/');
        } else {
            return redirect('/settings/admin');
        }
    }

    /**
     * Läd ein ausgewähltes Bild auf den Server
     * Das Bild wird als Profile Bild genutzt des entsprechenden Users
     * 
     * @param type $username
     * @return type
     */
    public function fileupload($username) {
        Input::file('file')->move('/var/www/laravel/public/img/', $username . '.png');
        $update = [];
        $update["imagePath"] = $username . '.png';
        \DB::table('users')->where('username', $username)->update($update);
        $this->changeRechte();
        return redirect('settings/profile/' . $username);
    }

    /**
     * Läd ein ausgewähltes Bild auf den Server
     * Das Bild wird im Bilder ordner des Dokuments gespeichert
     * 
     * @param type $documentId
     * @return type
     */
    public function fileuploadDocument($documentId) {
        $document = document::where('id', '=', $documentId)->first();
        $images = $this->countImage($document->path);
        Input::file('file')->move($document->path . '/source/_templates/', (count($images) + 1) . '.png');
        $this->changeRechte();
        return $this->showDocument($documentId);
    }

    /**
     * Zählt alle Bilder zusammenn
     * @param type $docuPath
     * @return array
     */
    private function countImage($docuPath) {
        $images = [];
        $weeds = array('.', '..');
        $directori = array_diff(scandir($docuPath . "/source/_templates/"), $weeds);
        foreach ($directori as $value) {
            if (pathinfo($value, PATHINFO_EXTENSION) == "png") {
                array_push($images, $value);
            }
        }
        return $images;
    }

    /**
     * Rechte auf dem Server neu setzten für eine Fest gesetzte Gruppe www 
     * und den beinhalteten Nutzeren pharao und www-data     
     */
    private function changeRechte() {
        $output = shell_exec("sudo /var/www/sphinx/./rechte.sh");
        //dd($output);
    }

}
