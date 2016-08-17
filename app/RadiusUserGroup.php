<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RadiusUserGroup extends Model {
    protected $table = 'radusergroup';
    protected $primaryKey = null;
    public $timestamps = false;
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'groupname',
        'priority'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Returns an array of groups the user belongs to.
     *
     * @param string $username
     * @return mixed
     */
    public static function getUserGroups($username) {
        $groups = self::select('groupname')
            ->where('username', $username)
            ->orderBy('priority', 'asc')
            ->get()
            ->toArray();

        return array_flatten($groups);
    }
}