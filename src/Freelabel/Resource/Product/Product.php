<?php

namespace Freelabel\Resource\Product;
use Freelabel\Http\HttpClient;
use Freelabel\Model\Base\BaseModelList;
use Freelabel\Resource\Base\BaseResource;

/**
 * Class Product
 *
 * @package Freelabel\Resources
 */
class Product extends BaseResource
{
    protected $resourceUrl = 'user/products';
    protected $adminResourceUrl = 'admin/products';

    public function __construct(HttpClient $httpClient)
    {
        $this->setModel(new \Freelabel\Model\Product\Product());
        $this->setResourceUrl($this->resourceUrl);
        parent::__construct($httpClient);
    }

    public function getColors($id)
    {
        $resourceUrl = $this->resourceUrl . (($id) ? '/' . $id : null) . '/colors';
        list($status, , $body) = $this->httpClient->sendHttpRequest(\Freelabel\Http\HttpClient::REQUEST_GET, $resourceUrl);

        if ($status === 200) {
            $body = json_decode($body);
            $items = $body->data;
            unset($body->data);

            $baseList = new BaseModelList();
            $baseList->loadFromArray($body);

            foreach ($items as $item) {
                /** @psalm-suppress UndefinedClass */
                $model = new \Freelabel\Model\Product\ProductColor($this->httpClient);

                $message           = $model->loadFromArray($item);
                $baseList->items[] = $message;
            }
            return $baseList;
        }

        return $this->processRequest($status, $body);
    }
    public function getVariants($id)
    {
        $resourceUrl = $this->resourceUrl . (($id) ? '/' . $id : null) . '/product-variants';
        list($status, , $body) = $this->httpClient->sendHttpRequest(\Freelabel\Http\HttpClient::REQUEST_GET, $resourceUrl);

        if ($status === 200) {
            $body = json_decode($body);
            $items = $body->data;
            unset($body->data);

            $baseList = new BaseModelList();
            $baseList->loadFromArray($body);

            foreach ($items as $item) {
                /** @psalm-suppress UndefinedClass */
                $model = new \Freelabel\Model\Product\ProductVariant($this->httpClient);

                $message           = $model->loadFromArray($item);
                $baseList->items[] = $message;
            }
            return $baseList;
        }

        return $this->processRequest($status, $body);
    }


}
