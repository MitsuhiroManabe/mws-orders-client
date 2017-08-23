<?php
//***アカウント情報を入力して下さい。***
$sellerId = "***セラーID***";
$awsAccessKeyId = "***AWSアクセスキーID***";
$secretKey = "***シークレットキー***";
//3時間前から現在までの未出荷注文を取得する。
$date = new DateTime();
$date->sub(new DateInterval('PT3H'));
$client = new Kumaneko\MwsOrdersClient\Client();
$request = new Kumaneko\MwsOrdersClient\Request\ListOrdersRequest([
    'SellerId' => $sellerId,
    'AWSAccessKeyId' => $awsAccessKeyId,
    'CreatedAfter' => $date,
    'OrderStatus' => ['Status' => ['1' => 'Unshipped', '2' => 'PartiallyShipped']],
    'SecretKey' => $secretKey
]);
$result = $client->listOrdersRequest($request);
$orders = $result->Orders;
//各注文ごとに注文商品情報を取得する。注文情報を表示する。(一部)
foreach ($orders as $order) {
    $request = new \Kumaneko\MwsOrdersClient\Request\ListOrderItemsRequest([
        'SellerId' => $sellerId,
        'AWSAccessKeyId' => $awsAccessKeyId,
        'AmazonOrderId' => $order->AmazonOrderId,
        'SecretKey' => $secretKey
    ]);
    $result = $client->listOrderItemsRequest($request);
    /** @var \Kumaneko\MwsOrdersClient\Response\Order $order */
    $order->OrderItems = $result->OrderItems;

    echo "AmazonOrderId: {$order->AmazonOrderId}\n";
    echo "OrderStatus: {$order->OrderStatus}\n";
    echo "PurchaseDate: {$order->PurchaseDate->format('Y-m-d H:i:s')}\n";
    echo "OrderTotal-Amount: {$order->OrderTotal['Amount']}\n";
    echo "OrderItems: \n";
    foreach ($order->OrderItems as $key => $orderItem) {
        /** @var \Kumaneko\MwsOrdersClient\Response\OrderItem $orderItem */
        echo "  OrderItem{$key}: \n";
        echo "    ASIN: {$orderItem->ASIN}\n";
        echo "    SellerSKU: {$orderItem->SellerSKU}\n";
        echo "    Title: {$orderItem->Title}\n";
        echo "    ItemPrice: {$orderItem->ItemPrice['Amount']}\n";
        echo "    ShippingPrice: {$orderItem->ShippingPrice['Amount']}\n";
    }
    sleep(1);
}
