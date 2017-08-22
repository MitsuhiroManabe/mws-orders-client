<?php
namespace Kumaneko\MwsOrdersClient;

use Kumaneko\MwsOrdersClient\Request\ListOrderItemsRequest;
use Kumaneko\MwsOrdersClient\Request\ListOrdersByNextTokenRequest;
use Kumaneko\MwsOrdersClient\Request\ListOrdersRequest;
use Kumaneko\MwsOrdersClient\Request\Request;
use Kumaneko\MwsOrdersClient\Response\ListOrderItemsResponse;
use Kumaneko\MwsOrdersClient\Response\ListOrdersByNextTokenResponse;
use Kumaneko\MwsOrdersClient\Response\ListOrdersResponse;

class Client
{
    protected $config = [
        'connectionTimeout' => 10,
        'timeout' => 60,
        'userAgent' => 'MwsOrdersClient 1.0',
        'maxRetry' => 3,
        'retryWaitSeconds' => 2,
    ];
    protected $headers;
    protected $httpStatusCode;

    public function __construct(array $config = [])
    {
        if($config) {
            $this->config = array_merge($this->config, $config);
        }
    }

    protected function request(Request $request)
    {
        unset($this->headers);
        unset($this->httpStatusCode);
        for ($i = 0; $i < $this->config['maxRetry']; $i++) {
            try {
                $response = $this->_invoke($request);
                $bodyXml = new \SimpleXMLElement($response['body']);
                $headerStrings = preg_split('/\R/', $response['header']);
                $headerStrings = array_filter($headerStrings);
                $headers = [];
                foreach($headerStrings as $headerString) {
                    $split = preg_split('/\:[\s]+/', $headerString);
                    $headers[$split[0]] = (empty($split[1]) ? '' : $split[1]);
                }
                $this->headers = $headers;
                $this->httpStatusCode = $response['statusCode'];
                if($response['statusCode'] != 200) {
                    $message = 'MWSエラー: ' . $response['statusCode'] . "\n";
                    try {
                        $xml = new \SimpleXMLElement($response['body']);
                        if(! empty($xml->Error)) {
                            $errorInfo = $xml->Error;
                            $message .= 'Type: ' . (empty($errorInfo->Type) ? '' : $errorInfo->Type) . "\n";
                            $message .= 'Code: ' . (empty($errorInfo->Code) ? '' : $errorInfo->Code) . "\n";
                            $message .= 'Message: ' . (empty($errorInfo->Message) ? '' : $errorInfo->Message) . "\n";
                        } else {
                            $message .= '不明なエラーです。';
                        }
                    } catch(\Exception $e) {
                        $message .= 'レスポンスボディが空です。';
                    }
                    throw new \Exception($message);
                }
                return $bodyXml;
            } catch (\Exception $e) {
                if ($i < $this->config['maxRetry'] - 1 && $e->getCode() != Request::ERROR_CODE_VALIDATION) {
                    sleep($this->config['retryWaitSeconds']);
                    continue;
                }
                throw new \Exception("MWSリクエストに失敗しました。\n" . $e->getMessage(), $e->getCode(), $e);
            }

        }
        return null;
    }

    protected function _invoke(Request $request)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request->getServiceUrl());
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request->httpQuery());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, empty($this->config['connectionTimeout']) ? 60 : $this->config['connectionTimeout']);
        curl_setopt($ch, CURLOPT_TIMEOUT, empty($this->config['timeout']) ? 600 : $this->config['timeout']);
        curl_setopt($ch, CURLOPT_USERAGENT, empty($this->config['userAgent']) ? '' : $this->config['userAgent']);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header = substr($response, 0, $info['header_size']);
        $body = substr($response, $info['header_size']);
        curl_close($ch);
        return [
            'statusCode' => (int)$info['http_code'],
            'body' => $body,
            'header' => $header,
        ];
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHttpStatusCode()
    {
        if (empty($this->httpStatusCode)) {
            return null;
        } else {
            return $this->httpStatusCode;
        }
    }

    public function listOrdersRequest(ListOrdersRequest $request)
    {
        $xml = $this->request($request);
        return new ListOrdersResponse($xml);
    }

    public function listOrdersByNextTokenRequest(ListOrdersByNextTokenRequest $request) {
        $xml = $this->request($request);
        return new ListOrdersByNextTokenResponse($xml);
    }

    public function listOrderItemsRequest(ListOrderItemsRequest $request) {
        $xml = $this->request($request);
        return new ListOrderItemsResponse($xml);
    }
}