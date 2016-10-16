<?php namespace App\Utils;

use App\RadiusAccount;
use App\BandwidthSummary;
use App\ActiveConnectionSummary;
use App\Utils\DataHelper;
use DateTime;

class Bandwidth
{
    public static function process() {
        //self::processOpenConnections();
        self::processClosedConnections();
    }

    public static function processOpenConnections() {
        $currentDate = new DateTime();
        $openConnections = RadiusAccount::getOpenConnections();

        foreach ($openConnections as $connection) {
            $download = (int) $connection->download;
            $upload = (int) $connection->upload;
            $total = (int) $connection->total;

            $usage = ActiveConnectionSummary::getConnectionUsage($connection->radacctid);
            if ($usage !== null) {
                $download -= (int) $usage->download;
                $upload -= (int) $usage->upload;
                $total -= (int) $usage->total;
            }

            if ($total === 0) {
                continue;
            }

            $currentRecord = ActiveConnectionSummary::getCurrentConnection($connection->radacctid, $currentDate);

            if ($currentRecord !== null) {
                $currentRecord->download += $download;
                $currentRecord->upload += $upload;
                $currentRecord->total += $total;
                $currentRecord->save();
            } else {
                ActiveConnectionSummary::create([
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

    public static function processClosedConnections() {
        $connections = RadiusAccount::getUnprocessed();

        foreach($connections as $connection) {
            $summarizedConnections = ActiveConnectionSummary::getConnections($connection->radacctid);

            foreach ($summarizedConnections as $summarizedConnection) {
                $summary = BandwidthSummary::getDailySummary(
                    $summarizedConnection->username,
                    $summarizedConnection->nas_id,
                    $summarizedConnection->date
                );

                if ($summary !== null) {
                    $summary->download += $summarizedConnection->download;
                    $summary->upload += $summarizedConnection->upload;
                    $summary->total += $summarizedConnection->total;
                    $summary->save();
                } else {
                    $summary = BandwidthSummary::create([
                        'username' => $summarizedConnection->username,
                        'nas_id' => $summarizedConnection->nas_id,
                        'date' => $summarizedConnection->date,
                        'download' => $summarizedConnection->download,
                        'upload' => $summarizedConnection->upload,
                        'total' => $summarizedConnection->total,
                    ]);
                }
            }

            $download = $connection->download;
            $upload = $connection->upload;
            $total = $connection->total;

            $usage = BandwidthSummary::getUsageSummary(
                $connections->username,
                $connections->nas_id,
                $connections->date
            );

            if ($usage !== null) {
                $download -= $usage->download;
                $upload -= $usage->upload;
                $total -= $usage->total;
            }

            $activeConnection = BandwidthSummary::getDailySummary(
                $connections->username,
                $connections->nas_id,
                $connections->date
            );

            if ($activeConnection !== null) {
                $activeConnection->download += $download;
                $activeConnection->upload += $upload;
                $activeConnection->total += $total;
            } else {
                $currentDate = new DateTime();
                $summary = BandwidthSummary::create([
                    'username' => $connection->username,
                    'nas_id' => $connection->nas_id,
                    'date' => $currentDate->format("Y-m-d"),
                    'download' => $download,
                    'upload' => $upload,
                    'total' => $total,
                ]);
            }
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