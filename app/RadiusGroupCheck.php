<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RadiusGroupCheck extends Model {
    protected $table = 'radgroupcheck';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'groupname',
        'attribute',
        'op',
        'value'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}