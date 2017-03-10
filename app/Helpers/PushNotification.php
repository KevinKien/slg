<?php

namespace App\Helpers;

use cURL;

class PushNotification
{
    private $server = "https://android.googleapis.com/gcm/send";
    private $key = "AIzaSyAZDFbd5BwEqYjU1y0nXWbtAOGcXYPKxik";

    /**
     * Push a message to one or many device(s)
     *
     * Example $data = array("msg"=>"Hello world! This is a notification","ttl"=>"My Application Name","id"=>"100");
     * Example $registration_ids = array("id1","id2","id3");
     *
     * @param array $registration_ids
     * @param array $data
     *
     * @return string json
     */
    public function pushMessageToManyDevices($registration_ids = array(), $data = array()) {
        $_headers = array(
            "Content-Type:application/json",
            "Authorization:key=".$this->key,
        );

        $_data = array(
            "registration_ids" => $registration_ids,
            "data" => $data
        );

        return $this->send($_headers, json_encode($_data));
    }

    /**
     * Push a message to one device
     *
     * Example $data = array("message"=>"Hello world! This is a notification","title"=>"My Application Name","id"=>"100");
     * Example $registration_id = "id1";
     *
     * @param string $registration_id
     * @param array $data
     *
     * @return string
     */
    public function pushMessageToOneDevice($registration_id, $data) {
        $_headers = array(
            "Content-Type:application/x-www-form-urlencoded;charset=UTF-8",
            "Authorization:key=".$this->key
        );

        $_data = "";
        foreach( $data as $key => $val ) {
            $_data .= "data." . $key . "=" . $val . "&";
        }
        $_data .= "registration_id=" . $registration_id;

        return $this->send($_headers, $_data);
    }

    /**
     * Send POST request to server google
     *
     * @return string JSON or String
     */
    public function send($headers, $data) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->server);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    public static function sendMessageIOS($device_tokens, $payload)
    {
//        $deviceToken =$token;
//        $passphrase = 'pushchat';
//        $apns_cert=drupal_get_path('module','backoffice_mobile').'/ck.pem';
//        $ctx = stream_context_create();
//        stream_context_set_option($ctx, 'ssl', 'local_cert', $apns_cert);
//        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
//        $fp = stream_socket_client(
//            'ssl://gateway.push.apple.com:2195', $err,
//            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
//
//        if (!$fp)
//            exit("Failed to connect: $err $errstr" . PHP_EOL);
//
//        echo 'Connected to APNS</br>';
//
//        // Create the payload body
//        $body['aps'] = array(
//            'alert' => $message,
//            'sound' => 'default',
//            "badge"=>  1
//        );
//
//        // Encode the payload as JSON
//        $payload = json_encode($body);
//
//        // Build the binary notification
//        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
//
//        // Send it to the server
//        $result = fwrite($fp, $msg, strlen($msg));
//
//        if (!$result)
//            echo 'Message not delivered' . PHP_EOL;
//        else
//            echo 'Message successfully delivered:' . $deviceToken;
//
//        // Close the connection to the server
//        fclose($fp);

    }
}