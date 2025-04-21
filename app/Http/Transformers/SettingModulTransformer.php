<?php

namespace App\Http\Transformers;

use App\Enums\AgamaEnum;
use App\Enums\FrekwensiAktivitasKeagamaanEnum;
use App\Models\DataPresisiAgama;
use League\Fractal\TransformerAbstract;

class SettingModulTransformer extends TransformerAbstract
{

    public function transform(DataPresisiAgama $item)
    {
        return $item->attributesToArray();
    }
}
