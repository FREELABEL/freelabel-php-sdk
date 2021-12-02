<?php

namespace Freelabel\Http;

use Freelabel\Authentication\Auth;
use Freelabel\Common;
use Freelabel\Exceptions\AuthenticateException;
use Freelabel\Exceptions\HttpException;
use GuzzleHttp\Client;

use function Couchbase\defaultDecoder;


/**
 * Class HttpClient
 *
 * @package Freelabel\Common
 */
class HttpClient
{
    const REQUEST_GET = 'GET';
    const REQUEST_POST = 'POST';
    const REQUEST_DELETE = 'DELETE';
    const REQUEST_PUT = 'PUT';
    const REQUEST_PATCH = "PATCH";

    const HTTP_NO_CONTENT = 204;
    const HTTP_VALIDATION_ERROR = 422;
    const HTTP_UNAUTHORIZED = 401;
    const SERVER_ERROR = 500;

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var array
     */
    protected $userAgent = [];

    /**
     * @var Common\Authentication
     */
    protected $authentication;

    /**
     * @var int
     */
    private $timeout = 10;

    /**
     * @var int
     */
    private $connectionTimeout = 2;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
     */
    private $httpOptions = [];

    /**
     * @param string $endpoint
     * @param int    $timeout           > 0
     * @param int    $connectionTimeout >= 0
     * @param array  $headers
     */
    public function __construct($endpoint, $timeout = 10, $connectionTimeout = 2, $headers = [])
    {
        $this->endpoint = $endpoint;

        if (!is_int($timeout) || $timeout < 1) {
            throw new \InvalidArgumentException(
                sprintf(
                'Timeout must be an int > 0, got "%s".',
                is_object($timeout) ? get_class($timeout) : gettype($timeout).' '.var_export($timeout, true)
            )
            );
        }

        $this->timeout = $timeout;

        if (!is_int($connectionTimeout) || $connectionTimeout < 0) {
            throw new \InvalidArgumentException(
                sprintf(
                'Connection timeout must be an int >= 0, got "%s".',
                is_object($connectionTimeout) ? get_class($connectionTimeout) : gettype($connectionTimeout).' '.var_export($connectionTimeout, true)
            )
            );
        }

        $this->connectionTimeout = $connectionTimeout;
        $this->headers = $headers;
    }

    /**
     * @param string $userAgent
     *
     * @return void
     */
    public function addUserAgentString($userAgent): void
    {
        $this->userAgent[] = $userAgent;
    }

    /**
     * @param Auth $authentication
     *
     * @return void
     */
    public function setAuthentication(Auth $authentication): void
    {
        $this->authentication = $authentication;
    }

    /**
     * @param string $resourceUrl
     * @param mixed  $query
     *
     * @return string
     */
    public function getRequestUrl($resourceUrl, $query)
    {
        $requestUrl = $this->endpoint . '/' . $resourceUrl;
        if ($query) {
            if (is_array($query)) {
                $query = http_build_query($query);
            }
            $requestUrl .= '?' . $query;
        }

        return $requestUrl;
    }

    /**
     * @param array $headers
     *
     * @return void
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @param mixed $option
     * @param mixed $value
     *
     * @return void
     */
    public function addHttpOption($option, $value): void
    {
        $this->httpOptions[$option] = $value;
    }

    /**
     * @param mixed $option
     * @return mixed|null
     */
    public function getHttpOption($option)
    {
        return $this->httpOptions[$option] ?? null;
    }

    /**
     * @param string      $method
     * @param string      $resourceUrl
     * @param mixed       $query
     * @param string|null $body
     *
     * @return array
     *
     * @throws Exceptions\AuthenticateException
     * @throws Exceptions\HttpException
     */
    public function sendHttpRequest($method, $resourceUrl, $query = null, $body = null)
    {

        if ($this->authentication === null) {
            throw new AuthenticateException('Can not perform API Request without Authentication');
        }

        $headers =  [
            'User-agent' => implode(' ', $this->userAgent),
            'Accept' => 'application/json',
            'Content-Type' =>  'application/json',
            'Accept-Charset' => 'utf-8',
            'Authorization' => sprintf('Bearer %s', $this->authentication->getAccessToken())
        ];

        $headers = array_merge($headers, $this->headers);

        $client = new Client(['http_errors' => false]);
        $response = $client->request($method, $this->getRequestUrl($resourceUrl, $query), [
            'headers' => $headers,
            'body' => $body
        ]);

        return  [$response->getStatusCode(), $response->getHeaders(), $response->getBody()];
    }

    /**
     * @param int $timeout
     * @return $this
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @param int $connectionTimeout
     * @return $this
     */
    public function setConnectionTimeout(int $connectionTimeout)
    {
        $this->connectionTimeout = $connectionTimeout;
        return $this;
    }
}
