<?php namespace App\Utils;

use App\Proxy;
use App\Realm;
use Exception;
use Storage;

class ConfigWriter {
    public static function writeProxyConfigV1() {
        $proxyConfigExists = Storage::disk('config')->exists(config('radium.proxy_file'));

        if ($proxyConfigExists) {
            $proxyConfig = Storage::disk('config')->get(config('radium.proxy_file'));

            if (strpos($proxyConfig, 'GENERATED BY RADIUM')) {
                echo "Config last generated by radium. Proceeding.\n";
            } else {
                echo "Configuration file manually changed! \n";
                echo "Update Radium's internal proxy / realm database with your changes.\n";
                echo "Please delete the file and run php .\\artisan proxy:write and restart freeradius.\n";

                throw new Exception("Custom modifications detected");
            }
        }

        $config = "# GENERATED BY RADIUM ON " . date('Y-m-d g:i:s') . "\n\n";
        $proxies = Proxy::all();
        $realms = Realm::all();

        $config .= "# Proxy configuration \n";
        foreach ($proxies as $proxy) {
            $synchronous = ($proxy->synchronous) ? 'yes' : 'no';
            $defaultFallback = ($proxy->default_fallback) ? 'yes' : 'no';
            $postProxyAuthorize = ($proxy->post_proxy_authorize) ? 'yes' : 'no';

            $config .= "proxy " . $proxy->name . " {\n";

            $config .= "\tsynchronous = " . $synchronous . "\n";
            $config .= "\tretry_delay = " . $proxy->retry_delay . "\n";
            $config .= "\tretry_count = " . $proxy->retry_count . "\n";
            $config .= "\tdead_time = " . $proxy->dead_time . "\n";
            $config .= "\tdefault_fallback = " . $defaultFallback . "\n";
            $config .= "\tpost_proxy_authorize = " . $postProxyAuthorize . "\n";

            $config .= "}\n\n";
        }

        $config .= "# Realm configuration \n";
        foreach ($realms as $realm) {
            $config .= "realm " . $realm->name . " {\n";

            $config .= "\ttype = " . $realm->type . "\n";
            $config .= "\tauthhost = " . $realm->authhost . "\n";
            $config .= "\taccthost = " . $realm->accthost . "\n";

            if (!empty($realm->secret) && $realm->secret !== null) {
                $config .= "\tsecret = " . $realm->secret . "\n";
            }

            if (!empty($realm->ldflag) && $realm->ldflag !== null) {
                $config .= "\tldflag = " . $realm->ldflag . "\n";
            }

            if ($realm->nostrip) {
                $config .= "\tnostrip\n";
            }

            $config .= "}\n\n";
        }

        echo $config;
    }

    public static function writeProxyConfigV2() {
        $proxyConfigExists = Storage::disk('config')->exists(config('radium.proxy_file'));

        if ($proxyConfigExists) {
            $proxyConfig = Storage::disk('config')->get(config('radium.proxy_file'));

            if (strpos($proxyConfig, 'GENERATED BY RADIUM')) {
                echo "Config last generated by radium. Proceeding.\n";
            } else {
                echo "Configuration file manually changed! \n";
                echo "Update Radium's internal proxy / realm database with your changes.\n";
                echo "Please delete the file and run php .\\artisan proxy:write and restart freeradius.\n";

                throw new Exception("Custom modifications detected");
            }
        }
    }

    public static function backupConfig() {

    }
}