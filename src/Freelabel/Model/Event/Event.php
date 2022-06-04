<?php

namespace Freelabel\Model\Event;


use Freelabel\Model\Base\BaseModel;

/**
 * Class Product
 *
 * @package Freelabel\Models
 */
class Event extends BaseModel
{

    public $pk;
    public $id;
    public $title;
    public $city;
    public $state;
    public $stages;
    public $media;
    public $photo;
    public $links;
    public $tickets;
    public $travel_packages;
    public $vendor_groups;
    public $created_at;
    public $updated_at;
}
