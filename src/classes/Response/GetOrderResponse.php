<?php
namespace Kumaneko\MwsOrdersClient\Response;

class GetOrderResponse extends Response
{
    protected function parseXml($xmlObject)
    {
        $response = [];
        if (! empty($xmlObject->GetOrderResult)) {
            $resultObject = $xmlObject->GetOrderResult;
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
        if ($xmlObject->getName() == 'Orders' && ! empty($xmlObject->Order)) {
            $orderItems = [];
            foreach ($xmlObject->Order as $orderElement) {
                $orderItems[] = new OrderItem($orderElement);
            }
            return ['Orders' => $orderItems];
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