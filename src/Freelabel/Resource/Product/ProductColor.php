<?php

namespace Freelabel\Resource\Product;
use Freelabel\Http\HttpClient;
use Freelabel\Resource\Base\BaseResource;

/**
 * Class Product
 *
 * @package Freelabel\Resources
 */
class ProductColor extends BaseResource
{
    protected $resourceUrl = 'user/products/colors';

    public function __construct(HttpClient $httpClient)
    {
        $this->setModel(new \Freelabel\Model\Product\ProductColor());
        $this->setResourceUrl($this->resourceUrl);
        parent::__construct($httpClient);
    }
}
