<?php

namespace App\Http\Transformers;

use App\Models\SettingModul;
use League\Fractal\TransformerAbstract;

class SettingModulTransformer extends TransformerAbstract
{

    public function transform(SettingModul $item)
    {
        return $item->attributesToArray();
    }
}
