<?php


namespace Eejay\Controllers;


class Request
{
    /**
     * Proxy setting.
     *
     * @var bool|mixed
     */
    private $_proxy;

    /**
     * Request constructor.
     * @param $proxy
     */
    public function __construct($proxy)
    {
        $this->_proxy = $proxy;
    }

    /**
     * Send HTTP request and get response.
     *
     * @param $url
     * @param null $data
     * @param null $method
     * @param null $apiKey
     * @return array
     */
    public function request($url, $data = null, $method = null, $apiKey = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        if (isset($this->_proxy)) {
            curl_setopt($ch, CURLOPT_PROXY, $this->_proxy);
        }

        // For POST
        if (isset($data) && !empty($data)) {
            $contentType = array('Content-Type: application/json');
            if ($method === 'PUT') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $contentType);
            } else {
                $headers = array(
                    'Content-type: application/json',
                    'x-api-key: ' . $apiKey,
                );
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            }

            $data = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'simpsimp-browser');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $header = substr($response, 0, $header_size);
        $body = substr($response, $header_size);

        $retVal = array($header, $body);
        array_push($retVal, $statusCode);

        return $retVal;
    }
}