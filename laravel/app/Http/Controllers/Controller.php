<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

    use DispatchesCommands,
        ValidatesRequests;

    var $privateDokus = [];
    var $publicDokus = [];

    public function __construct() {
        
    }

    private function loadPrivateNav($param) {
        array_push($this->privateDokus, [
            "ID" => 1,
            "Name" => "sipgate",
            "NameShow" => "SipGate"
        ]);
        array_push($this->privateDokus, [
            "ID" => 2,
            "Name" => "mummy",
            "NameShow" => "Mummy"
        ]);
        array_push($this->privateDokus, [
            "ID" => 3,
            "Name" => "laravel",
            "NameShow" => "Laravel"
        ]);
        array_push($this->privateDokus, [
            "ID" => 4,
            "Name" => "typo3",
            "NameShow" => "Typo3"
        ]);
    }

    private function loadPublicNav($param) {
        array_push($this->publicDokus, [
            "ID" => 1,
            "Name" => "sipgate",
            "NameShow" => "SipGate",
            "GroupName" => "entwicklung",
            "GroupNameShow" => "Entwicklung",
        ]);
        array_push($this->publicDokus, [
            "ID" => 2,
            "Name" => "mummy",
            "NameShow" => "Mummy",
            "GroupName" => "entwicklung",
            "GroupNameShow" => "Entwicklung",
        ]);
        array_push($this->publicDokus, [
            "ID" => 3,
            "Name" => "web",
            "NameShow" => "Web",
            "GroupName" => "entwicklung",
            "GroupNameShow" => "Entwicklung",
        ]);
        array_push($this->publicDokus, [
            "ID" => 4,
            "Name" => "laravel",
            "NameShow" => "Laravel",
            "GroupName" => "verwaltung",
            "GroupNameShow" => "Verwaltung",
        ]);
    }

    public function getPrivateNav() {
        $this->loadPrivateNav("");
        return $this->privateDokus;
    }

    public function getPublicNav() {
        $this->loadPublicNav("");
        return $this->publicDokus;
    }

}
