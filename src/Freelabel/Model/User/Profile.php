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
    public $facebook;
    public $instagram;
    public $twitter;
    public $twitter_id;
    public $twitter_data;
    public $twitch;
    public $twitch_id;
    public $soundcloud;
    public $soundcloud_data;
    public $youtube;
    public $youtube_channel_id;
    public $spotify_id;
    public $spotify;
    public $apple;
    public $layout_id;
    public $layout_config;
    public $camsoda;
    public $mfc;
    public $onlyfans;
    public $billing_user_id;
    public $enable_fan_tips;
    public $links;
    public $products;
    public $collections;
    public $created_at;
    public $updated_at;
}
