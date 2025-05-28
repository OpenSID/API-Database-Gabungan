<?php

namespace App\Http\Transformers;

use App\Models\Wilayah;
use League\Fractal\TransformerAbstract;

class WilayahTransformer extends TransformerAbstract
{
    public function transform(Wilayah $wilayah)
    {
        return [
            'id' => $wilayah->id,
        ];
    }

}
