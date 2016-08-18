<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Nas extends Model
{
    protected $table = 'nas';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nasname',
        'shortname',
        'type',
        'ports',
        'secret',
        'server',
        'community',
        'description'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}