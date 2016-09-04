<?php namespace App\Utils;

use App\RadiusAccount;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use DateTime;

class Graph {
    /**
     * Generates the bandwidth graph as a PNG and returns the raw base64 encoded data
     *
     * @param string $timeSpan
     * @param string $timeValue
     * @param string $username
     * @param string $nasIP
     * @return string
     */
    public static function createBandwidthGraphPNG($timeSpan = 'month', $timeValue = null, $username = null, $nasIP = null) {
        $data = RadiusAccount::bandwidthUsage($timeSpan, $timeValue, $username, $nasIP);
        $headers = self::generateHeaders($timeSpan, $timeValue);

        if ($nasIP === null) {
            $title = 'Bandwidth Usage';
        } else {
            $title = 'Bandwidth Usage for NAS ' . $nasIP;
        }

        $command = 'python ' . base_path() . '\scripts\bw-graph.py "' . $title . '" "' . implode("|", $headers) . '" "Download"  "' . implode("|", $data['download']) . '" "Upload" "' . implode("|", $data['upload']) . '"';

        try {
            $process = new Process($command);
            $process->mustRun();
            $output = $process->getOutput();
        } catch (ProcessFailedException $e) {
            $output = $e->getMessage();
        }

        return $output;
    }

    /**
     * Generates the connection graph as a PNG and return the raw base64 encoded data
     *
     * @param string $timeSpan
     * @param string $timeValue
     * @param string $username
     * @param string $nasIP
     * @return string
     */
    public static function createConnectionGraphPNG($timeSpan = 'month', $timeValue = null, $username = null, $nasIP = null) {
        $data = RadiusAccount::connectionCountSummary($timeSpan, $timeValue, $username, $nasIP);
        $headers = self::generateHeaders($timeSpan, $timeValue);
        $command = 'python ' . base_path() . '\scripts\connection-graph.py "Connections" "' . implode("|", $headers) . '" "Connections"  "' . implode("|", $data) . '"';

        try {
            $process = new Process($command);
            $process->mustRun();
            $output = $process->getOutput();
        } catch (ProcessFailedException $e) {
            $output = $e->getMessage();
        }

        return $output;
    }

    /**
     * Returns an array of years (where data is available), months,
     * or a list with the number of days starting from 1
     *
     * @param string $timeSpan
     * @param string $timeValue
     * @return array
     */
    public static function generateHeaders($timeSpan, $timeValue = null) {
        switch ($timeSpan) {
            case "year":
                return array_flatten(
                    RadiusAccount::selectRaw('YEAR(acctstarttime) as year')
                        ->groupBy('year')
                        ->get()
                        ->toArray()
                );

            case "month":
                return array_flatten(cal_info(CAL_GREGORIAN)['abbrevmonths']);

            case "week":
                $headers = [];
                $date = new DateTime($timeValue);

                for ($i=0; $i <= 6; $i++) {
                    if ($i > 0) {
                        $date->modify('+1 days');
                    }
                    $headers[] = $date->format('d');
                }

                return $headers;

            case "day":
                $headers = [];
                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $timeValue, date('Y')); $i++) {
                    $headers[] = (string) $i;
                }
                return $headers;
        }

        return [];
    }
}