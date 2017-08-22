<?php
namespace Kumaneko\MwsOrdersClient\Request;

class Request
{
    protected $defaultParams = [
        'MarketplaceId' => ['id' => ['1' => 'A1VC38T7YXB528']],
        'SignatureMethod' => 'HmacSHA256',
        'SignatureVersion' => '2',
        'Version' => '2013-09-01',
    ];
    protected $defaultSchema = [
        'SellerId' => ['type' => 'string', 'required' => true],
        'AWSAccessKeyId' => ['type' => 'string', 'required' => true],
    ];
    protected $schema = [];
    protected $params = [];
    protected $operationName;
    protected $serviceUrl = 'https://mws.amazonservices.jp/Orders/2013-09-01';
    protected $secretKey;
    const ERROR_CODE_VALIDATION = 101;

    /**
     * Request constructor.
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->schema += $this->defaultSchema;
        $this->params = $this->defaultParams;
        foreach ($params as $name => $param) {
            $methodName = 'set' . ucfirst($name);
            if (method_exists($this, $methodName)) {
                $this->$methodName($param);
            } else {
                $this->setParam($name, $param);
            }
        }
        $this->params['Action'] = $this->operationName;
    }

    public function __set($name, $value)
    {
        $methodName = 'set' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName($value);
        }
        $this->params[$name] = $value;
        return $this->params[$name];
    }

    protected function setParam($name, $value)
    {
        if (array_key_exists($name, $this->schema)) {
            switch ($this->schema[$name]['type']) {
                case 'datetime':
                    if (! $value instanceof \DateTime) {
                       $value = new \DateTime($value);
                    }
                    $value->setTimezone(new \DateTimeZone('UTC'));
                    $this->params[$name] = $value;
                    break;
                default:
                    $this->params[$name] = $value;
                    break;
            }
        } else {
            throw new \Exception("リクエストパラメータ{$name}は受け付けていません。", self::ERROR_CODE_VALIDATION);
        }
        return $this->params[$name];
    }

    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);
        if (method_exists($this, $methodName)) {
            return $this->$methodName($name);
        } elseif (isset($this->params[$name])) {
            return $this->params[$name];
        } else {
            return null;
        }
    }

    /**
     * @param string $url
     * @return string
     */
    public function setServiceUrl($url)
    {
        $this->serviceUrl = $url;
        return $this->serviceUrl;
    }

    /**
     * @return string
     */
    public function getServiceUrl()
    {
        return $this->serviceUrl;
    }

    /**
     * @param string $key
     * @return string
     */
    public function setSecretKey($key)
    {
        $this->secretKey = $key;
        return $this->secretKey;
    }

    /**
     * @return array
     */
    public function getFormattedParams()
    {
        $formattedParams = [];
        foreach($this->params as $key => $value) {
            $type = ! empty($this->schema[$key]) ?  $this->schema[$key]['type'] : null;
            $formattedParams = $this->formatParam($formattedParams, $key, $value, $type);
        }
        return $formattedParams;
    }

    /**
     * @param array $formattedParams
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return array
     */
    protected function formatParam($formattedParams, $key, $value, $type)
    {
        if(is_array($value)) {
            foreach($value as $k => $v) {
                $formattedParams = $this->formatParam($formattedParams, $key . '.' . $k, $v, $type);
            }
        } elseif ($type === 'datetime') {
            /** @var \DateTime $value */
            $formattedParams[$key] = $value->format('Y-m-d\TH:i:s\Z');
        } else {
            $formattedParams[$key] = $value;
        }
        return $formattedParams;
    }

    /**
     * @return string
     */
    public function httpQuery()
    {
        $this->validate();
        $formattedParams = $this->getFormattedParams();
        $formattedParams = $this->addTimeStamp($formattedParams);
        $formattedParams = $this->addSignature($formattedParams);
        return http_build_query($formattedParams);
    }

    /**
     * @param array $formattedParams
     * @return array
     */
    protected function addTimeStamp($formattedParams)
    {
        $formattedParams['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
        return $formattedParams;
    }

    /**
     * @param array $formattedParams
     * @return array
     * @throws \Exception
     */
    protected function addSignature($formattedParams)
    {
        ksort($formattedParams, SORT_STRING);
        if(empty($formattedParams['AWSAccessKeyId']) || empty($this->secretKey)) {
            throw new \Exception('AWSアクセスキーが設定されていません。');
        }
        $parsedUrl = parse_url($this->serviceUrl);
        $header = "POST\n" . $parsedUrl['host'] . "\n";
        $path = empty($parsedUrl['path']) ? '/' : $parsedUrl['path'];
        $header .= $path . "\n";
        $header .= http_build_query($formattedParams);
        $formattedParams['Signature'] = base64_encode(hash_hmac('sha256', $header, $this->secretKey, true));
        return $formattedParams;
    }

    protected function validate()
    {
        foreach ($this->schema as $key => $values) {
            if (empty($this->params[$key])) {
                if (! empty($values['required'])) {
                    throw new \Exception("リクエストパラメータ{$key}は必須項目です。", self::ERROR_CODE_VALIDATION);
                }
            } else {
                if (! empty($values['options'])) {
                    if (! in_array($this->params[$key], $values['options'])) {
                        throw new \Exception("値{$this->params[$key]}はリクエストパラメータ{$key}の値として入力できません。", self::ERROR_CODE_VALIDATION);
                    }
                }
            }
        }
    }
}
