<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utils\DataHelper;
use DateTime;
use Exception;

class RadiusAccount extends Model {
    protected $table = 'radacct';
    protected $primaryKey = 'radacctid';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'acctsessionid',
        'acctuniqueid',
        'username',
        'groupname',
        'realm',
        'nasipaddress',
        'nasportid',
        'nasporttype',
        'acctstarttime',
        'acctupdatetime',
        'acctstoptime',
        'acctinterval',
        'acctsessiontime',
        'acctauthentic',
        'connectinfo_start',
        'connectinfo_stop',
        'acctinputoctets',
        'acctoutputoctets',
        'calledstationid',
        'callingstationid',
        'acctterminatecause',
        'servicetype',
        'framedprotocol',
        'framedipaddress',
        'processed',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Returns today's accounting records for processing
     *
     * @return mixed
     */
    public static function getUnprocessed() {
        $columns = 'radacctid'
            . ',username'
            . ',nasipaddress'
            . ',acctstarttime'
            . ',DAY(acctstarttime) as day'
            . ',MONTH(acctstarttime) as month'
            . ',YEAR(acctstarttime) as year'
            . ',acctstoptime'
            . ',count(acctsessionid) as connection_count'
            . ',SUM(acctoutputoctets) as download'
            . ',SUM(acctinputoctets) as upload'
            . ',SUM(acctinputoctets + acctoutputoctets) as total'
            . ',nas.id as nas_id';

        return self::selectRaw($columns)
            ->leftJoin('nas', 'nasname', '=', 'nasipaddress')
            ->where('processed', false)
            ->whereRaw('acctstoptime IS NOT NULL')
            ->groupBy('username', 'nasipaddress', 'day', 'month', 'year')
            ->get();
    }

    public static function getOpenConnections() {
        $columns = 'radacctid'
            . ',username'
            . ',nasipaddress'
            . ',acctstarttime'
            . ',DAY(acctstarttime) as day'
            . ',MONTH(acctstarttime) as month'
            . ',YEAR(acctstarttime) as year'
            . ',acctstoptime'
            . ',count(acctsessionid) as connection_count'
            . ',SUM(acctoutputoctets) as download'
            . ',SUM(acctinputoctets) as upload'
            . ',SUM(acctinputoctets + acctoutputoctets) as total'
            . ',nas.id as nas_id';

        return self::selectRaw($columns)
            ->leftJoin('nas', 'nasname', '=', 'nasipaddress')
            ->whereRaw('acctstoptime IS NULL')
            ->groupBy('username', 'nasipaddress', 'day', 'month', 'year')
            ->get();
    }
}