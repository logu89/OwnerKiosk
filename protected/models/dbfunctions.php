<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of functions
 *
 * @author ifthekar
 */
require_once Yii::app()->basePath . '/extensions/phpqrcode/qrlib.php';

class dbfunctions {

    public $home_push_api_key = "AIzaSyCL6jovbQFqVC1Z5NywxG8pdLCAFGfDoFY";
    public $gcm_home = "APA91bFxDrpzVWAy6c_UTiBpAgDN662vpxFAB2Cxa5shl1kLwTb88W4nkV2xqWbCuAq2nyHV4wkM20zAaxWZ8AKaNAl9n6xe7iLi3Qj1nvpSMePZJQZ56mTSiirBwY5HvSmEdQMnGL6q";
    public $owner_push_api_key = " AIzaSyDkBwMmcmGK5ZefIHXqd8_KAeIrd72mG3Q";
    public $owner_gcm = "APA91bEIrXNEkIyYG_6QfBjULKdd6oQitCzpY-0fACjo3j02xH30H3THpmCg10jtsCmzoTP9o441B8MQAFtqvgGO-eofCUt3157HEX1ba-Lo0yEqhNpVt2moqXqN97bz6xaUDpTXy8Nm-ZanHn4c378o5jcZYaV8dg";

    function sendGoogleCloudMessage($data, $ids, $a1pi_keyset) {
        //------------------------------
        // Replace with real GCM API 
        // key from Google APIs Console
        // 
        // https://code.google.com/apis/console/
        //------------------------------

        $apiKey = $a1pi_keyset;

        //------------------------------
        // Define URL to GCM endpoint
        //------------------------------

        $url = 'https://android.googleapis.com/gcm/send';

        //------------------------------
        // Set GCM post variables
        // (Device IDs and push payload)
        //------------------------------

        $post = array(
            'registration_ids' => $ids,
            'data' => $data,
        );

        //------------------------------
        // Set CURL request headers
        // (Authentication and type)
        //------------------------------

        $headers = array(
            'Authorization: key=' . $apiKey,
            'Content-Type: application/json'
        );

        //------------------------------
        // Initialize curl handle
        //------------------------------

        $ch = curl_init();

        //------------------------------
        // Set URL to GCM endpoint
        //------------------------------

        curl_setopt($ch, CURLOPT_URL, $url);

        //------------------------------
        // Set request method to POST
        //------------------------------

        curl_setopt($ch, CURLOPT_POST, true);

        //------------------------------
        // Set our custom headers
        //------------------------------

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        //------------------------------
        // Get the response back as 
        // string instead of printing it
        //------------------------------

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //------------------------------
        // Set post data as JSON
        //------------------------------

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

        //------------------------------
        // Actually send the push!
        //------------------------------

        $result = curl_exec($ch);

        //------------------------------
        // Error? Display it!
        //------------------------------

        if (curl_errno($ch)) {
            echo 'GCM error: ' . curl_error($ch);
        }

        //------------------------------
        // Close curl handle
        //------------------------------

        curl_close($ch);

        //------------------------------
        // Debug GCM response
        //------------------------------

        return $result;
    }

//put your code here
    public function test() {
        $response = array("status" => 1, "message" => "Hello World", "errorcode" => 0);
        return $response;
    }

    function generateQrSpec($post_arr) {
        $api_response = array("status" => 0, "message" => "Error Occured.Try again", "errorcode" => "404");
        $key = $post_arr['message'];
        $filename = time() . ".png";
        QRcode::png($key, Yii::app()->basePath . "/../uploads/" . $filename);
        $response = array();
        $response["status"] = '1';
        $response["message"] = 'Qr Image generated';
        $response['qrurl'] = Yii::app()->getbaseUrl(true) . '/uploads/' . $filename;
        $response['errorcode'] = '0';
        $api_response = $response;
        return $api_response;
    }

    function alert() {
        $api_response = array("status" => 0, "message" => "Error Occured.Try again", "errorcode" => "404");

        // print_r(json_encode($door_ctrl));
        $msg = array("msg" => "1");


        $gcm_data = array("message" => $msg);
        $gcm_result = json_decode($this->sendGoogleCloudMessage($gcm_data, array($this->owner_gcm), $this->owner_push_api_key));
        if ($gcm_result != null && $gcm_result->success) {
            $response = array();
            $response["status"] = '1';
            $response["message"] = 'Alert send';
            $response['errorcode'] = '0';
            $api_response = $response;
        }
        return $api_response;
    }

