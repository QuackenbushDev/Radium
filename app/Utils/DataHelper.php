<?php namespace App\Utils;

use App\BandwidthSummary;
use App\RadiusAccount;
use DateTime;
use Carbon\Carbon;

class DataHelper {
    /**
     * Copyright Kryzstof Karski 2016
     * License: cc by-sa 3.0
     * URL: http://stackoverflow.com/questions/15188033/human-readable-file-size
     *
     * Changes:
     * Code formatting
     * Set factor override to normalize responses where data could be of varying sizes.
     * Enable of disable the ability to include the size indicator
     *
     * @param int $bytes
     * @param int $decimals
     * @param string $system
     * @param int $factor
     * @param bool $includeUnits
     * @return string
     */
    static function convertToHumanReadableSize($bytes = 0, $decimals = 2, $system = 'binary', $factor = 0, $includeUnits = true) {
        $mod = ($system === 'binary') ? 1024 : 1000;

        $units = [
            'binary' => ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'],
            'metric' => ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
        ];

        if ($factor === 0) {
            $factor = (int) floor((strlen($bytes) - 1) / 3);
        }

        $printFormat = ($includeUnits) ? "%.{$decimals}f %s" : "%.{$decimals}f";
        return sprintf($printFormat, $bytes / pow($mod, $factor), $units[$system][$factor]);
    }

    /**
     * Calculates the bandwidth usage for a given user and returns an array
     * with the daily, monthly, yearly stats
     *
     * @param string $username
     * @param int $nasID
     * @return array
     */
    static function calculateUserBandwidth($username, $nasID = null) {
        $dailyUsage = BandwidthSummary::getUsageForDateRange(
            $username,
            $nasID,
            Carbon::today()->toDateString(),
            Carbon::today()->toDateString()
        );
        $monthlyUsage = BandwidthSummary::getUsageForDateRange(
            $username,
            $nasID,
            Carbon::now()->startOfMonth()->toDateString(),
            Carbon::now()->endOfMonth()->toDateString()
        );
        $yearlyUsage = BandwidthSummary::getUsageForDateRange(
            $username,
            $nasID,
            Carbon::now()->startOfYear()->toDateString(),
            Carbon::now()->endOfYear()->toDateString()
        );

        $bandwidth = [];
        if ($dailyUsage !== null) {
            $bandwidth['day'] = [
                'download' => self::convertToHumanReadableSize($dailyUsage->download),
                'upload'   => self::convertToHumanReadableSize($dailyUsage->upload),
                'total'    => self::convertToHumanReadableSize($dailyUsage->total)
            ];
        } else {
            $bandwidth['day'] = ['download' => 0, 'upload' => 0, 'total' => 0];
        }

        if ($monthlyUsage !== null) {
            $bandwidth['month'] = [
                'download' => self::convertToHumanReadableSize($monthlyUsage->download),
                'upload'   => self::convertToHumanReadableSize($monthlyUsage->upload),
                'total'    => self::convertToHumanReadableSize($monthlyUsage->total)
            ];
        } else {
            $bandwidth['month'] = ['download' => 0, 'upload' => 0, 'total' => 0];
        }

        if ($yearlyUsage !== null) {
            $bandwidth['year'] = [
                'download' => self::convertToHumanReadableSize($yearlyUsage->download),
                'upload'   => self::convertToHumanReadableSize($yearlyUsage->upload),
                'total'    => self::convertToHumanReadableSize($yearlyUsage->total)
            ];
        } else {
            $bandwidth['year'] = ['download' => 0, 'upload' => 0, 'total' => 0];
        }

        return $bandwidth;
    }

    /**
     * Converts seconds to a human readable days, hours, minutes, seconds output
     *
     * Copyright: Glavic 2013
     * License: cc by-sa 3.0
     * URL: http://stackoverflow.com/questions/8273804/convert-seconds-into-days-hours-minutes-and-seconds
     *
     * Changes:
     * Code formatting
     * Casting inputSeconds as forced int
     *
     * @param $inputSeconds
     * @return string
     */
    public static function secondsToHumanReadableTime($inputSeconds) {
        $inputSeconds = (int) $inputSeconds;

        $dtF = new DateTime('@0');
        $dtT = new DateTime("@$inputSeconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }
}