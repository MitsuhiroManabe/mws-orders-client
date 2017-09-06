MWS注文APIを利用して、注文情報を取得するライブラリです。

ListOrdersオペレーションはOrderStatusを指定しない限り、保留中を含む全ての注文を返します。
またOrderStatusを指定する場合、UnshippedとPartiallyShippedはどちらか片方だけを指定することはできず、
両方を指定することのみが可能です。

*サンプルコード1*

ListOrders, ListOrderItems オペレーションを利用して、直近3時間の注文情報を取得する。
    
    //***アカウント情報***
    $sellerId = "***セラーID***";
    $awsAccessKeyId = "***AWSアクセスキーID***";
    $secretKey = "***シークレットキー***";
    //3時間前から現在までの注文を取得する。
    $date = new DateTime();
    $date->sub(new DateInterval('PT3H'));
    $client = new Kumaneko\MwsOrdersClient\Client();
    $request = new Kumaneko\MwsOrdersClient\Request\ListOrdersRequest([
        'SellerId' => $sellerId,
        'AWSAccessKeyId' => $awsAccessKeyId,
        'CreatedAfter' => $date,
        'OrderStatus' => 'Unshipped',
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
    
*サンプルコード2*

GetOrderオペレーションで注文ステータスを取得する

    //***アカウント情報***
    $sellerId = "***セラーID***";
    $awsAccessKeyId = "***AWSアクセスキーID***";
    $secretKey = "***シークレットキー***";
    //***注文番号***。
    $amazonOrderId = "***注文番号***";
    
    $client = new Kumaneko\MwsOrdersClient\Client();
    $request = new Kumaneko\MwsOrdersClient\Request\GetOrderRequest([
         'SellerId' => $sellerId,
         'AWSAccessKeyId' => $awsAccessKeyId,
         'AmazonOrderId' => ['Id' => ['1' => $amazonOrderId]],
         'SecretKey' => $secretKey
     ]);
     $result = $client->getOrderRequest($request);
     $orders = $result->getOrders();
     foreach ($orders as $order) {
         echo "AmazonOrderID: {$order->AmazonOrderId}\n";
         echo "Status: {$oder->OrderStatus}\n";
     }
     