<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BandwidthSummary extends Model {
    protected $table = 'radium_bandwidth_summary';
    public $timestamps = false;

    public $fillable = [
        'username',
        'date',
        'download',
        'upload',
        'total'
    ];

}