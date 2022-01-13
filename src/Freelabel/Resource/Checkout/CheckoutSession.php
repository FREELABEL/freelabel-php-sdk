<?php

namespace Freelabel\Resource\Checkout;
use Freelabel\Http\HttpClient;
use Freelabel\Model\Base\BaseModelList;
use Freelabel\Resource\Base\BaseResource;

/**
 * Class Profile
 *
 * @package Freelabel\Resources
 */
class CheckoutSession extends BaseResource
{
    protected $resourceUrl = 'user/checkout-session';

    public function __construct(HttpClient $httpClient)
    {
        $this->setModel(new \Freelabel\Model\Checkout\CheckoutSession());
        $this->setResourceUrl($this->resourceUrl);
        parent::__construct($httpClient);
    }
}
