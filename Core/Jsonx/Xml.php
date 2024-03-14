<?php

  namespace Core\Jsonx;

  class Xml {
    /**
     * @var \XMLWriter $XmlWriter
     */
    protected $XmlWriter;


    /**
     * @param $data
     *
     * @return mixed
     * @throws Exception
     */
    public function generate($data) {
      try {
        $this->startDocument();
        $XmlWriter = $this->getXmlWriter();

        $this->dataToXml($data, null, true);

        $XmlWriter->endDocument();
        return $XmlWriter->flush();
      }
      catch (\Exception $e) {
        throw new Exception('Failed to generate XML: ' . $e->getMessage(), $e->getCode());
      }
    }


    protected function startDocument() {
      $XmlWriter = $this->getXmlWriter();
      $XmlWriter->openMemory();
      $XmlWriter->startDocument('1.0', 'UTF-8');
    }


    /**
     * @param mixed      $data
     * @param string|null  $name
     * @param boolean $isRoot
     *
     * @throws Exception
     */
    protected function dataToXml($data, $name = null, $isRoot = false) {
      $type = $this->getDataType($data);
      $XmlWriter = $this->getXmlWriter();
      $XmlWriter->startElementNs('json', $type, null);

      if ($isRoot) {
        $this->writeRootInfo();
      }

      if (is_string($name)) {
        $XmlWriter->writeAttribute('name', $name);
      }

      $callback = array($this, $type . 'ToXml');
      if (is_callable($callback)) {
        call_user_func($callback, $data);
      }
      elseif ($type != 'null') {
        $XmlWriter->text($data);
      }

      $XmlWriter->endElement();
    }


    /**
     * @param array|object $data
     */
    protected function objectToXml($data) {
      foreach ($data as $key => $value) {
        $this->dataToXml($value, $key);
      }
    }


    /**
     * @param array $data
     */
    protected function arrayToXml(array $data) {
      foreach ($data as $value) {
        $this->dataToXml($value);
      }
    }

    /**
     * @param bool $data
     */
    protected function booleanToXml(bool $data) {
      $this->getXmlWriter()->text($data ? 'true' : 'false');
    }


    protected function writeRootInfo() {
      $this->XmlWriter->writeAttribute('xmlns:json', 'http://www.ibm.com/xmlns/prod/2009/jsonx');
      $this->XmlWriter->writeAttribute('xsi:schemaLocation', 'http://www.datapower.com/schemas/json jsonx.xsd');
      $this->XmlWriter->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
    }


    /**
     * @param mixed $data
     *
     * @return string
     * @throws Exception
     */
    protected function getDataType($data) {
      if (is_object($data) || $this->isAssocArray($data)) {
        return 'object';
      }
      if (is_array($data)) {
        return 'array';
      }
      if (is_bool($data)) {
        return 'boolean';
      }
      if (is_numeric($data)) {
        return 'number';
      }
      if (is_null($data)) {
        return 'null';
      }
      if (is_string($data)) {
        return 'string';
      }
      throw new Exception('Invalid data type');
    }


    /**
     * @param mixed $data
     *
     * @return bool
     */
    protected function isAssocArray($data) {
      return is_array($data) && count(array_filter(array_keys($data), 'is_string')) > 0;
    }


    /**
     * @return \XMLWriter
     */
    protected function getXmlWriter() {
      if (!$this->XmlWriter instanceof \XMLWriter) {
        $this->XmlWriter = new \XMLWriter();
      }
      return $this->XmlWriter;
    }
  }