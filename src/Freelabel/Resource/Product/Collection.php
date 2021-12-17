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

    public function addProducts($collectionId, $productIds) {
        $resourceUrl = $this->resourceUrl . '/' . $collectionId . '/products';
        list($status, , $body) = $this->httpClient->sendHttpRequest(HttpClient::REQUEST_POST, $resourceUrl, null, json_encode(['product_ids' => $productIds]));
        return $this->processRequest($status,$body);
    }


}
