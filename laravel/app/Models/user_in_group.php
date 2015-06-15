<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_in_group extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_in_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'group_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Many To One 
     * @return type
     */
    public function user() {
        return $this->belongsTo('App\Models\user');
    }

    /**
     * Many To One 
     * @return type
     */
    public function group() {
        return $this->belongsTo('App\Models\group');
    }

}
