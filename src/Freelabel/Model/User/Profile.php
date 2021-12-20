<?php

namespace Freelabel\Model\User;


use Freelabel\Model\Base\BaseModel;

/**
 * Class Product
 *
 * @package Freelabel\Models
 */
class Profile extends BaseModel
{

    public $pk;
    public $id;
    public $user_id;
    public $active;
    public $name;
    public $bio;
    public $city;
    public $state;
    public $email;
    public $phone;
    public $photo;
    public $brand;

    public $created_at;
    public $updated_at;
}
