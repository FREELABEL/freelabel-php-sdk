<?php

namespace Freelabel\Resource\User;
use Freelabel\Http\HttpClient;
use Freelabel\Model\Base\BaseModelList;
use Freelabel\Resource\Base\BaseResource;

/**
 * Class Profile
 *
 * @package Freelabel\Resources
 */
class User extends BaseResource
{
    protected $resourceUrl = 'user';

    public function __construct(HttpClient $httpClient)
    {
        $this->setModel(new \Freelabel\Model\User\Profile());
        $this->setResourceUrl($this->resourceUrl);
        parent::__construct($httpClient);
    }

    public function getCheckoutSessions($id)
    {
        $resourceUrl = $this->resourceUrl . '/' . $id . '/checkout-sessions';
        list($status, , $body) = $this->httpClient->sendHttpRequest(\Freelabel\Http\HttpClient::REQUEST_GET, $resourceUrl);

        if ($status === 200) {
            $body = json_decode($body);
            $items = $body->data;
            unset($body->data);

            $baseList = new BaseModelList();
            $baseList->loadFromArray($body);

            foreach ($items as $item) {
                /** @psalm-suppress UndefinedClass */
                $model = new \Freelabel\Model\Checkout\CheckoutSession($this->httpClient);
                $message           = $model->loadFromArray($item);
                $baseList->items[] = $message;
            }
            return $baseList;
        }

        return $this->processResponse($status, $body);
    }

}