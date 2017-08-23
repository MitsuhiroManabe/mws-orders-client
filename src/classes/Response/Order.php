<?php
namespace Kumaneko\MwsOrdersClient\Response;

/**
 * Class Order
 * @package Kumaneko\Response
 * @property  string|null ShipmentServiceLevelCategory
 * @property string|null ShipServiceLevel
 * @property \DateTime|null EarliestShipDate
 * @property \DateTime|null LatestShipDate
 * @property string|null MarketplaceId
 * @property string|null SalesChannel
 * @property string|null OrderType
 * @property string|null BuyerEmail
 * @property string|null BuyerName
 * @property \DateTime|null LatestUpdateDate
 * @property \DateTime|null PurchaseDate
 * @property string|null NumberOfItemsShipped
 * @property string|null NumberOfItemsUnshipped
 * @property string|null AmazonOrderId
 * @property string|null PaymentMethod
 * @property array|null OrderItems
 * @property array|null OrderTotal
 * @property array|null ShippingAddress
 * @property array|null PaymentExecutionDetail
 * @property string|null OrderStatus
 *
 */
class Order extends Response
{
    public function setLastUpdateTime($value)
    {
        if (empty($value)) {
            return false;
        } else {
            try {
                return $this->params['LastUpdateTime'] = new \DateTime($value);
            } catch(\Exception $e) {
                return false;
            }
        }
    }

    public function setPurchaseDate($value)
    {
        if (empty($value)) {
            return false;
        } else {
            try {
                return $this->params['PurchaseDate'] = new \DateTime($value);
            } catch(\Exception $e) {
                return false;
            }
        }
    }

    public function setEarliestShipDate($value)
    {
        if (empty($value)) {
            return false;
        } else {
            try {
                return $this->params['EarliestShipDate'] = new \DateTime($value);
            } catch(\Exception $e) {
                return false;
            }
        }
    }

    public function setLatestShipDate($value)
    {
        if (empty($value)) {
            return false;
        } else {
            return $this->params['LatestShipDate'] = new \DateTime($value);

        }
    }

    public function addOrderItem($orderItem)
    {
        $this->params['OrderItems'][] = $orderItem;
    }
}