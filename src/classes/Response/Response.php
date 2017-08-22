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
        $this->params = $this->parseXml($xmlObject);
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
        if ($xmlObject->count()) {
            $result = [];
            foreach ($xmlObject->children() as $childNode) {
                /** @var \SimpleXMLElement $childNode */
                $result[$childNode->getName()] = $this->parseNode($childNode);
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

    public function getParams()
    {
        return $this->params;
    }
}