<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NavController
 *
 * @author Peter
 */
class NavController {

    public $privateDokus = [];

    public function __construct() {
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

}
