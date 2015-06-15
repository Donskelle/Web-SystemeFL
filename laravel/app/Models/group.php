<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class group extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'active'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * One To Many
     * @return type
     */
    public function users() {
        return $this->hasMany('App\Models\user_in_group');
    }

    /**
     * One To Many
     * @return type
     */
    public function documents() {
        return $this->hasMany('App\Models\document_in_group');
    }

}
