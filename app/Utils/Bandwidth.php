<?php namespace App\Utils;

use App\RadiusAccount;
use App\BandwidthSummary;
use App\ActiveConnectionSummary;
use App\Utils\DataHelper;
use DateTime;

class Bandwidth
{
    public static function process() {
        //\DB::beginTransaction();

        $stats = [
            'upload' => 0,
            'download' => 0,
            'total' => 0
        ];

        // Handle open connections
        $currentDate = new DateTime();
        $openConnections = RadiusAccount::getOpenConnections();
        foreach ($openConnections as $connection) {
            $usage = ActiveConnectionSummary::getConnectionUsage($connection->radacctid);
            if ($usage === null) {
                $download = $connection->download;
                $upload = $connection->upload;
                $total = $connection->total;
                echo "Creating new usage record for connection.\r\n";
            } else {
                $download = $connection->download - $usage->download;
                $upload = $connection->upload - $usage->upload;
                $total = $connection->total - $usage->total;
                echo "Existing usage found. \r\n";
            }

            $currentRecord = ActiveConnectionSummary::getCurrentConnection(
                $connection->username,
                $connection->nas_id,
                $currentDate
            );
            if ($currentRecord !== null) {
                $currentRecord->download += $download;
                $currentRecord->upload += $upload;
                $currentRecord->total += $total;
                $currentRecord->save();
                echo "Found existing connection: " . $currentRecord->id;
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

            $stats['download'] += $download;
            $stats['upload']   += $upload;
            $stats['total']    += $total;
        }

        //\DB::rollBack();

        dd(
            "Download: " . \App\Utils\DataHelper::convertToHumanReadableSize($stats['download']),
            "Upload: "   . \App\Utils\DataHelper::convertToHumanReadableSize($stats['upload']),
            "Total: "    . \App\Utils\DataHelper::convertToHumanReadableSize($stats['total'])
        );
    }

    public static function getOpenConnections() {

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