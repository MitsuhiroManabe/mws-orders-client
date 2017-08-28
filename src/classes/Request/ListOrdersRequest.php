<?php
namespace Kumaneko\MwsOrdersClient\Request;

/**
 * Class ListOrdersRequest
 * @package Kumaneko\MwsOrdersClient\Request
 * @property \DateTime|null CreatedAfter
 * @property \DateTime|null CreatedBefore
 * @property \DateTime|null LastUpdatedAfter
 * @property \DateTime|null LastUpdatedBefore
 * @property array|null OrderStatus
 * @property string|null FulfillmentChannel
 * @property string|null PaymentMethod
 * @property string|null BuyerEmail
 * @property string|null SellerOrderId
 * @property string|null MaxResultPerPage
 * @property string|null TFMShipmentStatus
 */
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