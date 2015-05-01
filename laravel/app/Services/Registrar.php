<?php

namespace App\Services;

use App\user;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data) {
        return Validator::make($data, [
                    'username' => 'required|max:255',
                    'name' => 'required|max:255',                  
                    'password' => 'required|confirmed|min:4',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data) {
        return user::create([
                    'username' => $data['username'],
                    'name' => $data['name'],                   
                    'password' => bcrypt($data['password']),
        ]);
    }

}
