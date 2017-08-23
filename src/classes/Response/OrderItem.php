<?php
namespace Kumaneko\MwsOrdersClient\Response;

/**
 * Class OrderItem
 * @package Kumaneko\MwsOrdersClient\Response
 * @property string|null ASIN
 * @property string|null SellerSKU
 * @property string|null Title
 * @property array|null ItemPrice
 * @property string|null NextToken
 * @property array|null ShippingPrice
 * @property string|null AmazonOrderId
 * @property string|null QuantityOrdered
 * @property string|null QuantityShipped
 * @property array|null ShippingPrice
 * @property \DateTime|null ScheduledDeliveryEndDate
 * @property \DateTime|null ScheduledDeliveryStartDate
 * @property string|null CODFee
 * @property string|null CODFeeDiscount
 * @property string|null GiftMessageText
 * @property array|null GiftWrapPrice
 * @property string|null GiftWrapLevel
 */
class OrderItem extends Response
{
    public function setScheduledDeliveryEndDate($value)
    {
        if (empty($value)) {
            return false;
        } else {
            try {
                return $this->params['ScheduledDeliveryEndDate'] = new \DateTime($value);
            } catch(\Exception $e) {
                return false;
            }
        }
    }

    public function setScheduledDeliveryStartDate($value)
    {
        if (empty($value)) {
            return false;
        } else {
            try {
                return $this->params['ScheduledDeliveryStartDate'] = new \DateTime($value);
            } catch(\Exception $e) {
                return false;
            }
        }
    }
}