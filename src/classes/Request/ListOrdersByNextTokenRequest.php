<?php
namespace Kumaneko\MwsOrdersClient\Request;

/**
 * Class ListOrdersByNextTokenRequest
 * @package Kumaneko\MwsOrdersClient\Request
 * @property string NextToken
 */
class ListOrdersByNextTokenRequest extends Request
{
    protected $operationName = 'ListOrdersByNextToken';
    protected $schema = [
        'NextToken' => ['type' => 'string', 'required' => true]
    ];
}