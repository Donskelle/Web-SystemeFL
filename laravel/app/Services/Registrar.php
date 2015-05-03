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
                    'username' => 'required|max:255|unique:users',
                    'name' => 'required|max:255',
                    'extra' => 'required|max:255',
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
                    'extra' => $data['extra'],
                    'password' => bcrypt($data['password']),
                    'active' => 1,
                    'permission' => 2, // 0 = Admin , 1 = UserAdmin, 2 = User
                    'imagePath' => $this->randImage()]);
    }

    private function randImage() {
        switch (rand(0, 2)) {
            case 0: return "default0.png";
            case 1: return "default1.png";
            case 2: return "default2.png";
            default :
                return "default.png";
        }
    }   

}
