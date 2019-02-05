<?php

namespace RLJE;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;

class Loader
{
    public static function postInstall(Event $event)
    {
        $composer = $event->getComposer();
    }

    public static function postUpdate(Event $event)
    {
        $composer = $event->getComposer();

        $files = [
            'includes/rlje-wp/.ebextensions/redis.config' => 'html/.ebextensions/redis.config',
            'includes/rlje-wp/.htaccess' => 'html/.htaccess',
            'includes/rlje-wp/wp-config.php' => 'html/wp-config.php',
            'includes/rlje-wp/wp-content/wp-cache-config.php' => 'html/wp-content/wp-cache-config.php',
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
