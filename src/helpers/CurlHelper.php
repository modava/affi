<?php
/*
 * Implement by Duc Huynh
 * Date: 2020-07-28
 * */

namespace modava\affiliate\helpers;

class CurlHelper {
    var $curl;

    public function __construct($url)
    {
        $this->curl = curl_init();

        curl_setopt_array($this->curl, array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_FOLLOWLOCATION => true,
        ));
    }

    public function setHeader($headers = [])
    {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);
    }

    public function setOPT($option, $value)
    {
        curl_setopt($this->curl, $option, $value);
    }

    public function execute() {
        $response = curl_exec($this->curl);
        $err = curl_error($this->curl);

        return [
            'result' => $response,
            'error' => $err,
        ];
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }
}