<?php
namespace Kumaneko\MwsOrdersClient\Response;

class ListOrderItemsResponse extends Response
{
    protected function parseXml($xmlObject)
    {
        $response = [];
        if (! empty($xmlObject->ListOrderItemsResult)) {
            $resultObject = $xmlObject->ListOrderItemsResult;
            /** @var \SimpleXMLElement $resultObject */
            $response['result'] = parent::parseXml($resultObject);
        } else {
            $response['result'] = null;
        }
        if (! empty($xmlObject->ResponseMetadata)) {
            $metaDataObject = $xmlObject->ResponseMetadata;
            /** @var \SimpleXMLElement $metaDataObject */
            $response['metaData'] = parent::parseXml($metaDataObject);
        } else {
            $response['metaData'] = null;
        }
        return $response;
    }

    protected function parseNode($xmlObject)
    {
        if ($xmlObject->getName() == 'OrderItems' && ! empty($xmlObject->OrderItem)) {
            $orderItems = [];
            foreach ($xmlObject->OrderItem as $orderItemElement) {
                $orderItems[] = new OrderItem($orderItemElement);
            }
            return ['OrderItems' => $orderItems];
        }
        return parent::parseNode($xmlObject);
    }

    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName($name);
        } elseif (isset($this->params['result'][$name])) {
            return $this->params['result'][$name];
        } elseif (isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return null;
        }
    }
}