<?php
defined('ABSPATH') or die('No script kiddies please!');

class Model_WooFileDropzone
{

    private $wpdb;
    private $dbPrefix;
    private $tableName;


    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->dbPrefix = $this->wpdb->prefix;
        $this->tableName = $this->dbPrefix . "woo_file_dropzone";
    }

    public function saveOptions($data)
    {
        delete_option('woo_file_dropzone_options');
        update_option('woo_file_dropzone_options', $data);
    }

    public function getOptions()
    {
        return get_option('woo_file_dropzone_options');
    }

    public function saveUploadedImages($data)
    {
        $records = array();
        if ( $data['variation_id'] !== 0) {
            $records = $this->getFileDetailsByVariationId($data['variation_id'], $data['session_id']);
        } else {
            $data['variation_id'] = 0;
            $records = $this->getFileDetailsByProductId($data['product_id'], $data['session_id']);
        }
        if (!empty($records[0])) {
            $arrOne = unserialize($records[0]->file_urls);
            $arrTwo = unserialize($data['file_urls']);
            $fileURLs = array_merge($arrOne, $arrTwo);
            $data['file_urls'] = serialize($fileURLs);


            $this->wpdb->update($this->tableName,
                $data,
                array(
                    'session_id' => $data['session_id'],
                    'variation_id' => $data['variation_id'],
                    'product_id' => $data['product_id']
                )
            );

        } else {
            $this->wpdb->insert($this->tableName, $data);
        }
    }


    public function generateUniqueSessionID()
    {
        return sha1(mt_rand(1, 9999) . "IndusDolphin" . uniqid()) . time();
    }

    public function updateOrderStatusOnPayment($sessionID, $order_id)
    {
        $this->wpdb->update($this->tableName,
            array(
                'order_id' => $order_id,
                'order_status' => 'complete'
            ),
            array('session_id' => $sessionID)
        );
    }

    //Used for admin side
    public function getOrderDetails($order_id)
    {
        $records = $this->wpdb->get_results("SELECT * FROM {$this->tableName} WHERE order_id={$order_id}");
        $response = array();
        if (!is_null($records)) {

            foreach ($records as $row) {
                $_tempResponse = array();
                $_tempResponse['id'] = $row->id;
                $fileNames = unserialize($row->file_urls);
                $_tempFileNames = array();
                foreach ($fileNames as $fileName) {
                    $_tempFileNames[] = $fileName;
                }
                $_tempResponse['files'] = $_tempFileNames;
                $_tempResponse['product_id'] = $row->product_id;
                $_tempResponse['variation_id'] = $row->variation_id;

                $options = $this->getOptions();
                $fields = unserialize($row->extra_fields);
                $_tempFields = array();
                if (count($fields)) {
                    foreach ($fields as $fieldName => $fieldValue) {
                        foreach ($options['fields'] as $fieldArray) {
                            if (strcmp($fieldArray['fieldName'], $fieldName) === 0) {
                                //utf8 safe from associative keys
                                $_tempFields[] = array(
                                    $fieldArray['fieldLabel'],
                                    $fieldValue
                                );
                            }
                        }
                    }
                    $_tempResponse['fields'] = $_tempFields;
                } else {
                    $_tempResponse['fields'] = array();
                }
                $response[] = $_tempResponse;
            }
        }
        return $response;
    }

    public function getFileDetailsByProductId($product_id, $sessionID)
    {
        return $this->wpdb->get_results("SELECT * FROM {$this->tableName} WHERE product_id={$product_id} AND session_id='{$sessionID}'");
    }

    public function getFileDetailsByVariationId($variation_id, $sessionID)
    {
        return $this->wpdb->get_results("SELECT * FROM {$this->tableName} WHERE variation_id={$variation_id} AND session_id='{$sessionID}'");
    }

    public function getFileDetailsBySessionId($sessionID)
    {
        return $this->wpdb->get_results("SELECT * FROM {$this->tableName} WHERE session_id='{$sessionID}'");
    }

    // Delete file and entries
    public function collectGarbageByVariationId($variation_id, $sessionID)
    {
        $results = $this->wpdb->get_results("SELECT * FROM {$this->tableName} WHERE variation_id = {$variation_id} AND order_status IS NULL AND session_id='{$sessionID}'");

        if ($results) {
            //Remove all files
            foreach ($results as $row) {
                $fileNames = unserialize($row->file_urls);
                foreach ($fileNames as $file) {
                    Helper_WooFileDropzone::deleteFileFromDisk($file);
                }
            }
            // Delete entries
            $this->wpdb->delete($this->tableName, array(
                'variation_id' => $variation_id,
                'session_id' => $sessionID
            ));
        }

    }

    //Delete files and entries
    public function collectGarbageByProductId($product_id, $sessionID)
    {
        $results = $this->wpdb->get_results("SELECT * FROM {$this->tableName} WHERE product_id={$product_id} AND order_status IS NULL AND session_id='{$sessionID}'");
        if ($results) {

            //Remove all files
            foreach ($results as $row) {
                $fileNames = unserialize($row->file_urls);
                foreach ($fileNames as $file) {
                    Helper_WooFileDropzone::deleteFileFromDisk($file);
                }
            }
            // Delete entries
            $this->wpdb->delete($this->tableName, array(
                'product_id' => $product_id,
                'session_id' => $sessionID
            ));
        }
    }

    // Delete files and entries
    public function collectGarbageByOrderId($order_id)
    {
        $results = $this->wpdb->get_results("SELECT * FROM {$this->tableName} WHERE order_id={$order_id}");
        if ($results) {
            //Remove all files
            foreach ($results as $row) {
                $fileNames = unserialize($row->file_urls);
                foreach ($fileNames as $file) {
                    Helper_WooFileDropzone::deleteFileFromDisk($file);
                }
            }
            // Delete entries
            $this->wpdb->delete($this->tableName, array(
                'order_id' => $order_id
            ));
        }
    }

    // Delete files and entries
    public function collectGarbageBySessionId($sessionID)
    {
        $results = $this->wpdb->get_results("SELECT * FROM {$this->tableName} WHERE session_id='{$sessionID}'");
        if ($results) {
            //Remove all files
            foreach ($results as $row) {
                $fileNames = unserialize($row->file_urls);
                foreach ($fileNames as $file) {
                    Helper_WooFileDropzone::deleteFileFromDisk($file);
                }
            }
            // Delete entries
            $this->wpdb->delete($this->tableName, array(
                'session_id' => $sessionID
            ));
        }
    }

    //Admin side delete file
    public function deleteFileByFileName($id, $fileName)
    {
        $result = $this->wpdb->get_row("SELECT * FROM {$this->tableName} WHERE id={$this->wpdb->_escape($id)}");

        $files = unserialize($result->file_urls);
        $tempFiles = $files;
        foreach ($files as $file) {
            if (strcmp($file, $fileName) === 0) {
                if (($key = array_search($fileName, $tempFiles)) !== false) {
                    unset($tempFiles[$key]);
                }
            }
        }

        Helper_WooFileDropzone::deleteFileFromDisk($fileName);

        // if all files are deleted and there is no extra fields
        // then delete entries too
        $fields = unserialize($result->extra_fields);
        if (count($fields) < 1) {
            if (count($tempFiles) < 1) {
                $this->wpdb->delete($this->tableName, array(
                    'id' => $id
                ));

                return true;
            }
        }

        $this->wpdb->update($this->tableName,
            array(
                'file_urls' => serialize($tempFiles)
            ),
            array(
                'id' => $id
            ));
    }


    /**
     * Cron job garbage collection
     * Get all entries whose order is null and session is expired
     */
    public function cronCleanExpiredSessionEntriesWithFiles()
    {
        $currentTime = time();
        $results = $this->wpdb->get_results("SELECT * FROM {$this->tableName} WHERE ((CAST(session_expiry_time as UNSIGNED) < $currentTime) AND order_status IS NULL)");
        if ($results) {
            $entriesIDs = array();
            //Remove all files
            foreach ($results as $row) {
                $entriesIDs[] = $row->id;
                $fileNames = unserialize($row->file_urls);
                foreach ($fileNames as $file) {
                    Helper_WooFileDropzone::deleteFileFromDisk($file);
                }
            }

            foreach ($entriesIDs as $id) {
                // Delete entries
                $this->wpdb->delete($this->tableName, array(
                    'id' => $id
                ));
            }

        }
    }

}