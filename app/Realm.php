<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Realm extends Model {
    protected $table = 'radium_realm';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'authhost',
        'accthost',
        'secret',
        'ldflag',
        'nostrip',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [''];
}