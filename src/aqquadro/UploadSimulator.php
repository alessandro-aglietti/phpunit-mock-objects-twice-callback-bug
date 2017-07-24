<?php

namespace aqquadro;

/**
 * Created by PhpStorm.
 * User: name
 * Date: 24/07/17
 * Time: 12.33
 */

class UploadSimulator
{


    /**
     * UploadSimulator constructor.
     */
    public function __construct()
    {
    }


    /**
     * @param $bucket_name string
     * @param $path string
     * @param $file_resource resource
     * @return bool
     */
    public function upload($bucket_name, $path, $file_resource)
    {
        return true;
    }

    /**
     * from a given resource ID
     * do upload with default values
     * @param $resource_id string
     * @param $auto_close the file resource after upload
     */
    public function upload_simplified($resource_id, $auto_close = true)
    {
        if (empty($resource_id)) {
            throw new \Exception("ResourceID value '${resource_id}' is not valid");
        }
        $default_bucket_name = 'gold_bucket';
        $default_path = "cdn/${resource_id}";
        $default_file_resource = __DIR__ . "/../../resources/${resource_id}.txt";

        if (!file_exists($default_file_resource)) {
            throw new \Exception("Resource ${default_file_resource} doesn't exist");
        }

        $fp = fopen($default_file_resource, 'rb');
        $upload_status = $this->upload($default_bucket_name, $default_path, $fp);

        if ($auto_close) {
            fclose($fp);
        }


        return $upload_status;
    }
}