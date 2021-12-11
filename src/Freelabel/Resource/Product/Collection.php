<?php

namespace Freelabel\Resource\Product;
use Freelabel\Http\HttpClient;
use Freelabel\Resource\Base\BaseResource;

/**
 * Class Product
 *
 * @package Freelabel\Resources
 */
class Collection extends BaseResource
{
    protected $resourceUrl = 'user/collections';

    public function __construct(HttpClient $httpClient)
    {
        $this->setModel(new \Freelabel\Model\Product\Collection());
        $this->setResourceUrl($this->resourceUrl);
        parent::__construct($httpClient);
    }
}
