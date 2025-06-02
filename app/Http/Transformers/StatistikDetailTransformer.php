<?php

namespace App\Http\Transformers;

use App\Models\Rtm;
use League\Fractal\TransformerAbstract;

class StatistikDetailTransformer extends TransformerAbstract
{
    public function transform($item)
    {
        return $item->toArray();
    }
}
