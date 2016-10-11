<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class ActiveConnectionSummary extends Model {
    protected $table = 'radium_active_connection_summary';
    public $timestamps = false;

    public $fillable = [
        'connection_id',
        'username',
        'date',
        'download',
        'upload',
        'total'
    ];

    /**
     * Retrieves the combined session usage for culculating the total usage for the connection
     *
     * @param int $connectionID
     * @return mixed
     */
    public static function getConnectionUsage($connectionID = 0) {
        $columns = "connection_id"
            .',sum(download)'
            .',sum(upload)'
            .',sum(total)';

        return self::selectRaw($columns)
            ->where('connection_id', $connectionID)
            ->groupBy('connection_id')
            ->first();
    }

    /**
     * Retrieves the current connection to update the daily usage
     *
     * @param string $username
     * @param int $nasID
     * @param DateTime $date
     */
    public static function getCurrentConnection($username = "", $nasID = 0, DateTime $date) {
        return self::select("*")
            ->where('username', $username)
            ->where('nas_id', $nasID)
            ->where('date', $date)
            ->first();
    }
}