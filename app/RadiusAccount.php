<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utils\DataHelper;
use Symfony\Component\VarDumper\Cloner\Data;

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
        'acctstoptime',
        'acctsessiontime',
        'acctauthentic',
        'connectioninfo_start',
        'connectioninfo_stop',
        'acctinputoctets',
        'acctoutputoctets',
        'calledstationid',
        'callingstationid',
        'acctterminatecause',
        'servicetype',
        'framedprotocol',
        'framedipaddress',
        'acctstodelay',
        'xascendsessionsvrkey',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * retrieves the connections for a specific time period (day, month, year)
     * and optionally limits it to a specific user.
     *
     * @param string $timePeriod
     * @param string $timeValue
     * @param bool $groupByUsername
     * @param string $username
     * @return mixed
     */
    public static function getConnections($timePeriod = 'day', $timeValue = null, $groupByUsername = false, $username = null) {
        $sql = "username, count(acctstarttime) as connections, DAY(acctstarttime) AS day, MONTH(acctstarttime) AS month, YEAR(acctstarttime) AS year, sum(acctinputoctets) AS acctinputoctets, sum(acctoutputoctets) AS acctoutputoctets, sum(acctinputoctets + acctoutputoctets) AS total";
        $query = self::selectRaw($sql);

        if ($timeValue !== null) {
            $query->having($timePeriod, '=', $timeValue);
        }

        if ($username !== null) {
            $query->where('username', $username)
                ->groupBy($timePeriod);
        } else {
            if ($groupByUsername) {
                $query->groupBy($timePeriod, 'username');
            } else {
                $query->groupBy($timePeriod);
            }
        }

        return $query;
    }

    /**
     * calculates the number of connections and returns it in an array.
     *
     * @param $timeSpan
     * @param $timeValue
     * @param $username
     * @param $nasIP
     * @return array
     */
    public static function connectionCountSummary($timeSpan, $timeValue, $username, $nasIP) {
        $sql = 'count(acctstarttime) as connections, DAY(acctstarttime) AS day, MONTH(acctstarttime) AS month, YEAR(acctstarttime) AS year';
        $query = self::selectRaw($sql)
            ->groupBy($timeSpan);

        if ($username !== null) {
            $query->where('username', $username);
        }

        if ($nasIP !== null) {
            $query->where('nasipaddress', $nasIP);
        }

        switch($timeSpan) {
            case "year":
                $count = 0;
                break;

            default:
            case "month":
                $count = 12;
                break;

            case "day":
                $count = cal_days_in_month(CAL_GREGORIAN, $timeValue, date('Y'));
                break;
        }

        $results = $query->get();
        $data = array_pad([], $count, 0);

        foreach ($results as $result) {
            if ($timeSpan === 'year') {
                $data[] += $result->connections;
            } else {
                $index = $result->$timeSpan - 1;
                $data[$index] += (int) $result->connections;
            }
        }

        return $data;
    }

    /**
     * Retrieves the bandwidth summary for a username and/or nas.
     * Results are normalized in binary GiB format to keep everything consistent.
     *
     * @param $timeSpan
     * @param null $timeValue
     * @param null $username
     * @param null $nasIP
     * @return array
     */
    public static function bandwidthUsage($timeSpan, $timeValue = null, $username = null, $nasIP = null) {
        $sql = "DAY(acctstarttime) AS day, MONTH(acctstarttime) AS month, YEAR(acctstarttime) AS year, sum(acctinputoctets) as upload, sum(acctoutputoctets) as download";
        $query = self::selectRaw($sql);

        if ($username !== null) $query->where('username', $username);
        if ($nasIP !== null) $query->where('nasipaddress', $nasIP);

        switch($timeSpan) {
            case "year":
                $query->orderBy('year', 'asc');
                $count = 0;
                break;

            default:
            case "month":
                $query->having('year', '=', $timeValue)
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $count = 12;
                break;

            case "day":
                $query->having('month', '=', $timeValue)
                    ->groupBy('day')
                    ->orderBy('day', 'asc');
                $count = cal_days_in_month(CAL_GREGORIAN, $timeValue, date('Y'));
                break;
        }

        $results = $query->get();

        $usage = [
            'download' => array_pad([], $count, 0),
            'upload'   => array_pad([], $count, 0)
        ];

        foreach ($results as $result) {
            if ($timeSpan === 'year') {
                $usage['download'][] += (float) DataHelper::convertToHumanReadableSize($result->download, 2, 'binary', 3, false);
                $usage['upload'][] += (float) DataHelper::convertToHumanReadableSize($result->upload, 2, 'binary', 3, false);
            } else {
                $index = $result->$timeSpan - 1;
                $usage['download'][$index] += (float) DataHelper::convertToHumanReadableSize($result->download, 2, 'binary', 3, false);
                $usage['upload'][$index] += (float) DataHelper::convertToHumanReadableSize($result->upload, 2, 'binary', 3, false);
            }
        }

        return $usage;
    }

    /**
     * Returns a boolean with the users online status
     *
     * @param $username
     * @return bool
     */
    public static function onlineStatus($username) {
        $query = self::select('username')
            ->whereRaw('acctstoptime IS NULL')
            ->where('username', $username)
            ->orWhere('acctstoptime', '0000-00-00 00:00:00')
            ->where('username', $username);

        return ($query->count() > 0) ? true : false;
    }


    /**
     * Retrieves a list of online sessions for a user. This is used for the disconnect command.
     *
     * @param $username
     * @return mixed
     */
    public static function getOnlineUserSessions($username) {
        $query = self::select('username')
            ->whereRaw('acctstoptime IS NULL')
            ->where('username', $username)
            ->orWhere('acctstoptime', '0000-00-00 00:00:00')
            ->where('username', $username)
            ->get();

        return $query;
    }

    /**
     * returns a list of online users.
     *
     * @return mixed
     */
    public static function onlineUsers() {
        $sql = 'radacctid, username, framedipaddress, nasipaddress, sum(acctsessiontime) as acctsessiontime, count(acctstarttime) as connections, DAY(acctstarttime) AS day, MONTH(acctstarttime) AS month, YEAR(acctstarttime) AS year, sum(acctinputoctets) AS acctinputoctets, sum(acctoutputoctets) AS acctoutputoctets, sum(acctinputoctets + acctoutputoctets) AS total';

        $query = self::selectRaw($sql)
            ->whereRaw('AcctStopTime IS NULL')
            ->orWhere('AcctStopTime', '0000-00-00 00:00:00')
            ->groupBy('username', 'acctstarttime');

        return $query;
    }

    /**
     * Returns the latest activity for a specified nas
     *
     * @param int $limit
     * @param string $nasIP
     */
    public static function getLatestNasActivity($nasIP, $limit = 15) {
        return self::select([
            'username',
            'nasipaddress',
            'nasportid',
            'nasporttype',
            'acctstarttime',
            'acctstoptime',
            'acctsessiontime',
            'acctinputoctets',
            'acctoutputoctets'
        ])
            ->orderBy('radacctid', 'desc')
            ->where('nasipaddress', $nasIP)
            ->limit($limit);
    }
}