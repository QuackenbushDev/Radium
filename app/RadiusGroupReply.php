<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RadiusGroupReply extends Model {
    protected $table = 'radgroupreply';
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