    function doorControl($postarr) {
        $api_response = array("status" => 0, "message" => "Error Occured.Try again", "errorcode" => "404");
        $door_ctrl = array("doorstatus" => $postarr['flag']);
        // print_r(json_encode($door_ctrl));
        $door_status = array("0" => "Closed", "1" => "Opened");


        $gcm_data = array("message" => $door_ctrl);
        $gcm_result = json_decode($this->sendGoogleCloudMessage($gcm_data, array($this->gcm_home), $this->home_push_api_key));
        if ($gcm_result != null && $gcm_result->success) {
            $response = array();
            $response["status"] = '1';
            $response["message"] = 'Door step updated to ' . $door_status[$postarr['flag']];
            $response['errorcode'] = '0';
            $api_response = $response;
        }
        return $api_response;
    }

    function generateCustomerAccessKey($post_arr) {
        $api_response = array("status" => 0, "message" => "Error Occured.Try again", "errorcode" => "404");
        $key = $this->generateRandomString(9);
        $filename = time() . ".png";
        QRcode::png($key, Yii::app()->basePath . "/../uploads/" . $filename);
        $expiry_time = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + ' . $post_arr['validity'] . ' days'));

        $qrkey = new qrkey();
        $qrkey->key_value = $key;
        $qrkey->key_url = $filename;
        $qrkey->key_expirytime = $expiry_time;
        $qrkey->key_createdtime = date("Y-m-d H:i:s");
        if ($qrkey->save(false)) {
            $response = array();
            $response["status"] = '1';
            $response["message"] = 'Qr Code generated';
            $response['keyid'] = $qrkey->key_id;
            $response['keyurl'] = Yii::app()->getbaseUrl(true) . '/uploads/' . $qrkey->key_url;
            $response['keyvalue'] = $qrkey->key_value;
            $response['expirytime'] = (string) strtotime($qrkey->key_expirytime);
            $response['errorcode'] = '0';
            $gcm_data = array("message" => $response);
            $gcm_result = json_decode($this->sendGoogleCloudMessage($gcm_data, array($this->gcm_home), $this->home_push_api_key));

            if ($gcm_result != null && $gcm_result->success) {
                $api_response = $response;
            }
        }



// Yii::app()->baseUrl
        return $api_response;
    }

    public function resizeImage($file, $w, $h, $crop = FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width - ($width * abs($r - $w / $h)));
            } else {
                $height = ceil($height - ($height * abs($r - $w / $h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w / $h > $r) {
                $newwidth = $h * $r;
                $newheight = $h;
            } else {
                $newheight = $w / $r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        return $dst;
    }

    public function getFrame() {
        $att_response = array(
            'status' => "0",
            'link' => '',
            'filename' => '',
            'message' => 'No attachment found',
            "errorcode" => "404"
        );

        $attach_record = camera::model()->findByPk(1);
        if (count($attach_record) > 0) {

            $att_response['status'] = "1";
            $att_response['link'] = $attach_record->camera_filepath;
            $att_response['filename'] = $attach_record->camera_filename;
            $att_response['message'] = "1 Attachment found";
            $att_response['errorcode'] = "0";
        }

        return $att_response;
    }

    public function updateFrame($postarr) {

        $filename = $postarr["filename"];
        $type = "image";
        $file = $_FILES["file"];
        $att_response = array(
            'status' => "0",
            'link' => '',
            'id' => '',
            "errorcode" => "304",
            'message' => 'No attachment added'
        );

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $file_name = "frame1" . "." . $ext;
        if (move_uploaded_file($file["tmp_name"], "uploads/" . $file_name)) {

            $file_url = Yii::app()->getBaseUrl(true) . "/uploads/" . $file_name;
            $attachment = array();
            //$attachment->camera_id = 1;
            $attachment['camera_attachtype'] = $type;
            $attachment['camera_filepath'] = $file_url;
            $attachment['camera_filename'] = $filename;
            $res = camera::model()->updateByPK(1, $attachment);
            if ($res) {
                $att_response['message'] = "1 Attachment added";
                $att_response['id'] = "1";
                $att_response['link'] = $file_url;
                $att_response['status'] = "1";
                $att_response['errorcode'] = "0";
            } else {
                $att_response['message'] = "Attachments failed.Please try again";
            }
        } else {
            $att_response['message'] = "Image upload failed.Please try again";
        }

        return $att_response;
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function checkUnique($column_name, $field_value, $table_name) {
        $checkunique_response = array("status" => 0, "message" => "Error Occured.Try again");

        //for getting obj for particular class using name
        $model = $this->getObjectForClass($table_name);
        $check_response = $model->findByAttributes(array($column_name => $field_value));

        //for getting primarykey
        $primarykey_name = $model->tableSchema->primaryKey;

        if (isset($check_response[$primarykey_name])) {
            $checkunique_response['status'] = -1;
            $checkunique_response['message'] = "Field  with same value exist.";
        } else {
            $checkunique_response['status'] = 1;
            $checkunique_response['message'] = "Success.Field not found.";
        }
        return $checkunique_response['status'];
    }

}
