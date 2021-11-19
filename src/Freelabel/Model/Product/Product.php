<?php

namespace Freelabel\Model\Product;



use Freelabel\Model\Base\BaseModel;

/**
 * Class Balance
 *
 * @package Freelabel\Objects
 */
class Product extends BaseModel
{
    /**
     * Your payment method. Possible values are: prepaid & postpaid
     *
     * @var string
     */
    public $id;

    /**
     * Your payment type. Possible values are: credits & euros
     *
     * @var string
     */
    public $type;
    public $title;

    /**
     * The amount of balance of the payment type. When postpaid is your payment method, the amount will be 0.
     *
     * @var float
     */
    public $amount;
}
