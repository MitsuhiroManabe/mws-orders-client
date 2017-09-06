<?php
namespace Kumaneko\MwsOrdersClient\Request;

/**
 * Class GetOrderRequest
 * @package Kumaneko\MwsOrdersClient\Request
 * @property string AmazonOrderId
 */
class GetOrderRequest extends Request
{
    protected $operationName = 'GetOrder';
    protected $schema = [
        'AmazonOrderId' => ['type' => 'array', 'required' => true],
    ];
}
