<?php

namespace Freelabel\Model\Base;



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
