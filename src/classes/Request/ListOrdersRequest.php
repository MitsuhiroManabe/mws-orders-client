<?php
namespace Kumaneko\MwsOrdersClient\Request;

class ListOrdersRequest extends Request
{
    protected $operationName = 'ListOrders';
    protected $schema = [
        'CreatedAfter' => ['type' => 'datetime'],
        'CreatedBefore' => ['type' => 'datetime'],
        'LastUpdatedAfter' => ['type' => 'datetime'],
        'LastUpdateBefore' => ['type' => 'datetime'],
        'OrderStatus' => ['type' => 'array',],
        'FulfillmentChannel' => [
            'type' => 'string',
            'options' => [
                'AFN',
                'MFN'
            ],
        ],
        'PaymentMethod' => [
            'type' => 'string',
            'options' => [
                'COD',
                'CVS',
                'Other',
            ],
        ],
        'BuyerEmail' => ['type' => 'string'],
        'SellerOrderId' => ['type' => 'string'],
        'MaxResultPerPage' => ['type' => 'int'],
        'TFMShipmentStatus' => [
            'type' => 'string',
            'options' => [
                'PendingPickUp',
                'LabelCancelled',
                'PickedUp',
                'AtDestinationFC',
                'Delivered',
                'RejectedByBuyer',
                'Undeliverable',
                'ReturnedToSeller',
                'Lost',
            ],
        ],
    ];
}