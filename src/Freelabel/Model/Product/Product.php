<?php

namespace Freelabel\Model\Product;


use Freelabel\Model\Base\BaseModel;

/**
 * Class Product
 *
 * @package Freelabel\Models
 */
class Product extends BaseModel
{

    public $id;
    public $reference_no;
    public $product_category_id;
    public $product_subcategory_id;
    public $profile_id;
    public $profile;
    public $photo;
    public $thumbnail;
    public $retail_price;
    public $attributes;
    public $tags;
    public $description;
    public $product_type;
    public $is_active;
    public $type;
    public $title;
    public $subtitle;
    public $price;
    public $variants;
    public $images;
    public $category;
    public $subcategory;
    public $colors;
    public $shippable;
    public $created_at;
    public $updated_at;
}
