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
        'description',
        'nas_port'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Retrieves a nas record by IP address.
     *
     * @param string $ip
     * @return mixed
     */
    public static function findByNasIp($ip = '') {
        return self::where('nasname', $ip)->firstOrFail();
    }
}