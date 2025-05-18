<?php

namespace App\Http\Transformers;

use App\Models\SettingAplikasi;
use League\Fractal\TransformerAbstract;

class SettingAplikasiTransformer extends TransformerAbstract
{

    public function transform(SettingAplikasi $item)
    {
        return $item->attributesToArray();
    }
}
