<?php
namespace Kumaneko\MwsOrdersClient\Request;

class ListOrdersByNextTokenRequest extends Request
{
    protected $operationName = 'ListOrdersByNextToken';
    protected $schema = [
        'NextToken' => ['type' => 'string', 'required' => true]
    ];
}