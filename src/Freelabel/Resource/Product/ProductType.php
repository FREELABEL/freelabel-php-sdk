<?php

namespace Freelabel\Resource\Product;
use Freelabel\Http\HttpClient;
use Freelabel\Resource\Base\BaseResource;

/**
 * Class ProductType
 *
 * @package Freelabel\Resources
 */
class ProductType extends BaseResource
{
    protected $resourceUrl = 'user/products';

    public function __construct(HttpClient $httpClient)
    {
        $this->setModel(new \Freelabel\Model\Product\Product());
        $this->setResourceUrl($this->resourceUrl);
        parent::__construct($httpClient);
    }
}
