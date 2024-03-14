<?php

  namespace Core\Network;

  class Curl {
    protected $curlHandler;
    protected $options = [];
    protected $info = [];

    /**
     * @param null $url
     */
    public function __construct($url = null) {
      if (is_string($url)) {
        $this->setUrl($url);
      }
    }


    /**
     * @param string $url
     */
    public function setUrl(string $url) {
      $this->setOption(CURLOPT_URL, $url);
    }


    /**
     * @return bool|string
     * @throws Exception
     */
    public function execute() {
      $this->initCurl();

      $result = curl_exec($this->curlHandler);

      if ($result === false) {
        $message = curl_error($this->curlHandler);
        throw new Exception($message);
      }
      $this->info = curl_getinfo($this->curlHandler);

      $this->closeConnection();
      return $result;
    }


    public function closeConnection() {
      if ($this->curlHandler) {
        curl_close($this->curlHandler);
        $this->curlHandler = null;
      }
    }


    /**
     * @return array
     */
    public function getInfo() {
      return $this->info;
    }


    /**
     * @param int $timeout
     */
    public function setTimeOut(int $timeout) {
      $this->setOption(CURLOPT_TIMEOUT, $timeout);
    }


    /**
     * @param $option
     * @param $value
     */
    public function setOption($option, $value) {
      $this->options[$option] = $value;
    }


    /**
     * @throws Exception
     */
    protected function initCurl() {
      if (($this->curlHandler = curl_init()) === false) {
        throw new Exception('Could not initialize Curl:');
      }
      $this->initCurlOptions();
    }


    protected function initCurlOptions() {
      foreach ($this->options as $option => $value) {
        curl_setopt($this->curlHandler, $option, $value);
      }
    }


    public function __destruct() {
      $this->closeConnection();
    }
  }