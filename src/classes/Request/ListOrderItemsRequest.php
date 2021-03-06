<?php
namespace Kumaneko\MwsOrdersClient\Request;

/**
 * Class ListOrderItemsRequest
 * @package Kumaneko\MwsOrdersClient\Request
 * @property string AmazonOrderId
 */
class ListOrderItemsRequest extends Request
{
    protected $operationName = 'ListOrderItems';
    protected $schema = [
        'AmazonOrderId' => ['type' => 'string', 'required' => true],
        ];
}