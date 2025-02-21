<?php

namespace App\Http\Transformers;

use App\Enums\Dtks\Regsosek2022kEnum;
use App\Models\Papan;
use League\Fractal\TransformerAbstract;

class PapanPresisiTransformer extends TransformerAbstract
{
    public function transform(Papan $papan)
    {  
        $dtksFieldMapping = array_flip(Papan::$dtksFieldMapping);
        $pilihanBagian3 = Regsosek2022kEnum::pilihanBagian3();
        foreach ($dtksFieldMapping as $key => $value) {
            $papan->{$key} = $pilihanBagian3[$value][$papan->{$key}] ?? '';
        }
        $papan->id = $papan->rtm_id;
        $papan->makeHidden('rtm', 'uuid', 'dtks');
        $papan->nik_kepala_rtm = $papan->rtm?->kepalaKeluarga?->nik;
        return $papan->toArray();
    }
}
