<?php
defined('ABSPATH') or die('No direct Access to this file');

class Helper_LoggerWooFileDropzone
{

    const ERROR = 1, DB_ERROR = 2, NOTICE = 3, DEBUG = 4;

    public function __construct()
    {
        // Add necessary initialization
    }

    public static function log($type, $message)
    {
        if ($type === self::ERROR) {
            $msg = "[" . date('d-m-Y  h:i:s A') . "] ERROR: " . $message.PHP_EOL;
        } elseif ($type === self::DB_ERROR) {
            $msg = "[" . date('d-m-Y  h:i:s A') . "] DB_ERROR: " . $message.PHP_EOL;
        } elseif ($type === self::DEBUG) {
            $msg = "[" . date('d-m-Y  h:i:s A') . "] DEBUG: " . $message.PHP_EOL;
        } else {
            $msg = "[" . date('d-m-Y  h:i:s A') . "] NOTICE: " . $message.PHP_EOL;
        }
        $file = fopen(WOO_FILE_DROPZONE_PLUGIN_DIR . "/logs/logs.log", 'a+');
        fwrite($file, $msg);
        fclose($file);
    }


}