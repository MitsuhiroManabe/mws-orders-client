<?php
namespace Kumaneko\MwsOrdersClient\Response;

/**
 * Class Order
 * @package Kumaneko\Response
 * @param string $ShipmentServiceLevelCategory
 * @param string $ShipServiceLevel
 * @param \DateTime|null $EarliestShipDate
 * @param \DateTime|null $LatestShipDate
 * @param string $MarketplaceId
 * @param string $SalesChannel
 * @param string $OrderType
 * @param string $BuyerEmail
 * @param string $BuyerName
 * @param \DateTime|null $LatestUpdateDate
 * @param \DateTime|null $PurchaseDate
 * @param int $NumberOfItemsShipped
 * @param int $NumberOfItemsUnshipped
 * @param string $AmazonOrderId
 * @param string $PaymentMethod
 * @param array $orderItems
 */
class Order extends Response
{
    protected $orderItems = [];

    public function getLastUpdateTime()
    {
        if (empty($this->params['LastUpdateDate'])) {
            return null;
        } else {
            try {
                return new \DateTime($this->params['LastUpdateDate']);
            } catch(\Exception $e) {
                return null;
            }
        }
    }

    public function getPurchaseDate()
    {
        if (empty($this->params['PurchaseDate'])) {
            return null;
        } else {
            try {
                return new \DateTime($this->params['PurchaseDate']);
            } catch(\Exception $e) {
                return null;
            }
        }
    }

    public function getEarliestShipDate()
    {
        if (empty($this->params['EarliestShipDate'])) {
            return null;
        } else {
            try {
                return new \DateTime($this->params['EarliestShipDate']);
            } catch(\Exception $e) {
                return null;
            }
        }
    }

    public function getLatestShipDate()
    {
        if (empty($this->params['LatestShipDate'])) {
            return null;
        } else {
            try {
                return new \DateTime($this->params['LatestShipDate']);
            } catch(\Exception $e) {
                return null;
            }
        }
    }

    public function addOrderItem($orderItem) {
        $this->orderItems[] = $orderItem;
    }
}