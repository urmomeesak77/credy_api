<?php

  require_once "Core/Autoloader.php";

  $Loader = new Core\Autoloader();
  $Loader->register();


  $timestamp = time();
  $arr =[
    'first_name' => 'Urmo',
    'last_name' => 'Meesak',
    'email' => 'urmo.meesak@gmail.com',
    'bio' => '',
    'technologies' => [],
    'timestamp' => $timestamp,
    'signature' => sha1($timestamp . 'credy'),
    'vcs_uri' => 'https://github.com/',
  ];

  $xml = Core\Jsonx::toXml($arr);
  #$Http = new Core\Network\Http('https://cv.microservices.credy.com/v1');
  $Http = new Core\Network\Http('localhost');

  $Http->setPostData($xml);
  $Http->setContentType('application/xml');
  $Http->verifySSL(false);
  $result = $Http->execute();

  echo $result;
