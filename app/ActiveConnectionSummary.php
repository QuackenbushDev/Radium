<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class ActiveConnectionSummary extends Model {
    protected $table = 'radium_active_connection_summary';
    public $timestamps = false;

    public $fillable = [
        'connection_id',
        'nas_id',
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
            .',sum(download) as download'
            .',sum(upload) as upload'
            .',sum(total) as total';

        return self::selectRaw($columns)
            ->where('connection_id', $connectionID)
            ->groupBy('connection_id')
            ->first();
    }

    /**
     * Retrieves the current connection to update the daily usage
     *
     * @param int $connectionID
     * @param DateTime $date
     */
    public static function getCurrentConnection($connectionID, DateTime $date) {
        return self::select("*")
            ->where('connection_id', $connectionID)
            ->where('date', $date->format('Y-m-d'))
            ->first();
    }
}