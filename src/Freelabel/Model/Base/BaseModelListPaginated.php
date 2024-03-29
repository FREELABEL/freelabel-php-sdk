<?php

namespace Freelabel\Model\Base;



class BaseModelListPaginated extends BaseModel
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
