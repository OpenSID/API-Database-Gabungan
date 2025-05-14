<?php

namespace App\Http\Transformers;

use App\Models\Config;
use League\Fractal\TransformerAbstract;

class ConfigKabupatenTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Config $config)
    {
        return [
            'id' => $config->id,
            'nama_kabupaten' => $config->nama_kabupaten,
            'kode_kabupaten' => $config->kode_kabupaten,
        ];
    }
}
