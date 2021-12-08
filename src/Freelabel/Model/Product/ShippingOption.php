<?php

namespace Freelabel\Model\Product;


use Freelabel\Model\Base\BaseModel;

/**
 * Class Product
 *
 * @package Freelabel\Models
 */
class ShippingOption extends BaseModel
{
    public $id;
    public $title;
    public $description;
    public $delivery_time;
    public $shipping_time;
    public $cost;
    public $policy;
    public $profile_id;
    public $image;
    public $thumbnail;
    public $created_at;
    public $updated_at;
}
