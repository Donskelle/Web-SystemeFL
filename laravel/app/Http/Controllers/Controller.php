<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

    use DispatchesCommands,
        ValidatesRequests;

    public function __construct() {
        
    }
    
    /**
     * Hilfsfunktionen für die leichtere benutzung der Datenübertragung
     * @return type
     */

    public function getAuthDocuments() {
        return \Auth::user()->documents;
    }

    public function getAuthGroups() {
        return \Auth::user()->groups;
    }

    public function getAllUsers() {
        return \App\Models\user::all();
    }

    public function getAllGroups() {
        return \App\Models\group::all();
    }

    public function getAllDocuments() {
        return \App\Models\document::all();
    }

    public function getAllNews() {
        return \App\Models\news::all();
    }

}
