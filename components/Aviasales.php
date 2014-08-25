<?php
/**
 * Created by Treschelet.
 * Date: 22.08.14
 */

namespace treschelet\aviasales\components;

use Yii;
use yii\base\Object;
use yii\base\Exception;
use yii\web\HttpException;
use yii\helpers\Json;

class Aviasales extends Object
{
    const CONTENT_TYPE_JSON = 'json'; // JSON format
    const CONTENT_TYPE_URLENCODED = 'urlencoded'; // urlencoded query string, like name1=value1&name2=value2
    const CONTENT_TYPE_XML = 'xml'; // XML format
    const CONTENT_TYPE_AUTO = 'auto'; // attempts to determine format automatically

    const TICKETS_SEARCH_ENGINE = 'http://yasen.aviasales.ru';
    const CITIES_AUTO_COMPLETE = 'http://places.aviasales.ru';
    const HOTEL_SEARCH_ENGINE = 'http://engine.hotellook.com';

    public $marker;
    public $token;
    public $version = '1.0';

    public function getTickets($params)
    {
        $search = [
            'search[params_attributes][origin_name]' => $params['origin'],
            'search[params_attributes][destination_name]' => $params['destination'],
            'search[params_attributes][depart_date]' => date('Y-m-d', strtotime($params['depart'])),
            'search[params_attributes][adults]' => $params['adults'],
            'search[params_attributes][children]' => $params['children'],
            'search[params_attributes][infants]' => $params['infants'],
            'search[params_attributes][trip_class]' => $params['class'] ? 1 : 0,
        ];
        if (!is_null($params['return']) && !empty($params['return'])) {
            $search['search[params_attributes][return_date]'] = date('Y-m-d', strtotime($params['return']));
        }
        ksort($search);
        $signature = md5($this->token.':'.$this->marker.':'.implode(':', array_values($search)));
        $search['signature'] = $signature;
        $search['marker'] = $this->marker;
        $search['locale'] = $params['locale'];
        $search['enable_api_auth'] = true;

        $searchResponse = $this->makeRequest(self::TICKETS_SEARCH_ENGINE, '/searches.json', [], $search);
        $searchResult = [
            'sid' => $searchResponse['search_id'],
            'cache_time' => $searchResponse['search_cache_time'],
            'count' => $searchResponse['metadata']['count'],
            'currency' => $searchResponse['currency_rates'],
            'airports' => $searchResponse['airports'],
            'airlines' => $searchResponse['airlines'],
        ];
        $gates = [];
        foreach($searchResponse['gates_info'] as $gate) {
            $gates[$gate['id']] = $gate;
        }
        $tickets = $searchResponse['tickets'];
        $cmp = function($a, $b) {
            if ($a['total'] == $b['total'])
                return 0;
            return $a['total'] < $b['total'] ? -1 : 1;
        };
        usort($tickets, $cmp);
        $searchResult['gates'] = $gates;
        $searchResult['tickets'] = $tickets;
        return $searchResult;
    }

    public function bookingTickets($search_id, $url_id)
    {
        return $this->makeRequest(self::TICKETS_SEARCH_ENGINE, "/searches/$search_id/order_urls/$url_id", [], ['marker' => $this->marker]);
    }

    public function getPlaces($term, $locale = 'ru')
    {
        return $this->makeRequest(self::CITIES_AUTO_COMPLETE, '/', ['term' => $term, 'locale' => $locale]);
    }

