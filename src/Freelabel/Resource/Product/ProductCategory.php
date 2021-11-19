<?php

namespace Freelabel\Resource\Product;
use Freelabel\Http\HttpClient;
use Freelabel\Resource\Base\BaseResource;

/**
 * Class ProductType
 *
 * @package Freelabel\Resources
 */
class ProductCategory extends BaseResource
{
    protected $resourceUrl = 'product-categories';

    public function __construct(HttpClient $httpClient)
    {
        $this->setModel(new \Freelabel\Model\Product\ProductCategory());
        $this->setResourceUrl($this->resourceUrl);
        parent::__construct($httpClient);
    }
}
