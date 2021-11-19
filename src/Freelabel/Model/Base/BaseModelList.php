<?php

namespace Freelabel\Model\Base;


/**
 * Class BaseList
 *
 * @package Freelabel\Objects
 */
class BaseModelList extends BaseModel
{
    public $limit;
    public $offset;
    public $count;
    public $totalCount;
    public $links =  [
        'first'    => null,
        'previous' => null,
        'next'     => null,
        'last'     => null,
    ];

    public $items =  [];
}
