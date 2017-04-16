<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $table = 'plan';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'limit',
        'throttle_on_limit',
        'throttle_speed_on_limit',
        'disable_on_limit',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function users() {
        return $this->hasMany('User');
    }
}