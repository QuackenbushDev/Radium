<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RadiusAccountInfo extends Model {
    protected $table = 'radius_account_info';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $perPage = 15;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'company',
        'home_phone',
        'mobile_phone',
        'office_phone',
        'address',
        'notes',
        'enable_portal',
        'enable_password_resets',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
}