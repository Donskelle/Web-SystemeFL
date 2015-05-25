<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class user extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'name', 'password', 'imagePath', 'extra','browser_layout', 'editor_layout', 'permission', 'active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * One To Many
     * @return type
     */
    public function groups() {
        return $this->hasMany('App\Models\user_in_group');
    }
    
    /**
     * One To Many
     * @return type
     */
    public function documents() {
        return $this->hasMany('App\Models\document');
    }
    
    /**
     * One To Many
     * @return type
     */
    public function activeDocuments() {
        return $this->hasMany('App\Models\active_document_part');
    }
    
      public function groupWhere($group_id) {
          return   $this->hasMany('App\Models\user_in_group')->where('group_id', '=', '1');
      }

}
