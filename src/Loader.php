<?php

namespace RLJE;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;

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
        self::moveFiles($files);

        $folders = [
            'wordpress/wp-content/plugins/rlje-api-wp-plugin/.git/',
            'wordpress/wp-content/plugins/rlje-wp-plugin/.git/',
            'wordpress/wp-content/themes/rlje/.git/',
        ];
        self::removeFolders($folders);

        echo "Finish running `post-update-cmd` command\n";
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

    private static function moveFiles($files)
    {
        foreach ($files as $orig => $dest) {
            $path = pathinfo($dest);
            if (!file_exists($path['dirname'])) {
                echo $path['dirname'] . " doesn't exist. Creating...\n";
                mkdir($path['dirname'], 0777, true);
            }
            self::copyFile($orig, $dest);
        }

        echo "Done moving files...\n";
    }

    private static function removeFolders($folders)
    {
        // foreach ($folders as $folder) {
        //     echo $folder . " exists. Deleting files inside...\n";
        //     $files = glob("$folder{,.}*", GLOB_BRACE);
        //     foreach ($files as $file) { // iterate files
        //         if (is_file($file)) {
        //             unlink($file); // delete file
        //         }
        //     }

        //     if (is_dir($folder)) {
        //         rmdir($folder);
        //         echo $folder . ". Deleted...\n";
        //     }
        // }

        foreach ($folders as $folder) {
            $dir = $folder;
            $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
            $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($ri as $file) {
                echo "Deleting $file\n";
                if ($file->isDir()) {
                    $is_deleted = rmdir($file);
                } else {
                    $is_deleted = unlink($file);
                }

                if ($is_deleted) {
                    echo "$file is deleted.\n";
                } else {
                    echo "$file cannot be deleted.\n";
                }
            }
        }

        echo "Done deleting given folders and files inside...\n";
    }
}
