<?php

  namespace Core;

  class Jsonx {

    public static function toXml($input) {
      $Xml = new Jsonx\Xml();

      return $Xml->generate($input);
    }


    public static function jsonToXml(string $input) {
      return self::toXml(Json::decode($input, false));
    }
  }