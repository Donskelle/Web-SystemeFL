<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class document extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'path', 'user_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    
     /**
     * One To One 
     * @return type
     */
    public function group() {
        return $this->hasOne('App\Models\document_in_group');
    }

}
