<?php

use App\Nas;
use App\RadiusAccount;
use App\RadiusCheck;
use App\RadiusUserGroup;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Radius {
    /**
     * Disconnect all open sessions for a user.
     *
     * @param integer $userID
     * @param string $type
     * @return string
     */
    public static function disconnectUser($userID, $type = 'disconnect') {
        $user = RadiusCheck::findOrFail($userID);
        $openConnections = RadiusAccount::getOpenConnections($user->username);
        $output = '';

        foreach($openConnections as $connection) {
            $nas = Nas::findByNasIp($connection->nasipaddress);

            $echo = "echo \"User-Name='". $user->username . "'\"";
            $radClient = "radclient -c '1' -n '3' -r '3' -t '3' -x '" . $nas->nasname . ":" . $nas->nas_port
                . "' '" . $type . "' '" . $nas->secret . "'";

            $command = $echo . " | " . $radClient;

            try {
                $process = new Process($command);
                $process->mustRun();

                $output .= $process->getOutput() . "\r\n\r\n";
            } catch (ProcessFailedException $e) {
                $output .= $e->getMessage() . "\r\n\r\n";
            }
        }

        return $output;
    }

    /**
     * Validate the user credentials for access to radius
     *
     * @param $username
     * @param $password
     * @param $nasIP
     * @return string
     */
    public static function validateUserCredentials($username, $password, $nasIP) {
        $radiusServer = env('RADIUM_RADIUS_SERVER', '') . ':' . env('RADIUM_RADIUS_PORT', 0);
        $radiusSecret = env('RADIUM_RADIUS_SECRET');

        $echo = "echo \"User-Name='". $username . "',User-Password='" . $password . "'\"";
        $radClient = "radclient -c '1' -n '3' -r '3' -t '3' -x '" . $radiusServer . "' 'auth' '" . $radiusSecret . "'";
        $command = $echo . " | " . $radClient;

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
     * Disable a given username for access to radius, by adding them to the disabled group.
     *
     * @param $userID
     * @param string $disabledGroupName
     * @return bool
     */
    public static function disableUser($userID, $disabledGroupName = 'default') {
        $user = RadiusCheck::findOrFail($userID);
        $groups = RadiusUserGroup::getUserGroups($user->username);

        if ($disabledGroupName === 'default') {
            $disabledGroupName = config('radium.disabled_group');
        }

        if (!in_array($disabledGroupName, $groups)) {
            RadiusUserGroup::create([
                'username'  => $user->username,
                'groupname' => $disabledGroupName,
                'priority'  => 0,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Enable a given username for access to radius, by removing them from the disabled group
     *
     * @param $userID
     * @param string $disabledGroupName
     * @return bool
     * @throws Exception
     */
    public static function enableUser($userID, $disabledGroupName = 'default') {
        try {
            $user = RadiusCheck::findOrFail($userID);
        } catch (Exception $e) {
            throw $e;
        }

        if ($disabledGroupName === 'default') {
            $disabledGroupName = config('radium.disabled_group');
        }

        RadiusUserGroup::where('username', $user->username)
            ->where('groupname', $disabledGroupName)
            ->delete();

        return true;
    }

    /**
     * Hashes a users password with the selected hashing mechanism
     *
     * @param $passwordType
     * @param $password
     * @throws Exception
     * @return string
     */
    public static function hashPassword($passwordType, $password) {
        switch($passwordType) {
            case 'Crypt-Password':
                return crypt($password, Str::random());

            case 'Cleartext-Password':
                return $password;
        }

        throw new Exception('Invalid password type');
    }
}