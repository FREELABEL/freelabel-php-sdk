<?php

namespace Freelabel\Model\Product;



use Freelabel\Model\Base\BaseModel;

/**
 * Class Product
 *
 * @package Freelabel\Models
 */
class ProductType extends BaseModel
{
    public $id;
    public $title;
    public $slug;
    public $image;
    public $thumbnail;
    public $description;
    public $is_active;
    public $created_at;
    public $updated_at;
}
