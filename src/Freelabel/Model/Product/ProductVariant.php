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
    public $price;
    public $title;
    public $color;
    public $size;
    public $quantity_available;
    public $quantity_total;
    public $options;
    public $tags;
    public $product_id;
    public $product_category_size_id;
    public $product_color_id;
    public $retail_price;
    public $image;
    public $thumbnail;
    public $description;
    public $created_at;
    public $updated_at;
}
