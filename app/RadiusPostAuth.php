<?php namespace App;

use Illuminate\Database\Eloquent\Model;

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

    /**
     * Retrieves the latest login attempts for all users or for a specific user.
     *
     * @param int $limit
     * @param string $username
     * @return mixed
     */
    public static function getLatestAttempts($limit = 10, $username = null) {
        $query = self::limit($limit)
            ->orderBy('id', 'desc');

        if ($username !== null) {
            $query->where('username', $username);
        }

        return $query;
    }

    /**
     * Retrieves the numbner of connections for a given username between a given date range
     *
     * @param string $username
     * @param string $dateStart
     * @param string $dateStop
     * @return mixed
     */
    public static function getConnectionCount($username = null, $dateStart = null, $dateStop = null) {
        $query = self::selectRaw('COUNT(*) as connections');

        if ($username !== null) {
            $query->where('username', $username);
        }

        if ($dateStart !== null) {
            $query->where('authdate', '>=', $dateStart);
        }

        if ($dateStop !== null) {
            $query->where('authdate', '<=', $dateStop);
        }

        return $query->first();
    }
}