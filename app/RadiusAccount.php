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
     * Returns a list of unprocessed closed connections for processing.
     *
     * @return mixed
     */
    public static function getUnprocessed() {
        $columns = 'radacctid'
            . ',username'
            . ',acctstarttime'
            . ',acctstoptime'
            . ',acctoutputoctets as download'
            . ',acctinputoctets as upload'
            . ',(acctinputoctets + acctoutputoctets) as total'
            . ',nas.id as nas_id';

        return self::selectRaw($columns)
            ->leftJoin('nas', 'nasname', '=', 'nasipaddress')
            ->where('processed', false)
            ->whereRaw('acctstoptime IS NOT NULL')
            ->get();
    }

    /**
     * Retrieve a list of connections that are still open. An open connection cannot
     * be processed therefore is not checked for the processed flag.
     *
     * @return mixed
     */
    public static function getOpenConnections() {
        $columns = 'radacctid'
            . ',username'
            . ',acctstarttime'
            . ',acctoutputoctets as download'
            . ',acctinputoctets as upload'
            . ',(acctinputoctets + acctoutputoctets) as total'
            . ',nas.id as nas_id';

        return self::selectRaw($columns)
            ->leftJoin('nas', 'nasname', '=', 'nasipaddress')
            ->whereRaw('acctstoptime IS NULL')
            ->get();
    }

    /**
     * Returns a boolean with the users online status
     *
     * @param $username
     * @return bool
     */
    public static function onlineStatus($username) {
        $query = self::select('username')
            ->where('AcctStopTime', 'IS', 'NULL')
            ->where('Username', $username)
            ->orWhere('AcctStopTime', '0000-00-00 00:00:00')
            ->where('username', $username);
        return ($query->count() > 0) ? true : false;
    }

    /**
     * returns a list of online users.
     *
     * @return mixed
     */
    public static function onlineUsers() {
        $sql = 'radacctid, username, framedipaddress, nasipaddress, sum(acctsessiontime), count(acctstarttime) as connections, DAY(acctstarttime) AS day, MONTH(acctstarttime) AS month, YEAR(acctstarttime) AS year, sum(acctinputoctets) AS acctinputoctets, sum(acctoutputoctets) AS acctoutputoctets, sum(acctinputoctets + acctoutputoctets) AS total';
        $query = self::selectRaw($sql)
            ->whereRaw('AcctStopTime IS NULL')
            ->orWhere('AcctStopTime', '0000-00-00 00:00:00')
            ->groupBy('username', 'acctstarttime');
        return $query;
    }
}