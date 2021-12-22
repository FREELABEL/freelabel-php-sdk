<?php

namespace Freelabel\Model\Product;


use Freelabel\Model\Base\BaseModel;

/**
 * Class Product
 *
 * @package Freelabel\Models
 */
class ProductColor extends BaseModel
{
    public $id;
    public $title;
    public $photos;
    public $product_id;
    public $created_at;
    public $updated_at;
}
