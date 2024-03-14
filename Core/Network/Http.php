<?php

  namespace Core\Network;


  class Http extends Curl {
    protected $options  = [
      CURLOPT_RETURNTRANSFER => 1,
    ];

    protected $headers = [];


    public function setPostFields(array $data) {
      $this->setPostData(http_build_query($data));
    }


    public function setPostData(string $data) {
      $this->setOption(CURLOPT_POSTFIELDS, $data);
    }


    public function verifySSL($verify = true) {
      $this->setOption(CURLOPT_SSL_VERIFYPEER, $verify);
    }


    public function setContentType(string $type) {
      $this->addHeader('Content-Type', $type);
    }


    public function addHeader(string $header, string $value) {
      $this->headers[$header] = $value;
    }


    protected function initCurlOptions() {
      $this->initHeaderOption();
      parent::initCurlOptions();
    }


    protected function initHeaderOption() {
      $headers = $this->options[CURLOPT_HTTPHEADER] ?? [];
      foreach ($this->headers as $key => $val) {
        $headers[] = $key . ': ' . $val;
      }
      $this->options[CURLOPT_HTTPHEADER] = $headers;
    }
  }