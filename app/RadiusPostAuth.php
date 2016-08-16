<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class RadiusPostAuth extends Model
{
    protected $table = 'radpostauth';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'pass',
        'reply',
        'authdate'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['pass'];

    public static function getLatestAttempts($limit = 10, $username = null) {
        $query = self::limit($limit)
            ->orderBy('id', 'desc');

        if ($username !== null) {
            $query->where('username', $username);
        }

        return $query;
    }
}