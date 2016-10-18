<?php namespace App\Utils;

use App\RadiusAccount;
use App\BandwidthSummary;
use App\ActiveConnectionSummary;
use App\Utils\DataHelper;
use Carbon\Carbon;

class Bandwidth
{
    public static function process() {
        self::processOpenConnections();
        self::processClosedConnections();
    }

    /**
     * Processes open connections
     */
    public static function processOpenConnections() {
        $currentDate = Carbon::now();
        $openConnections = RadiusAccount::getOpenConnections();

        foreach ($openConnections as $connection) {
            $download = $connection->download;
            $upload = $connection->upload;
            $total = $connection->total;

            $usage = ActiveConnectionSummary::getConnectionUsage($connection->radacctid);
            if ($usage !== null) {
                $download -= $usage->download;
                $upload -= $usage->upload;
                $total -= $usage->total;
            }


            if ($total <= 0) {
                continue;
            }

            $currentRecord = ActiveConnectionSummary::getCurrentConnection($connection->radacctid, $currentDate);

            if ($currentRecord !== null) {
                $currentRecord->download += $download;
                $currentRecord->upload += $upload;
                $currentRecord->total += $total;
                $currentRecord->save();
            } else {
                $record = ActiveConnectionSummary::create([
                    'connection_id' => $connection->radacctid,
                    'nas_id'        => $connection->nas_id,
                    'username'      => $connection->username,
                    'date'          => $currentDate,
                    'download'      => $download,
                    'upload'        => $upload,
                    'total'         => $total,
                ]);
            }
        }
    }

    /**
     * Prccesses closed connection to create bandwidthsummary records
     */
    public static function processClosedConnections() {
        $connections = RadiusAccount::getUnprocessed();

        foreach($connections as $connection) {
            $summarizedConnections = ActiveConnectionSummary::getConnections($connection->radacctid);
            $date = Carbon::createFromFormat("Y-m-d H:i:s", $connection->acctstoptime);

            if (count($summarizedConnections) > 0) {
                
            }

            $download = $connection->download;
            $upload = $connection->upload;
            $total = $connection->total;
            $usage = ActiveConnectionSummary::getConnectionUsage($connection->radacctid);

            if ($usage !== null) {
                $download -= $usage->download;
                $upload -= $usage->upload;
                $total -= $usage->total;
            }

            $currentRecord = BandwidthSummary::getConnectionForDate(
                $connection->username,
                $connection->nas_id,
                $date
            );

            if ($currentRecord !== null) {
                $currentRecord->download += $download;
                $currentRecord->upload += $upload;
                $currentRecord->total += $total;
            } else {
                $record = BandwidthSummary::create([
                    'username' => $connection->username,
                    'nas_id' => $connection->nas_id,
                    'date' => $date->toDateString(),
                    'download' => $download,
                    'upload' => $upload,
                    'total' => $total
                ]);
            }

            $connection->processed = 1;
            $connection->save();
        }

    }

    public static function cleanupClosedConnections() {
        // implement....
    }

    /**
     * Calculates the records array index for a given week range.
     *
     * @param $startDate
     * @param $record
     * @return int
     * @throws Exception
     */
    private static function calculateWeekIndex($startDate, $record) {
        $day = new \DateTime($startDate);
        for ($i = 0; $i <= 7; $i++) {
            if ($i > 0) {
                $day->modify('+1 days');
            }

            if ((int) $day->format('d') === $record->day) {
                return $i;
            }
        }

        throw new Exception("Couldn't calculate day index.");
    }
}