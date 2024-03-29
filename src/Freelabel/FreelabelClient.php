<?php

namespace Freelabel;

use Freelabel\Authentication\Auth;
use Freelabel\Http\HttpClient;
use Freelabel\Resource\Checkout\CheckoutSession;
use Freelabel\Resource\Product\Collection;
use Freelabel\Resource\Product\Product;
use Freelabel\Resource\Product\ProductCategory;
use Freelabel\Resource\Product\ProductColor;
use Freelabel\Resource\Product\ProductType;
use Freelabel\Resource\Product\ProductVariant;
use Freelabel\Resource\Product\ShippingOption;
use Freelabel\Resource\User\Profile;
use Freelabel\Resource\Event\Event;
use Freelabel\Resource\User\User;

/**
 * Class Client
 *
 * @package Freelabel
 */
class FreelabelClient
{
    const ENDPOINT = 'http://api.freelabel.us/api/v1';

    protected $endpoint = self::ENDPOINT;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var Product
     */
    public $product;

    /**
     * @var ProductVariant
     */
    public $productVariant;

    /**
     * @var ProductType
     */
    public $productType;

    /**
     * @var ProductCategory
     */
    public $productCategory;

    /**
     * @var ShippingOption
     */
    public $shippingOption;

    /**
     * @var Collection
     */
    public $productCollection;

    /**
     * @var ProductColor
     */
    public $productColor;

    /**
     * @var CheckoutSession
     */
    public $checkoutSession;

    /**
     * @var User
     */
    public $user;

    /**
     * @var Profile
     */
    public $userProfile;

    /**
     * @var Event
     */
    public $event;

    public function __construct($baseUrl, $accessKey = null, HttpClient $httpClient = null, array $config = [])
    {
        $this->endpoint = $baseUrl;
        if ($httpClient === null) {
            $this->httpClient = new HttpClient($this->endpoint);
        } else {
            $this->httpClient = $httpClient;
        }

        $this->httpClient->addUserAgentString('Freelabel/ApiClient/' . '1.0');
        $this->httpClient->addUserAgentString($this->getPhpVersion());

        if ($accessKey !== null) {
            $this->setToken($accessKey);
        }

        $this->product = new Product($this->httpClient);
        $this->productVariant = new ProductVariant($this->httpClient);
        $this->productType = new ProductType($this->httpClient);
        $this->productCategory = new ProductCategory($this->httpClient);
        $this->shippingOption = new ShippingOption($this->httpClient);
        $this->event = new Event($this->httpClient);
        $this->productCollection = new Collection($this->httpClient);
        $this->productColor = new ProductColor($this->httpClient);
        $this->userProfile = new Profile($this->httpClient);
        $this->checkoutSession = new CheckoutSession($this->httpClient);
        $this->user = new User($this->httpClient);
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
