<?php namespace App\Utils;

use App\RadiusAccount;
use DateTime;

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
     * @return array
     */
    static function calculateUserBandwidth($username) {
        $dailyUsage = RadiusAccount::getConnections('day', date('d'), true, $username)->first();
        $monthlyUsage = RadiusAccount::getConnections('month', date('m'), true, $username)->first();
        $yearlyUsage = RadiusAccount::getConnections('year', date('Y'), true, $username)->first();

        $bandwidth = [];
        if ($dailyUsage !== null) {
            $bandwidth['day'] = [
                'connections' => $dailyUsage->connections,
                'in'          => self::convertToHumanReadableSize($dailyUsage->acctinputoctets),
                'out'         => self::convertToHumanReadableSize($dailyUsage->acctoutputoctets),
                'total'       => self::convertToHumanReadableSize($dailyUsage->total)
            ];
        } else {
            $bandwidth['day'] = ['connections' => 0, 'in' => 0, 'out' => 0, 'total' => 0];
        }

        if ($monthlyUsage !== null) {
            $bandwidth['month'] = [
                'connections' => $monthlyUsage->connections,
                'in'          => self::convertToHumanReadableSize($monthlyUsage->acctinputoctets),
                'out'         => self::convertToHumanReadableSize($monthlyUsage->acctoutputoctets),
                'total'       => self::convertToHumanReadableSize($monthlyUsage->total)
            ];
        } else {
            $bandwidth['month'] = ['connections' => 0, 'in' => 0, 'out' => 0, 'total' => 0];
        }

        if ($yearlyUsage !== null) {
            $bandwidth['year'] = [
                'connections' => $yearlyUsage->connections,
                'in'          => self::convertToHumanReadableSize($yearlyUsage->acctinputoctets),
                'out'         => self::convertToHumanReadableSize($yearlyUsage->acctoutputoctets),
                'total'       => self::convertToHumanReadableSize($yearlyUsage->total)
            ];
        } else {
            $bandwidth['year'] = ['connections' => 0, 'in' => 0, 'out' => 0, 'total' => 0];
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