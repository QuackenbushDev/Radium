<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Utils\DataHelper;
use DB;

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

    /**
     * Retrieves a connection for a specified date string
     *
     * @param string $username
     * @param int $nasID
     * @param Carbon $date
     * @return mixed
     */
    public static function getConnectionForDate($username, $nasID, Carbon $date) {
        return self::select('*')
            ->where('username', $username)
            ->where('nas_id', $nasID)
            ->where('date', $date->toDateString())
            ->first();
    }

    /**
     * Retrieves a summary of data usage for a given date range.
     *
     * @param string $username
     * @param int $nasID
     * @param string $startDate
     * @param string $stopDate
     * @return mixed
     */
    public static function getUsageForDateRange($username = null, $nasID = null, $startDate, $stopDate) {
        $columns = "username"
            . ",nas_id"
            . ",SUM(download) as download"
            . ",SUM(upload) as upload"
            . ",SUM(total) as total";

        $query = self::selectRaw($columns);
        $activeConnections = ActiveConnectionSummary::selectRaw($columns);

        if ($username !== null) {
            $query->where('username', $username);
            $activeConnections->where('username', $username);
        }

        if ($nasID !== null) {
            $query->where('nas_id', $nasID);
            $activeConnections->where('nas_id', $nasID);
        }

        $query->where('date', '>=', $startDate)
            ->where('date', '<=', $stopDate);
        $activeConnections->where('date', '>=', $startDate)
            ->where('date', '<=', $stopDate);

        $response = $query->union($activeConnections)
            ->get();

        $summary = new BandwidthSummary();
        $summary->download = 0;
        $summary->upload = 0;
        $summary->total = 0;
        foreach ($response as $connection) {
            if ($connection->download !== null) {
                $returnNull = false;
                $summary->username = $connection->username;
                $summary->nas_id = $connection->nas_id;
                $summary->download += $connection->download;
                $summary->upload += $connection->upload;
                $summary->total += $connection->total;
            }
        }

        return $summary;
    }
    /**
     * Retrieves the bandwidth summary for a username and/or nas.
     * Results are normalized in binary GiB format to keep everything consistent.
     *
     * @param $timeSpan
     * @param null $timeValue
     * @param null $username
     * @param null $nasID
     * @return array
     */
    public static function bandwidthUsage($timeSpan, $timeValue = null, $username = null, $nasID = null) {
        $columns = 'DAY(date) AS day'
            . ',MONTH(date) AS month'
            . ',YEAR(date) AS year'
            . ',SUM(download) as download'
            . ',SUM(upload) as upload';

        $query = self::selectRaw($columns);
        $activeConnections = ActiveConnectionSummary::selectRaw($columns);

        if ($username !== null) {
            $query->where('username', $username);
            $activeConnections->where('username', $username);
        }

        if ($nasID !== null) {
            $query->where('nas_id', $nasID);
            $activeConnections->where('nas_id', $nasID);
        }

        switch($timeSpan) {
            case "year":
                $query->orderBy('year', 'asc');
                $activeConnections->orderBy('year', 'asc');
                $count = 0;
                break;

            default:
            case "month":
                $query->having('year', '=', $timeValue)
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $activeConnections->having('year', '=', $timeValue)
                    ->groupBy('month')
                    ->orderBy('month', 'asc');
                $count = 12;
                break;

            case "day":
                $query->where('date', '>=', date("Y")."-".$timeValue."-01")
                    ->where('date', '<=', date("Y")."-".$timeValue."-31")
                    ->groupBy('day')
                    ->orderBy('day', 'asc');
                $activeConnections->where('date', '>=', date("Y")."-".$timeValue."-01")
                    ->where('date', '<=', date("Y")."-".$timeValue."-31")
                    ->groupBy('day')
                    ->orderBy('day', 'asc');
                $count = cal_days_in_month(CAL_GREGORIAN, $timeValue, date('Y'));
                break;
        }

        $results = $query->union($activeConnections)
            ->get();

        $usage = [
            'download' => array_pad([], $count, 0),
            'upload'   => array_pad([], $count, 0)
        ];

        foreach ($results as $result) {
            $download = DataHelper::convertToHumanReadableSize($result->download, 2, 'binary', 3, false);
            $upload = DataHelper::convertToHumanReadableSize($result->upload, 2, 'binary', 3, false);

            if ($timeSpan === 'year') {
                $usage['download'][] += (float) $download;
                $usage['upload'][] += (float) $upload;
            } else {
                $index = $result->$timeSpan - 1;
                $usage['download'][$index] += (float) $download;
                $usage['upload'][$index] += (float) $upload;
            }
        }

        return $usage;
    }

    /**
     * Returns the latest activity for a specified nas
     *
     * @param int $limit
     * @param string $nasID
     */
    public static function getLatestNasActivity($nasID, $limit = 15) {
        return self::select([
            'username',
            'date',
            'download',
            'upload',
            'total',
            'nas_id'
        ])
            ->orderBy('date', 'desc')
            ->where('nas_id', $nasID)
            ->limit($limit);
    }

    /**
     * Generates a list of top users based on their bandwidth usage
     *
     * @param $start
     * @param $stop
     * @param $nasName
     * @return mixed
     */
    public static function topUsers($start, $stop, $nasName) {
        $columns = "username, SUM(download) as download, SUM(upload) as upload"
            . ", SUM(total) as total, nas_id";
        $summary = self::selectRaw($columns)->groupBy('username', 'nas_id');
        $dailySummary = ActiveConnectionSummary::selectRaw($columns)->groupBy('username', 'nas_id');

        if (!empty($start)) {
            $summary->where('date', '>=', $start);
            $dailySummary->where('date', '>=', $start);
        }

        if (!empty($stop)) {
            $summary->where('date', '<=', $stop);
            $dailySummary->where('date', '<=', $stop);
        }

        $subquery = $summary->union($dailySummary);
        $query = self::selectRaw($columns . ", nasname", $subquery->getBindings())
            ->from(DB::raw('(' . $subquery->getQuery()->toSQL() . ') t'))
            ->leftJoin('nas', 't.nas_id', '=', 'nas.id')
            ->limit(50)
            ->orderBy('total', 'DESC')
            ->groupBy('username');

        if (!empty($nasName)) {
            $query->where('nas.nasname', 'LIKE', '%' . $nasName . '%');
        }

        return $query->get();
    }

    /**
     * Generates the bandwidth accounting report using radium_bandwidth_summary and
     * radium_active_connection_summary
     *
     * @param $start
     * @param $stop
     * @param $username
     * @param $nasID
     * @return mixed
     */
    public static function bandwidthAccountingReport($start, $stop, $username, $nasID) {
        $columns = "username, date, download, upload, total, nas_id";
        $summary = self::selectRaw($columns);
        $dailySummary = ActiveConnectionSummary::selectRaw($columns);

        if (!empty($start)) {
            $summary->where('date', '>=', $start);
            $dailySummary->where('date', '>=', $start);
        }

        if (!empty($stop)) {
            $summary->where('date', '<=', $stop);
            $dailySummary->where('date', '<=', $stop);
        }

        if (!empty($username)) {
            $summary->where('username', 'LIKE', '%' . $username . '%');
            $dailySummary->where('username', 'LIKE', '%' . $username . '%');
        }

        if (!empty($nasID)) {
            $summary->where('nas_id', $nasID);
            $dailySummary->where('nas_id', $nasID);
        }

        $subquery = $summary->union($dailySummary);
        $query = self::selectRaw($columns . ", nasname", $subquery->getBindings())
            ->from(DB::raw('(' . $subquery->getQuery()->toSQL() . ') t'))
            ->leftJoin('nas', 't.nas_id', '=', 'nas.id')
            ->groupBy("username", "nasname", "date")
            ->orderBy('t.date', 'DESC');

        return $query;
    }
}