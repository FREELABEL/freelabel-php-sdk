<?php

namespace Freelabel\Resource\Product;
use Freelabel\Http\HttpClient;
use Freelabel\Resource\Base\BaseResource;

/**
 * Class Product
 *
 * @package Freelabel\Resources
 */
class ProductVariant extends BaseResource
{
    protected $resourceUrl = 'user/products/product-variant';

    public function __construct(HttpClient $httpClient)
    {
        $this->setModel(new \Freelabel\Model\Product\ProductVariant());
        $this->setResourceUrl($this->resourceUrl);
        parent::__construct($httpClient);
    }
}
