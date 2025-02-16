<?php

namespace App\Http\Transformers;

use App\Models\Suplemen;
use League\Fractal\TransformerAbstract;

class SuplemenTransformer extends TransformerAbstract
{
    public function transform(Suplemen $suplemen)
    {
        // Transform data suplemen
        return [
            'id' => $suplemen->id,
            'nama' => $suplemen->nama,
            'sasaran' => unserialize(SASARAN)[$suplemen->sasaran] ?? 'Tidak Diketahui',
            'status' => unserialize(STATUS_SUPLEMEN)[$suplemen->status] ?? 'Tidak Diketahui',
            'keterangan' => $suplemen->keterangan,
            'terdata_count' => $suplemen->terdata_count,
            'aksi' => $this->generateAksiColumn($suplemen),
        ];
    }

    /**
     * Generate "aksi" column for Suplemen data.
     */
    protected function generateAksiColumn(Suplemen $suplemen): string
{
    $aksi = '';

    // Ambil base URL dari request (untuk aplikasi utama)
    $mainUrl = request()->server('HTTP_ORIGIN'); // Hasil: http://127.0.0.1:8989

    $delete = $suplemen->terdata_count > 0 ? '' : '<button type="button" class="btn btn-danger btn-sm hapus mr-1" data-id="'.$suplemen->id.'" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>';

    $edit = $suplemen->terdata_count > 0 ? '' : '<a href="'.$mainUrl.'/suplemen/form/'.$suplemen->id.'" class="btn btn-warning btn-sm" title="Edit Data"><i class="fa fa-pencil"></i></a> ';

    // Rincian Data
    $aksi .= '<a href="'.$mainUrl.'/suplemen/rincian/'.$suplemen->id.'" class="btn bg-purple btn-sm" title="Rincian Data"><i class="fa fa-list-ol"></i></a> ';

    $aksi .= $delete;
    $aksi .= $edit;

    return $aksi;
}

}
