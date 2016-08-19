<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class RadiusCheck extends Model {
    protected $table = 'radcheck';
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

    protected static $passwordAttributes = [
        'Cleartext-Password',
        'Auth-Type',
        'User-Password',
        'Crypt-Password',
        'MD5-Password',
        'SMD5-Password',
        'SHA-Password',
        'SSHA-Password',
        'NT-Password',
        'LM-Password',
        'SHA1-Password',
        'CHAP-Password',
        'NS-MTA-MD5-Password'
    ];

    /**
     * Retrieves a list of users available in the redis database
     *
     * @return mixed
     */
    public static function getUserList() {
        return self::selectRaw('radcheck.username, radcheck.value as password,radcheck.id,radusergroup.groupname as groupname, attribute, IF(disabled.username IS NOT NULL, 1, 0) as disabled')
            ->distinct('radcheck.username')
            ->whereIn('attribute', self::$passwordAttributes)
            ->leftJoin('radusergroup', 'radcheck.username', '=', 'radusergroup.username')
            ->leftJoin('radusergroup as disabled', function($join) {
                $join->on('disabled.username', '=', 'radcheck.username')
                    ->where('disabled.groupname', '=', config('radium.disabled_group'));
            })
            ->orderBy('radcheck.id', 'asc')
            ->groupBy('radcheck.username');
    }

    /**
     * Returns a list of attributes for the given username
     *
     * @param string $username
     * @return mixed
     */
    public static function getUserAttributes($username) {
        return self::select(['attribute', 'op', 'value'])
            ->where('username', $username)
            ->whereNotIn('attribute', self::$passwordAttributes);
    }
}