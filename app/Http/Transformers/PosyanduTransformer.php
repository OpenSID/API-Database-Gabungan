<?php

namespace App\Http\Transformers;

use App\Models\Posyandu;
use League\Fractal\TransformerAbstract;

class PosyanduTransformer extends TransformerAbstract
{
    public function transform(Posyandu $posyandu)
    {
        return [
            'id' => $posyandu['id'],
            'config_id' => $posyandu['config_id'],
            'nama' => strtoupper($posyandu['nama']),
            'alamat' => $posyandu['alamat'],
        ];
    }
}
