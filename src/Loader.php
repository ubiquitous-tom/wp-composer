<?php

namespace RLJE;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Loader
{
    private static $originFolder = 'includes/rlje-wp/';
    private static $destinationFolder = 'wordpress/';

    public static function postInstall(Event $event)
    {
        $composer = $event->getComposer();
    }

    public static function postUpdate(Event $event)
    {
        $composer = $event->getComposer();

        $files = [
            self::$originFolder . '.ebextensions/redis.config' => self::$destinationFolder . '.ebextensions/redis.config',
            self::$originFolder . '.htaccess' => self::$destinationFolder . '.htaccess',
            self::$originFolder . 'wp-config.php' => self::$destinationFolder . 'wp-config.php',
            self::$originFolder . 'wp-content/wp-cache-config.php' => self::$destinationFolder . 'wp-content/wp-cache-config.php',
        ];
        foreach ($files as $orig => $dest) {
            $path = pathinfo($dest);
            if (!file_exists($path['dirname'])) {
                echo $path['dirname'] . " doesn't exist. Creating...\n";
                mkdir($path['dirname'], 0777, true);
            }
            self::copyFile($orig, $dest);
        }

        echo "Done...\n";
    }

    public static function postAutoLoadDump(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        require $vendorDir . 'autoload.php';

        some_function_from_an_autoloaded_file();
    }

    public static function postPackageInstall(PackageEvent $event)
    {
        $installedPackage = $event->getOperation()->getPackage();
        // do stuff
    }

    public static function warmCache(Event $event)
    {
        // make cache toasty
    }

    private static function copyFile($origFileName, $destFileName)
    {
        if (! file_exists($destFileName)) {
            if (copy($origFileName, $destFileName)) {
                echo "Moving $origFileName to $destFileName...\n";
            } else {
                echo "Failed to move $origFileName to $destFileName...\n";
            }
        } else {
            echo "$destFileName already existed. Skip...\n";
        }
    }
}
