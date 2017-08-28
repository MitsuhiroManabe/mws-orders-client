<?php
namespace Kumaneko\MwsOrdersClient\Request;

/**
 * Class GetOrderRequest
 * @package Kumaneko\MwsOrdersClient\Request
 * @property string AmazonOrderId
 */
class GetOrderRequest extends Request
{
    protected $operationName = 'getOrder';
    protected $schema = [
        'AmazonOrderId' => ['type' => 'string', 'required' => true],
    ];
}
