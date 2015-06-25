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
        return \App\Models\news::orderBy('id', 'DESC')->take(30)->get();
    }

    
    /**
     * 
     * Die Mode Nummeren
     * 1    Neue Benutzer Gruppen Projekte Dokumente
     * 2    Bearbeiten der Benutzer Gruppen Projekte
     * 3    Bearbeiten der Dokumente
     * 
     * 
     * @param type $user_id
     * @param type $document_id
     * @param type $group_id
     * @param type $mode
     * @param type $description
     */
    public function addNewNews( $document_id, $group_id, $mode, $description) {
        $newNews = \App\Models\news::create([
                    'user_id' => \Auth::user()->id,
                    'document_id' => $document_id,
                    'group_id' => $group_id,
                    'mode' => $mode,
                    'description' => $description
        ]);
        
    }

}
