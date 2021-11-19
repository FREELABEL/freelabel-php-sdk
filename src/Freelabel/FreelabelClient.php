<?php

namespace Freelabel;


use Freelabel\Authentication\Auth;
use Freelabel\Http\HttpClient;
use Freelabel\Resource\Product\Product;
use Freelabel\Resource\Product\ProductCategory;
use Freelabel\Resource\Product\ProductType;

/**
 * Class Client
 *
 * @package Freelabel
 */
class FreelabelClient {

    const ENDPOINT = 'http://api.freelabel.us/api/v1';

    protected $endpoint = self::ENDPOINT;


    /**
     * @var Resources\Product
     */
    public $product;

    /**
     * @var Resources\ProductType
     */
    public $productType;

    /**
     * @var Resources\ProductCategory
     */
    public $productCategory;


    public function __construct($accessKey = null, HttpClient $httpClient = null, array $config = [])
    {
        if ($httpClient === null) {
            $this->httpClient = new HttpClient(self::ENDPOINT);
        } else {
            $this->httpClient = $httpClient;
        }

        $this->httpClient->addUserAgentString('Freelabel/ApiClient/' . '1.0');
        $this->httpClient->addUserAgentString($this->getPhpVersion());

        if ($accessKey !== null) {
            $this->setToken($accessKey);
        }

        $this->product = new Product($this->httpClient);
        $this->productType = new ProductType($this->httpClient);
        $this->productCategory = new ProductCategory($this->httpClient);
    }


    public function setToken($token)
    {
        $authentication = new Auth($token);

        $this->httpClient->setAuthentication($authentication);
    }

    private function getPhpVersion()
    {
        if (!defined('PHP_VERSION_ID')) {
            $version = array_map('intval', explode('.', PHP_VERSION));
            define('PHP_VERSION_ID', $version[0] * 10000 + $version[1] * 100 + $version[2]);
        }

        return 'PHP/' . PHP_VERSION_ID;
    }


}
