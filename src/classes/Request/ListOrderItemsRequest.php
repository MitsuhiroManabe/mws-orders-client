<?php
namespace Kumaneko\MwsOrdersClient\Request;


class ListOrderItemsRequest extends Request
{
    protected $operationName = 'ListOrderItems';
    protected $schema = [
        'AmazonOrderId' => ['type' => 'string', 'required' => true],
        ];
}