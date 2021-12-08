<?php

namespace Freelabel\Resource\Product;
use Freelabel\Http\HttpClient;
use Freelabel\Resource\Base\BaseResource;

/**
 * Class Product
 *
 * @package Freelabel\Resources
 */
class ShippingOption extends BaseResource
{
    protected $resourceUrl = 'user/products/shipping-options';

    public function __construct(HttpClient $httpClient)
    {
        $this->setModel(new \Freelabel\Model\Product\ShippingOption());
        $this->setResourceUrl($this->resourceUrl);
        parent::__construct($httpClient);
    }
}
