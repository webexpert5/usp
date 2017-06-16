<?php
defined('ABSPATH') or die('No script kiddies please!');

class Helper_WooFileDropzone
{

    public static function loadView($view, $data = array())
    {
        if (!file_exists(WOO_FILE_DROPZONE_PLUGIN_DIR . "views" . DIRECTORY_SEPARATOR . "{$view}.php")) {
            trigger_error("No View Found in Directory");
        } else {
            extract($data);
            require WOO_FILE_DROPZONE_PLUGIN_DIR . "views" . DIRECTORY_SEPARATOR . "{$view}.php";
        }
    }

    public static function deleteFileFromDisk($fileName)
    {
        if (!file_exists(
            WOO_FILE_DROPZONE_UPLOAD_CONTENT_DIR
            . WOO_FILE_DROPZONE_UPLOAD_FOLDER
            . DIRECTORY_SEPARATOR . $fileName)
        ) {
            Helper_LoggerWooFileDropzone::log(1, "Looking for file to delete that does not exists");
        } else {
            unlink(
                WOO_FILE_DROPZONE_UPLOAD_CONTENT_DIR
                . WOO_FILE_DROPZONE_UPLOAD_FOLDER
                . DIRECTORY_SEPARATOR . $fileName);
        }
    }

    //cron job to delete files and table entries in table wc_file_dropzone that
    // are abandoned by users
    public static function cronGarbageCollection(){
        $model = new Model_WooFileDropzone();
        $model->cronCleanExpiredSessionEntriesWithFiles();
    }
}