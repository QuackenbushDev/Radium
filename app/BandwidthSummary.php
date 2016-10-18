<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BandwidthSummary extends Model {
    protected $table = 'radium_bandwidth_summary';
    public $timestamps = false;

    public $fillable = [
        'username',
        'nas_id',
        'date',
        'download',
        'upload',
        'total'
    ];

    public static function getConnectionForDate($username, $nasID, Carbon $date) {
        return self::select('*')
            ->where('username', $username)
            ->where('nas_id', $nasID)
            ->where('date', $date->toDateString())
            ->first();
    }
}