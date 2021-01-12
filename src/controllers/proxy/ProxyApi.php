<?php


namespace Eejay\Controllers\Proxy;

use Eejay\Controllers\Api;

class ProxyApi extends Api
{

    /**
     * Original SimSimi settings for generating workshop API for v1.1.
     */
    const APP_KEY = 'AIzaSyA9dhp5-AKka4EtVGO_JBG7bM8mplA0WlE';
    const GOOGLEAPI_SIGNUP_URL = 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/signupNewUser';
    const WORKSHOP_INFO = 'https://workshop.simsimi.com/api/project/info';
    const WORKSHOP_USER = 'https://workshop.simsimi.com/api/user';
    const WORKSHOP_PROJECT = 'https://workshop.simsimi.com/api/project';
    const WORKSHOP_ENABLE = 'https://workshop.simsimi.com/api/project/enable';
    const TALK_API = 'https://wsapi.simsimi.com/190410/talk';

    /**
     * PUID for generating workshop API.
     * Not active in this version.
     *
     * @var string
     */
    private $puid;

    /**
     * Workshop API key.
     * Not active in this version.
     *
     * @var string
     */
    private $apiKey;

    /**
     * ProxyApi constructor.
     *
     * @param $proxy
     */
    public function __construct($proxy)
    {
        parent::__construct($proxy);
    }

    /**
     * Get reply from original SimSimi.
     *
     * @param $utext
     * @param $lang
     * @param $apiKey
     * @return mixed
     */
    protected function getReply($utext, $lang, $apiKey)
    {
        $data = array(
            'utext' => $utext,
            'lang' => $lang,
            'atext_bad_prob_max' => 1
        );

        $response = $this->curl->request(self::TALK_API, $data, null, $apiKey);
        $json = $response[1];

        return $json;
    }

    /**
     * Check for remaining trials in v1.1.
     * Not active in this version.
     *
     * @param $puid
     * @return bool
     */
    protected function checkRemainingRequest($puid)
    {
        $workshopApi = self::WORKSHOP_INFO . '?puid=' . $puid;
        $response = $this->curl->request($workshopApi);
        $remain = intval(json_decode($response[1])->remaining);

        return $remain >= 10;
    }

    /**
     * Confirm UUID in v1.1.
     * Not active in this version.
     *
     * @param $uuid
     * @param $email
     */
    private function confirmUuid($uuid, $email)
    {
        $data = array(
            'uuid' => $uuid,
            'email' => $email
        );

        $this->curl->request(self::WORKSHOP_USER, $data);
    }

    /**
     * Create temporary account for new workshop API in v1.1.
     * Not active in this version.
     *
     * @return mixed
     */
    protected function requestNewToken()
    {
        $data = array(
            'email' => 'vitteo' . rand(100, 10000000000) . '@gmail.com',
            'password' => '123qweA@',
            'returnSecureToken' => true
        );
        $response = $this->curl->request(self::GOOGLEAPI_SIGNUP_URL . '?key=' . self::APP_KEY, $data);
        $statusCode = $response[2];

        if ($statusCode === 400) {
            $this->requestNewToken();
        }

        $json = json_decode($response[1]);
        $uuid = $json->localId;
        $email = $json->email;
        $this->confirmUuid($uuid, $email);

        return $uuid;
    }

    /**
     * Enable project to use workshop API in v1.1.
     * Not active in this version.
     */
    public function enableProject()
    {
        $data = array(
            'puid' => $this->puid
        );
        $this->curl->request(self::WORKSHOP_ENABLE, $data, 'PUT');
    }

    /**
     * Get PUID and API key.
     * Not active in this version.
     *
     * @param $uuid
     */
    protected function getAppInfo($uuid)
    {
        $response = $this->curl->request(self::WORKSHOP_PROJECT . '?uuid=' . $uuid);

        $body = $response[1];
        $json = trim($body, '[');
        $json = trim($json, ']');
        $json = json_decode($json);

        $this->puid = $json->puid;
        $this->apiKey = $json->apiKey;

        $this->enableProject();
    }

}