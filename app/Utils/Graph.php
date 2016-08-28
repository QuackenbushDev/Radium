<?php namespace App\Utils;

use App\RadiusAccount;
use Symfony\Component\Process\Process;

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
        $headers = self::generateHeaders($timeSpan);
        $command = 'python ' . base_path() . '\scripts\bw-graph.py "Bandwidth Usage" "' . implode("|", $headers) . '" "Download"  "' . implode("|", $data['download']) . '" "Upload" "' . implode("|", $data['upload']) . '"';

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
        $headers = self::generateHeaders($timeSpan);
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
     * @param $timeSpan
     * @return array
     */
    public static function generateHeaders($timeSpan) {
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
                $date = new \DateTime();
                $date->sub(new \DateInterval('P7D'));
                break;

            case "day":
                $headers = [];
                for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')); $i++) {
                    $headers[] = (string) $i;
                }
                return $headers;
        }

        return [];
    }
}