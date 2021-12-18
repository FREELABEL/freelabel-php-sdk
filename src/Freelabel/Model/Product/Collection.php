<?php

namespace Freelabel\Model\Product;


use Freelabel\Model\Base\BaseModel;

/**
 * Class Product
 *
 * @package Freelabel\Models
 */
class Collection extends BaseModel
{
    public $id;
    public $title;
    public $subtitle;
    public $description;
    public $tags;
    public $options;
    public $photos;
    public $thumbnail;
    public $profile;
    public $products;
    public $created_at;
    public $updated_at;
}
