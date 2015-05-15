<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class document_in_group extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'document_in_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['document_id', 'group_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Many Or One To One
     * @return type
     */
    public function group() {
        return $this->belongsTo('App\Models\group');
    }
    
     /**
     * Many Or One To One 
     * @return type
     */
    public function document() {
        return $this->belongsTo('App\Models\document');
    }
}
