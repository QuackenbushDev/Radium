<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Proxy extends Model {
    protected $table = 'radium_proxy';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'synchronous',
        'retry_delay',
        'retry_count',
        'dead_time',
        'default_fallback',
        'post_proxy_authorize',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [''];
}