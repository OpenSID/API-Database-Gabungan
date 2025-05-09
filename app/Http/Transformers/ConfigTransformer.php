<?php

namespace App\Http\Transformers;

use App\Models\Config;
use League\Fractal\TransformerAbstract;

class ConfigTransformer extends TransformerAbstract
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
            'nama_desa' => $config->nama_desa,
            'kode_desa' => $config->kode_desa,
            'nama_kecamatan' => $config->nama_kecamatan,
            'kode_kecamatan' => $config->kode_kecamatan,
        ];
    }
}
