<?php

namespace App\Http\Transformers;

use App\Models\Config;
use League\Fractal\TransformerAbstract;

class kecamatanTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Config $config)
    {
        return $config->toArray();
    }
}
