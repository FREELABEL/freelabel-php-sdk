<?php

namespace Freelabel\Model\Product;



use Freelabel\Model\Base\BaseModel;

/**
 * Class ProductVariant
 *
 * @package Freelabel\Models
 */
class ProductVariant extends BaseModel
{

    public $id;
    public $product;
    public $size;
    public $image;
    public $thumbnail;
    public $retail_price;
    public $attributes;
    public $tags;
    public $description;
    public $type;
    public $title;
    public $price;
    public $created_at;
    public $updated_at;
    public $is_active;
}
