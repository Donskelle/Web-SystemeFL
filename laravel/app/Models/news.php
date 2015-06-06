<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class news extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'document_id', 'group_id', 'mode', 'description'];

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
    public function user() {
        return $this->belongsTo('App\Models\user');
    }

    /**
     * Many Or One To One
     * @return type
     */
    public function document() {
        return $this->belongsTo('App\Models\document');
    }

    /**
     * Many Or One To One
     * @return type
     */
    public function group() {
        return $this->belongsTo('App\Models\group');
    }

}
