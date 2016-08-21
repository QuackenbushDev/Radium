<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RadiusReply extends Model {
    protected $table = 'radreply';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $perPage = 15;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
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

    /**
     * Returns a list of attributes for the given username
     *
     * @param string $username
     * @return mixed
     */
    public static function getUserAttributes($username) {
        return self::select(['id', 'attribute', 'op', 'value'])
            ->where('username', $username);
    }
}