    public function makeRequest($engine, $url, $get = [], $post = [])
    {
        $curlOptions = [
            CURLOPT_URL => $this->composeUrl($engine.$url, $get),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => Yii::$app->name . ' AviaSales ' . $this->version . ' Client',
        ];
        if (!empty($post)) {
            $curlOptions[CURLOPT_POST] = 1;
            $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($post);
        }
        $curlResource = curl_init();
        foreach($curlOptions as $option => $value)
            curl_setopt($curlResource, $option, $value);
        $response = curl_exec($curlResource);
        $responseHeaders = curl_getinfo($curlResource);
        // check cURL error
        $errorNumber = curl_errno($curlResource);
        $errorMessage = curl_error($curlResource);
        curl_close($curlResource);

        if ($errorNumber > 0) {
            throw new Exception('Curl error requesting "' .  $url . '": #' . $errorNumber . ' - ' . $errorMessage);
        }
        if ($responseHeaders['http_code'] != 200) {
            throw new HttpException($responseHeaders['http_code'], 'Request failed with code: ' . $responseHeaders['http_code'] . ', message: ' . $response);
        }

        return $this->processResponse($response, $this->determineContentTypeByHeaders($responseHeaders));
    }

    protected function composeUrl($url, array $params = [])
    {
        if (strpos($url, '?') === false) {
            $url .= '?';
        } else {
            $url .= '&';
        }
        $url .= http_build_query($params, '', '&', PHP_QUERY_RFC3986);

        return $url;
    }

    protected function processResponse($rawResponse, $contentType = self::CONTENT_TYPE_AUTO)
    {
        if (empty($rawResponse)) {
            return [];
        }
        switch ($contentType) {
            case self::CONTENT_TYPE_AUTO: {
                $contentType = $this->determineContentTypeByRaw($rawResponse);
                if ($contentType == self::CONTENT_TYPE_AUTO) {
                    throw new Exception('Unable to determine response content type automatically.');
                }
                $response = $this->processResponse($rawResponse, $contentType);
                break;
            }
            case self::CONTENT_TYPE_JSON: {
                $response = Json::decode($rawResponse, true);
                if (isset($response['error'])) {
                    throw new Exception('Response error: ' . $response['error']);
                }
                break;
            }
            case self::CONTENT_TYPE_URLENCODED: {
                $response = [];
                parse_str($rawResponse, $response);
                break;
            }
            case self::CONTENT_TYPE_XML: {
                $response = $this->convertXmlToArray($rawResponse);
                break;
            }
            default: {
            throw new Exception('Unknown response type "' . $contentType . '".');
            }
        }

        return $response;
    }

    /**
     * Converts XML document to array.
     * @param string|\SimpleXMLElement $xml xml to process.
     * @return array XML array representation.
     */
    protected function convertXmlToArray($xml)
    {
        if (!is_object($xml)) {
            $xml = simplexml_load_string($xml);
        }
        $result = (array) $xml;
        foreach ($result as $key => $value) {
            if (is_object($value)) {
                $result[$key] = $this->convertXmlToArray($value);
            }
        }

        return $result;
    }

    /**
     * Attempts to determine HTTP request content type by headers.
     * @param array $headers request headers.
     * @return string content type.
     */
    protected function determineContentTypeByHeaders(array $headers)
    {
        if (isset($headers['content_type'])) {
            if (stripos($headers['content_type'], 'json') !== false) {
                return self::CONTENT_TYPE_JSON;
            }
            if (stripos($headers['content_type'], 'urlencoded') !== false) {
                return self::CONTENT_TYPE_URLENCODED;
            }
            if (stripos($headers['content_type'], 'xml') !== false) {
                return self::CONTENT_TYPE_XML;
            }
        }

        return self::CONTENT_TYPE_AUTO;
    }

    /**
     * Attempts to determine the content type from raw content.
     * @param string $rawContent raw response content.
     * @return string response type.
     */
    protected function determineContentTypeByRaw($rawContent)
    {
        if (preg_match('/^\\{.*\\}$/is', $rawContent)) {
            return self::CONTENT_TYPE_JSON;
        }
        if (preg_match('/^[^=|^&]+=[^=|^&]+(&[^=|^&]+=[^=|^&]+)*$/is', $rawContent)) {
            return self::CONTENT_TYPE_URLENCODED;
        }
        if (preg_match('/^<.*>$/is', $rawContent)) {
            return self::CONTENT_TYPE_XML;
        }

        return self::CONTENT_TYPE_AUTO;
    }
}