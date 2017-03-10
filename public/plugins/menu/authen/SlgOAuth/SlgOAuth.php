<?php

class SlgOAuth {

    public $client_id = 0;
    public $client_secret = 0;
    public $redirect_uri = 0;
    public $response_type = 'code';

    const AUTHORIZE_URL = 'http://id.slg.vn/oauth/authorize';
    const REGISTER_URL = 'http://id.slg.vn/oauth/register';
    const ACCESS_TOKEN_URL = 'http://id.slg.vn/oauth/access_token';
    const API_URL = 'http://api.slg.vn';

    public function __construct($client_id, $client_secret, $redirect_uri) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        echo 'test';
    }

    public function getLoginUrl() {
        if (empty($this->client_id) || empty($this->client_secret) || empty($this->redirect_uri)) {
            return FALSE;
        }
        $url = self::AUTHORIZE_URL . '?client_id=' . $this->client_id .
                '&redirect_uri=' . $this->redirect_uri .
                '&response_type=' . $this->response_type;
        return $url;
    }

    public function getRegisterUrl() {
        if (empty($this->client_id) || empty($this->client_secret) || empty($this->redirect_uri)) {
            return FALSE;
        }
        $url = self::REGISTER_URL . '?client_id=' . $this->client_id .
                '&redirect_uri=' . $this->redirect_uri .
                '&response_type=' . $this->response_type;
        return $url;
    }

    public function getAccessToken($code) {
        if (empty($code)) {
            return FALSE;
        }
        try {
            $data = array(
                'grant_type' => 'authorization_code',
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'redirect_uri' => $this->redirect_uri,
                'code' => $code,
            );

            return $this->postData(self::ACCESS_TOKEN_URL, $data);
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }
    }

    public function callApi($type, $api, $token, $data = 0) {
        $reponse = array();
        $type = strtoupper($type);

        if (empty($type) || empty($api) || empty($token)) {
            return FALSE;
        }
        $api_url = self::API_URL . '/' . $api . '?access_token=' . $token;

        switch ($type) {
            case 'GET':
                $reponse = $this->getData($api_url);
                break;
            case 'POST':
                $reponse = $this->postData($api_url, $data);
        }
        return $reponse;
    }

    public function postData($url, $data) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        return json_decode($server_output);
    }

    public function getData($url, $header = 0) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);

        curl_close($ch);
        return json_decode($output);
    }

}

?>