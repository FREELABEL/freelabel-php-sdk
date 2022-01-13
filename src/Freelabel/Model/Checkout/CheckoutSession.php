<?php

namespace Freelabel\Model\Checkout;


use Freelabel\Model\Base\BaseModel;

/**
 * Class Product
 *
 * @package Freelabel\Models
 */
class CheckoutSession extends BaseModel
{

    public $id;
    public $user_id;
    public $url;
    public $options;
    public $session_id;
    public $cart_items;
    public $is_active;
    public $created_at;
    public $updated_at;
}
