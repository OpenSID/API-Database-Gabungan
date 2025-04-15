<?php

namespace App\Http\Transformers;

use App\Models\DataPresisiJaminanSosial;
use League\Fractal\TransformerAbstract;

class DataPresisiJaminanSosialTransformer extends TransformerAbstract
{
    public function transform(DataPresisiJaminanSosial $item)
    {
        $item->id = $item->uuid;
        return $item->toArray();
    }
}
