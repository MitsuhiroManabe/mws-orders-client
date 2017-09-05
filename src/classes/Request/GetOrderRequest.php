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
        'AmazonOrderId.Id.1' => ['type' => 'string', 'required' => true],
    ];
}
