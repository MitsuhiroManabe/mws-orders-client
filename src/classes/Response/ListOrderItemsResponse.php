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
        if ($xmlObject->getName() == 'OrderItems' && ! empty($xmlObject->OrderItems)) {
            $orderItems = [];
            foreach ($xmlObject->OrderItems as $orderItemElement) {
                $orderItems[] = new OrderItem($orderItemElement);
            }
            return ['OrderItems' => $orderItems];
        }
        return parent::parseNode($xmlObject);
    }
}