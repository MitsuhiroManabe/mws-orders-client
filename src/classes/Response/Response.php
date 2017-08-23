<?php
/**
 * Created by PhpStorm.
 * User: mmanabe
 * Date: 2017/08/23
 * Time: 5:34
 */

namespace Kumaneko\MwsOrdersClient\Response;


class Response
{
    protected $params;

    /**
     * Order constructor.
     * @param \SimpleXMLElement $xmlObject
     */
    public function __construct($xmlObject)
    {
        $params = $this->parseXml($xmlObject);
        foreach ($params as $name => $value) {
            $this->__set($name, $value);
        }
    }

    /**
     * @param \SimpleXMLElement $xmlObject
     * @return array
     */
    protected function parseXml($xmlObject)
    {
        $params = [];
        foreach ($xmlObject->children() as $item) {
            /** @var \SimpleXMLElement $item */
            $params += $this->parseNode($item);
        }
        return $params;
    }

    /**
     * @param \SimpleXMLElement $xmlObject
     * @return array
     */
    protected function parseNode($xmlObject)
    {
        if ($xmlObject->count() > 1) {
            $result = [];
            foreach ($xmlObject as $childNode) {
                /** @var \SimpleXMLElement $childNode */
                $result += $this->parseNode($childNode);
            }
            return [$xmlObject->getName() => $result];
        } else {
            return [$xmlObject->getName() => (string)$xmlObject];
        }
    }

    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName($name);
        } elseif (isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return null;
        }
    }

    public function __set($name, $value) {
        $methodName = 'set' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName($value);
        } else {
            return $this->params[$name] = $value;
        }
    }

    public function getParams()
    {
        return $this->params;
    }
}