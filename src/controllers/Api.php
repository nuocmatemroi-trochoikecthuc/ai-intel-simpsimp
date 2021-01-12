<?php


namespace Eejay\Controllers;

use Eejay\Controllers\Proxy\ProxyApi;
use Eejay\Data\Database;

class Api
{

    /**
     * Proxy setting.
     *
     * @var bool|mixed
     */
    private $_proxy;

    /**
     * The Simsimi API from its own developer.
     *
     * @var ProxyApi
     */
    private $api;

    /**
     * Database model.
     *
     * @var Database
     */
    private $database;

    /**
     * The request controller.
     *
     * @var Request
     */
    protected $curl;

    /**
     * Custom setting for handling object's destruction.
     * This option is for developers who wish to develop my project.
     *
     * @var bool
     */
    private $custom = false;

    /**
     * The API controller.
     * This option is for developers who wish to develop my project.
     *
     * @var mixed
     */
    private $object;

    /**
     * The method that handles object's destruction.
     * This option is for developers who wish to develop my project.
     *
     * @var
     */
    private $name;

    /**
     * The self-destruct method's configurations.
     * This option is for developers who wish to develop my project.
     *
     * @var mixed
     */
    private $config;

    /**
     * Type should be 'database' or something to support detection
     * when to do garbage cleaning or closing database connection
     *
     * @var mixed
     */
    private $type = null;

    /**
     * Api constructor.
     *
     * @param $proxy
     */
    public function __construct($proxy)
    {
        $this->_proxy = isset($proxy) ? $proxy : false;
        $this->curl = new Request($this->_proxy);
        $this->database = new Database();
    }

    /**
     * Get Simsimi's API from user custom config.php .
     *
     * @param $file
     * @return mixed
     */
    private function getApiConfig($file)
    {
        if (preg_match('/(php|file|glob|data|ftp|zip|compress.zlib|ftps|phar):\/\//im', $file)) {
            die('disabled wrapper');
        }
        if (preg_match('/(tmp|sess|log|data|\.\.|www)/im', $file)) {
            die('i was hacked by this, so no more');
        }
        return require $file;
    }

    /**
     * Chat with my SimpSimp.
     *
     * @param $apiConfig
     * @param $utext
     * @param $lang
     * @return false|mixed|string
     */
    public function chat($apiConfig, $utext, $lang)
    {
        $reply = $this->database->getAnswer($utext);
        if (!empty($reply)) {
            return json_encode(array(
                'atext' => $reply
            ));;
        } else {
            $this->api = new ProxyApi($this->_proxy);
            if (strlen($utext) >= 100)
                return json_encode(array(
                    'atext' => 'make it shorter than 100 please, dai qua luoi doc, chat it it thoi'
                ));

            $data = $this->getApiConfig($apiConfig);
            $puid = key($data);
            $apiKey = $data[$puid];

            return $this->api->getReply($utext, $lang, $apiKey);
        }
    }

    /**
     * Teach my SimpSimp to be nicer.
     *
     * @param $set
     * @return false|string
     */
    public function teach($set)
    {
        $remoteIp = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';
        if ($this->database->store($remoteIp, urlencode($set)) !== 1) {
            return json_encode(array(
                'status' => 'something went wrong'
            ));
        }

        $data = unserialize(urldecode($set));

        foreach ($data as $question => $answer) {
            if ($this->database->insertSet($question, $answer) !== 1) {
                return json_encode(array(
                    'status' => 'something went wrong'
                ));
            }
        }
        return json_encode(array(
            'status' => 'okay i learned that'
        ));
    }

    /**
     * Custom object's destruction for arbitrary object.
     *
     * @return mixed
     */
    private function destroy($type)
    {
        return $this->object->{$this->name}(
            $this->config,
            $type,
            $optionals = array(
                'check' => $this->custom
            )
        );
    }

    /**
     * Custom handler for object's destruction.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->custom) {
            $type = $this->type === 'database' ? $this->type : null;
            $this->destroy($type);
        }
    }

    /**
     * Initialize ProxyApi for v1.
     * Not active in this version.
     *
     * @return ProxyApi
     */
    private function initApi()
    {
        return new ProxyApi($this->_proxy);
    }

}