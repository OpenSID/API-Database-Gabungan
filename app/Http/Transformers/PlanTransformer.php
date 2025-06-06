<?php

namespace App\Http\Transformers;

use App\Models\Lokasi;
use App\Models\Point;
use League\Fractal\TransformerAbstract;

class PlanTransformer extends TransformerAbstract
{
    public function transform(Lokasi $plan)
    {
        return [
            'id' => $plan->id,
            'nama' => $plan->nama,
            'enabled' => $plan->enabled == '1' ? 'Ya' : 'Tidak',
            'jenis' => Point::find($plan->point->parrent)->nama ?? '',
            'kategori' => $plan->point->nama,
            'aksi' => $this->generateAksiColumn($plan),
            'point' => $plan?->point
        ];
    }

    /**
     * Generate "aksi" column for Point data.
     */
    protected function generateAksiColumn(Lokasi $plan): string
    {
        $mainUrl = request()->server('HTTP_ORIGIN');
        if ($plan->point->parent) {
            $aksi = '<a href="'.$mainUrl.'/plan/ajax_lokasi_maps/'.$plan->point->parent->id.'/'.$plan->id.'" class="btn bg-olive btn-sm" title="Lokasi '.$plan->nama.'"><i class="fa fa-map"></i></a> ';
        } else {
            $aksi = '<a href="'.$mainUrl.'/plan/ajax_lokasi_maps/'.$plan->point->id.'/'.$plan->id.'" class="btn bg-olive btn-sm" title="Lokasi '.$plan->nama.'"><i class="fa fa-map"></i></a> ';
        }

        return $aksi ?? '';
    }
}
