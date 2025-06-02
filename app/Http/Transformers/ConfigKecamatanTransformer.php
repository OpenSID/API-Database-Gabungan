<?php

namespace App\Http\Transformers;

use App\Models\Config;
use League\Fractal\TransformerAbstract;

class ConfigKecamatanTransformer extends TransformerAbstract
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
            'nama_kecamatan' => $config->nama_kecamatan,
            'kode_kecamatan' => $config->kode_kecamatan,
        ];
    }
}
